<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function index()
    {
        return response()->json(
            Insumo::with(['tipo', 'unidadMedida'])->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Insumo::with(['tipo', 'unidadMedida', 'loteInsumos'])->findOrFail($id)
        );
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

        $insumo = Insumo::create($data);

        return response()->json($insumo, 201);
    }

    public function update(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'tipoinsumoid' => 'sometimes|exists:tipoinsumo,tipoinsumoid',
            'unidadmedidaid' => 'sometimes|exists:unidadmedida,unidadmedidaid',
            'stock' => 'sometimes|numeric|min:0',
            'stockminimo' => 'sometimes|numeric|min:0',
            'proveedor' => 'nullable|string|max:100',
            'preciounitario' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $insumo->update($data);

        return response()->json($insumo);
    }

    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();

        return response()->json(['message' => 'Eliminado correctamente']);
    }
}