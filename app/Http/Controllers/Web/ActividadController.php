<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Lote;
use App\Models\Usuario;
use App\Models\TipoActividad;
use App\Models\Prioridad;
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

    public function create()
    {
        $lotes = Lote::all();
        $usuarios = Usuario::all();
        $tipos = TipoActividad::all();
        $prioridades = Prioridad::all();

        return view('actividades.create', compact('lotes', 'usuarios', 'tipos', 'prioridades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'descripcion' => 'required|string|max:200',
            'fechainicio' => 'nullable|date',
            'fechafin' => 'nullable|date|after_or_equal:fechainicio',
            'tipoactividadid' => 'required|exists:tipoactividad,tipoactividadid',
            'prioridadid' => 'required|exists:prioridad,prioridadid',
            'observaciones' => 'nullable|string|max:250',
        ]);

        Actividad::create($data);

        return redirect()->route('actividades.index')->with('success', 'Actividad creada.');
    }

    public function show(Actividad $actividad)
    {
        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad)
    {
        $lotes = Lote::all();
        $usuarios = Usuario::all();
        $tipos = TipoActividad::all();
        $prioridades = Prioridad::all();

        return view('actividades.edit', compact('actividad', 'lotes', 'usuarios', 'tipos', 'prioridades'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'descripcion' => 'required|string|max:200',
            'fechainicio' => 'nullable|date',
            'fechafin' => 'nullable|date|after_or_equal:fechainicio',
            'tipoactividadid' => 'required|exists:tipoactividad,tipoactividadid',
            'prioridadid' => 'required|exists:prioridad,prioridadid',
            'observaciones' => 'nullable|string|max:250',
        ]);

        $actividad->update($data);

        return redirect()->route('actividades.index')->with('success', 'Actividad actualizada.');
    }

    public function destroy(Actividad $actividad)
    {
        $actividad->delete();

        return redirect()->route('actividades.index')->with('success', 'Actividad eliminada.');
    }
}