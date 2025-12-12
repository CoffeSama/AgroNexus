<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        return response()->json(
            Pedido::with(['cultivo', 'unidadMedida'])->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Pedido::with(['cultivo', 'unidadMedida'])->findOrFail($id)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_planta' => 'required|string|max:150',

            // uno de los dos
            'cultivoid' => 'nullable|exists:cultivo,cultivoid',
            'cultivo_personalizado' => 'nullable|string|max:150',

            'unidadmedidaid' => 'required|exists:unidadmedida,unidadmedidaid',
            'cantidad' => 'required|numeric|min:0.01',

            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'direccion_texto' => 'nullable|string|max:255',

            'estado' => 'required|in:pendiente,confirmado,en produccion,rechazado',

            'fechapedido' => 'nullable|date',
            'fechaEntregaDeseada' => 'nullable|date',

            'observaciones' => 'nullable|string',
        ]);

        // Validación lógica: uno u otro obligatorio
        if (empty($data['cultivoid']) && empty($data['cultivo_personalizado'])) {
            return response()->json([
                'message' => 'Debe especificar cultivoid o cultivo_personalizado'
            ], 422);
        }

        $pedido = Pedido::create($data);

        return response()->json(
            Pedido::with(['cultivo', 'unidadMedida'])->find($pedido->pedidoid),
            201
        );
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $data = $request->validate([
            'nombre_planta' => 'sometimes|string|max:150',

            'cultivoid' => 'nullable|exists:cultivo,cultivoid',
            'cultivo_personalizado' => 'nullable|string|max:150',

            'unidadmedidaid' => 'sometimes|exists:unidadmedida,unidadmedidaid',
            'cantidad' => 'sometimes|numeric|min:0.01',

            'latitud' => 'sometimes|numeric|between:-90,90',
            'longitud' => 'sometimes|numeric|between:-180,180',
            'direccion_texto' => 'nullable|string|max:255',

            'estado' => 'sometimes|in:pendiente,confirmado,en produccion,rechazado',

            'fechapedido' => 'nullable|date',
            'fechaEntregaDeseada' => 'nullable|date',

            'observaciones' => 'nullable|string',
        ]);

        $pedido->update($data);

        return response()->json(
            Pedido::with(['cultivo', 'unidadMedida'])->find($pedido->pedidoid)
        );
    }

    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return response()->json([
            'message' => 'Pedido eliminado correctamente'
        ]);
    }
}