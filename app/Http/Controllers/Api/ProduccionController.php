<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produccion;
use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    public function index()
    {
        return response()->json(
            Produccion::with(['lote', 'destino'])->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Produccion::with(['lote', 'destino', 'venta'])->findOrFail($id)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'cantidadkg' => 'nullable|numeric|min:0',
            'fechacosecha' => 'nullable|date',
            'destinoproduccionid' => 'nullable|exists:destinoproduccion,destinoproduccionid',
            'imagenurl' => 'nullable|string|max:250',
            'observaciones' => 'nullable|string',
        ]);

        $produccion = Produccion::create($data);

        return response()->json($produccion, 201);
    }

    public function update(Request $request, $id)
    {
        $produccion = Produccion::findOrFail($id);

        $data = $request->validate([
            'loteid' => 'sometimes|exists:lote,loteid',
            'cantidadkg' => 'nullable|numeric|min:0',
            'fechacosecha' => 'nullable|date',
            'destinoproduccionid' => 'nullable|exists:destinoproduccion,destinoproduccionid',
            'imagenurl' => 'nullable|string|max:250',
            'observaciones' => 'nullable|string',
        ]);

        $produccion->update($data);

        return response()->json($produccion);
    }

    public function destroy($id)
    {
        $produccion = Produccion::findOrFail($id);
        $produccion->delete();

        return response()->json(['message' => 'Eliminado correctamente']);
    }
}