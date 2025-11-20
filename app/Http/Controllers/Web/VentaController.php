<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Produccion;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('produccion')
            ->orderBy('ventaid', 'desc')
            ->paginate(15);

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $producciones = Produccion::all();

        return view('ventas.create', compact('producciones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'produccionid' => 'required|exists:produccion,produccionid',
            'cliente' => 'nullable|string|max:100',
            'cantidadkg' => 'nullable|numeric|min:0',
            'preciokg' => 'nullable|numeric|min:0',
            'fechaventa' => 'nullable|date',
            'observaciones' => 'nullable|string|max:200',
        ]);

        Venta::create($data);

        return redirect()->route('ventas.index')->with('success', 'Venta registrada.');
    }

    public function show(Venta $venta)
    {
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $producciones = Produccion::all();

        return view('ventas.edit', compact('venta', 'producciones'));
    }

    public function update(Request $request, Venta $venta)
    {
        $data = $request->validate([
            'produccionid' => 'required|exists:produccion,produccionid',
            'cliente' => 'nullable|string|max:100',
            'cantidadkg' => 'nullable|numeric|min:0',
            'preciokg' => 'nullable|numeric|min:0',
            'fechaventa' => 'nullable|date',
            'observaciones' => 'nullable|string|max:200',
        ]);

        $venta->update($data);

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada.');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada.');
    }
}