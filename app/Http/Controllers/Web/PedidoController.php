<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Cultivo;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['cultivo', 'unidadMedida'])->get();
        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        return view('pedidos.create', [
            'cultivos' => Cultivo::all(),
            'unidades' => UnidadMedida::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_planta' => 'required|string|max:150',
            'cultivoid' => 'nullable|exists:cultivo,cultivoid',
            'cultivo_personalizado' => 'nullable|string|max:150',
            'unidadmedidaid' => 'required|exists:unidadmedida,unidadmedidaid',
            'cantidad' => 'required|numeric|min:0.01',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'direccion_texto' => 'nullable|string|max:255',
            'estado' => 'required|in:pendiente,confirmado,en produccion,rechazado',
            'fechaEntregaDeseada' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        if (empty($data['cultivoid']) && empty($data['cultivo_personalizado'])) {
            return back()
                ->withErrors('Debe especificar un cultivo o escribir uno personalizado')
                ->withInput();
        }

        Pedido::create($data);

        return redirect()->route('pedidos.index');
    }

    public function show($id)
    {
        $pedido = Pedido::with(['cultivo', 'unidadMedida'])->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        $data = $request->validate([
            'estado' => 'required|in:pendiente,confirmado,en produccion,rechazado',
        ]);

        $pedido->update($data);

        return back();
    }

    public function destroy($id)
    {
        Pedido::findOrFail($id)->delete();
        return redirect()->route('pedidos.index');
    }
}