<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class UnidadMedidaController extends Controller
{
    public function index()
    {
        $unidades = UnidadMedida::orderBy('unidadmedidaid', 'desc')->paginate(15);

        return view('unidades_medida.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidades_medida.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:20'],
        ]);

        UnidadMedida::create($data);

        return redirect()
            ->route('unidades-medida.index')
            ->with('success', 'Unidad de medida creada correctamente.');
    }

    public function show(UnidadMedida $unidades_medido)
    {
        // Si prefieres, cambia el nombre de la variable para que sea más legible:
        $unidad = $unidades_medido;

        return view('unidades_medida.show', compact('unidad'));
    }

    public function edit(UnidadMedida $unidades_medido)
    {
        $unidad = $unidades_medido;

        return view('unidades_medida.edit', compact('unidad'));
    }

    public function update(Request $request, UnidadMedida $unidades_medido)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:20'],
        ]);

        $unidades_medido->update($data);

        return redirect()
            ->route('unidades-medida.index')
            ->with('success', 'Unidad de medida actualizada correctamente.');
    }

    public function destroy(UnidadMedida $unidades_medido)
    {
        $unidades_medido->delete();

        return redirect()
            ->route('unidades-medida.index')
            ->with('success', 'Unidad de medida eliminada correctamente.');
    }
}