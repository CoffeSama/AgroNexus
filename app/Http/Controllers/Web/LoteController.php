<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Usuario;
use App\Models\Cultivo;
use App\Models\EstadoLoteTipo;
use App\Models\Produccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    public function index()
    {
        $lotes = Lote::with(['usuario', 'cultivo', 'estadoTipo'])
            ->orderBy('loteid', 'desc')
            ->paginate(15);

        return view('lotes.index', compact('lotes'));
    }

    /**
     * Mapa interactivo de todos los lotes
     */
    public function mapa()
    {
        // Estadísticas
        $stats = [
            'total' => Lote::count(),
            'en_produccion' => Lote::whereHas('estadoTipo', fn($q) => $q->where('nombre', 'en producción'))->count(),
            'cosechados' => Lote::whereHas('estadoTipo', fn($q) => $q->where('nombre', 'cosechado'))->count(),
            'hectareas' => Lote::sum('superficie') ?? 0,
        ];

        // Lotes con coordenadas para el mapa
        $lotesConCoordenadas = Lote::with(['usuario', 'cultivo', 'estadoTipo'])
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->get()
            ->map(function($lote) {
                return [
                    'id' => $lote->loteid,
                    'nombre' => $lote->nombre,
                    'latitud' => (float) $lote->latitud,
                    'longitud' => (float) $lote->longitud,
                    'superficie' => (float) $lote->superficie,
                    'ubicacion' => $lote->ubicacion,
                    'propietario' => ($lote->usuario->nombre ?? '') . ' ' . ($lote->usuario->apellido ?? ''),
                    'cultivo' => $lote->cultivo->nombre ?? null,
                    'estado' => $lote->estadoTipo->nombre ?? 'disponible',
                    'usuarioid' => $lote->usuarioid,
                    'cultivoid' => $lote->cultivoid,
                    'estadoid' => $lote->estadolotetipoid,
                ];
            });

        // Lotes sin coordenadas (para alertas)
        $lotesSinCoordenadas = Lote::with(['usuario'])
            ->where(function($q) {
                $q->whereNull('latitud')->orWhereNull('longitud');
            })
            ->limit(5)
            ->get();

        // Top lotes por producción
        $topLotes = Lote::with(['usuario', 'cultivo'])
            ->select('lote.*')
            ->selectSub(
                Produccion::selectRaw('COALESCE(SUM(cantidad), 0)')
                    ->whereColumn('produccion.loteid', 'lote.loteid'),
                'total_produccion'
            )
            ->orderByDesc('total_produccion')
            ->limit(5)
            ->get();

        // Alertas climáticas (placeholder - se puede conectar con la tabla Clima)
        $alertasClimaticas = collect([]);

        // Lotes que podrían necesitar insumos (en producción sin actividad reciente)
        $lotesStockBajo = Lote::with(['usuario'])
            ->whereHas('estadoTipo', fn($q) => $q->whereIn('nombre', ['sembrado', 'en producción']))
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->limit(3)
            ->get();

        // Datos para filtros
        $usuarios = Usuario::orderBy('nombre')->get();
        $cultivos = Cultivo::orderBy('nombre')->get();
        $estados = EstadoLoteTipo::orderBy('nombre')->get();

        return view('lotes.mapa', compact(
            'stats',
            'lotesConCoordenadas',
            'lotesSinCoordenadas',
            'topLotes',
            'alertasClimaticas',
            'lotesStockBajo',
            'usuarios',
            'cultivos',
            'estados'
        ));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        $cultivos = Cultivo::all();
        $estados = EstadoLoteTipo::all();

        return view('lotes.create', compact('usuarios', 'cultivos', 'estados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:200',
            'superficie' => 'required|numeric|min:0',
            'cultivoid' => 'nullable|exists:cultivo,cultivoid',
            'fechasiembra' => 'nullable|date',
            'estadolotetipoid' => 'nullable|exists:estadolote_tipo,estadolotetipoid',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Procesar imagen si se subió
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreArchivo = 'lote_' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images/lotes'), $nombreArchivo);
            $data['imagenurl'] = 'images/lotes/' . $nombreArchivo;
        }

        unset($data['imagen']); // Remover del array antes de crear

        Lote::create($data);

        return redirect()->route('lotes.index')->with('success', 'Lote creado exitosamente.');
    }

    public function show(Lote $lote)
    {
        $lote->load(['usuario', 'cultivo', 'estadoTipo']);
        return view('lotes.show', compact('lote'));
    }

    public function edit(Lote $lote)
    {
        $usuarios = Usuario::all();
        $cultivos = Cultivo::all();
        $estados = EstadoLoteTipo::all();

        return view('lotes.edit', compact('lote', 'usuarios', 'cultivos', 'estados'));
    }

    public function update(Request $request, Lote $lote)
    {
        $data = $request->validate([
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:200',
            'superficie' => 'required|numeric|min:0',
            'cultivoid' => 'nullable|exists:cultivo,cultivoid',
            'fechasiembra' => 'nullable|date',
            'estadolotetipoid' => 'nullable|exists:estadolote_tipo,estadolotetipoid',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Procesar nueva imagen si se subió
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($lote->imagenurl && file_exists(public_path($lote->imagenurl))) {
                unlink(public_path($lote->imagenurl));
            }

            $imagen = $request->file('imagen');
            $nombreArchivo = 'lote_' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('images/lotes'), $nombreArchivo);
            $data['imagenurl'] = 'images/lotes/' . $nombreArchivo;
        }

        unset($data['imagen']);

        $lote->update($data);

        return redirect()->route('lotes.index')->with('success', 'Lote actualizado.');
    }

    public function destroy(Lote $lote)
    {
        // Eliminar imagen si existe
        if ($lote->imagenurl && file_exists(public_path($lote->imagenurl))) {
            unlink(public_path($lote->imagenurl));
        }

        $lote->delete();

        return redirect()->route('lotes.index')->with('success', 'Lote eliminado.');
    }
}