<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Lote;
use App\Models\TipoActividad;
use App\Models\Prioridad;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index()
    {
        $actividades = Actividad::with(['lote', 'usuario', 'tipoActividad', 'prioridad'])
            ->orderBy('actividadid', 'desc')
            ->paginate(15);

        return view('actividades.index', compact('actividades'));
    }

    /**
     * Calendario de actividades
     */
    public function calendario()
    {
        // Estadísticas
        $stats = [
            'mes' => Actividad::whereMonth('fechainicio', now()->month)
                ->whereYear('fechainicio', now()->year)
                ->count(),
            'hoy' => Actividad::whereDate('fechainicio', now()->toDateString())->count(),
            'pendientes' => Actividad::whereNull('fechafin')->count(),
            'completadas' => Actividad::whereNotNull('fechafin')->count(),
        ];

        // Eventos para el calendario
        $actividades = Actividad::with(['lote', 'usuario', 'tipoActividad'])
            ->whereNotNull('fechainicio')
            ->get();

        $eventos = $actividades->map(function($act) {
            $tipo = $act->tipoActividad->nombre ?? 'Actividad';
            $lote = $act->lote->nombre ?? 'Sin lote';
            
            return [
                'id' => $act->actividadid,
                'title' => $tipo . ' - ' . $lote,
                'start' => $act->fechainicio,
                'end' => $act->fechafin,
                'extendedProps' => [
                    'id' => $act->actividadid,
                    'tipo' => $tipo,
                    'lote' => $lote,
                    'loteid' => $act->loteid,
                    'responsable' => ($act->usuario->nombre ?? '') . ' ' . ($act->usuario->apellido ?? ''),
                    'usuarioid' => $act->usuarioid,
                    'fechafin' => $act->fechafin,
                    'observaciones' => $act->observaciones,
                ]
            ];
        });

        // Datos para filtros y formulario
        $lotes = Lote::with('cultivo')->orderBy('nombre')->get();
        $usuarios = Usuario::orderBy('nombre')->get();
        $tiposActividad = TipoActividad::orderBy('nombre')->get();

        return view('actividades.calendario', compact(
            'stats',
            'eventos',
            'lotes',
            'usuarios',
            'tiposActividad'
        ));
    }

    public function create()
    {
        $lotes = Lote::with('usuario')->get();
        $tipos = TipoActividad::all();
        $prioridades = Prioridad::all();

        return view('actividades.create', compact('lotes', 'tipos', 'prioridades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'descripcion' => 'nullable|string|max:200',
            'tipoactividadid' => 'required|exists:tipoactividad,tipoactividadid',
            'prioridadid' => 'nullable|exists:prioridad,prioridadid',
            'fechainicio' => 'nullable|date',
            'fechafin' => 'nullable|date|after_or_equal:fechainicio',
            'observaciones' => 'nullable|string|max:250',
        ]);

        // Obtener el lote para asignar usuario automáticamente
        $lote = Lote::findOrFail($data['loteid']);
        $tipo = TipoActividad::find($data['tipoactividadid']);

        // Si no hay descripción, usar el tipo de actividad
        if (empty($data['descripcion'])) {
            $data['descripcion'] = $tipo->nombre ?? 'Actividad';
        }

        // Prioridad por defecto si no se envió
        if (empty($data['prioridadid'])) {
            $prioridadDefault = Prioridad::first();
            $data['prioridadid'] = $prioridadDefault ? $prioridadDefault->prioridadid : null;
        }

        Actividad::create([
            'loteid' => $data['loteid'],
            'usuarioid' => $lote->usuarioid,
            'descripcion' => $data['descripcion'],
            'fechainicio' => $data['fechainicio'] ?? now(),
            'fechafin' => $data['fechafin'] ?? null,
            'tipoactividadid' => $data['tipoactividadid'],
            'prioridadid' => $data['prioridadid'],
            'observaciones' => $data['observaciones'] ?? null,
        ]);

        // Detectar si viene del calendario
        if ($request->has('from_calendar') || $request->header('referer') && str_contains($request->header('referer'), 'calendario')) {
            return redirect()->route('actividades.calendario')
                ->with('success', "Actividad de {$tipo->nombre} registrada para el lote {$lote->nombre}.");
        }

        return redirect()->route('actividades.index')
            ->with('success', "Actividad de {$tipo->nombre} registrada para el lote {$lote->nombre}.");
    }

    public function show(Actividad $actividad)
    {
        $actividad->load(['lote', 'usuario', 'tipoActividad', 'prioridad']);
        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad)
    {
        $lotes = Lote::with('usuario')->get();
        $tipos = TipoActividad::all();
        $prioridades = Prioridad::all();

        return view('actividades.edit', compact('actividad', 'lotes', 'tipos', 'prioridades'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'descripcion' => 'required|string|max:200',
            'fechainicio' => 'nullable|date',
            'fechafin' => 'nullable|date|after_or_equal:fechainicio',
            'tipoactividadid' => 'required|exists:tipoactividad,tipoactividadid',
            'prioridadid' => 'required|exists:prioridad,prioridadid',
            'observaciones' => 'nullable|string|max:250',
        ]);

        // Obtener usuario del lote
        $lote = Lote::findOrFail($data['loteid']);
        $data['usuarioid'] = $lote->usuarioid;

        $actividad->update($data);

        return redirect()->route('actividades.index')->with('success', 'Actividad actualizada.');
    }

    public function destroy(Actividad $actividad)
    {
        $actividad->delete();

        return redirect()->route('actividades.index')->with('success', 'Actividad eliminada.');
    }
}