<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Insumo;
use App\Models\TipoInsumo;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function index()
    {
        $insumos = Insumo::with(['tipo', 'unidadMedida'])
            ->orderBy('insumoid', 'desc')
            ->paginate(15);

        return view('insumos.index', compact('insumos'));
    }

    public function create()
    {
        $tipos = TipoInsumo::all();
        $unidades = UnidadMedida::all();

        return view('insumos.create', compact('tipos', 'unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'tipoinsumoid' => 'required|exists:tipoinsumo,tipoinsumoid',
            'unidadmedidaid' => 'required|exists:unidadmedida,unidadmedidaid',
            'stock' => 'required|numeric|min:0',
            'stockminimo' => 'required|numeric|min:0',
            'proveedor' => 'nullable|string|max:100',
            'preciounitario' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        Insumo::create($data);

        return redirect()->route('insumos.index')->with('success', 'Insumo creado.');
    }

    public function show(Insumo $insumo)
    {
        return view('insumos.show', compact('insumo'));
    }

    public function edit(Insumo $insumo)
    {
        $tipos = TipoInsumo::all();
        $unidades = UnidadMedida::all();

        return view('insumos.edit', compact('insumo', 'tipos', 'unidades'));
    }

    public function update(Request $request, Insumo $insumo)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'tipoinsumoid' => 'required|exists:tipoinsumo,tipoinsumoid',
            'unidadmedidaid' => 'required|exists:unidadmedida,unidadmedidaid',
            'stock' => 'required|numeric|min:0',
            'stockminimo' => 'required|numeric|min:0',
            'proveedor' => 'nullable|string|max:100',
            'preciounitario' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $insumo->update($data);

        return redirect()->route('insumos.index')->with('success', 'Insumo actualizado.');
    }

    public function destroy(Insumo $insumo)
    {
        $insumo->delete();

        return redirect()->route('insumos.index')->with('success', 'Insumo eliminado.');
    }
}