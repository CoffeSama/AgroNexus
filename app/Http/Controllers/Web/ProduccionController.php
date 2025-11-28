<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Produccion;
use App\Models\Lote;
use App\Models\DestinoProduccion;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    public function index()
    {
        $producciones = Produccion::with(['lote', 'destino', 'unidadMedida'])
            ->orderBy('produccionid', 'desc')
            ->paginate(15);

        return view('producciones.index', compact('producciones'));
    }

    public function create()
    {
        $lotes     = Lote::all();
        $destinos  = DestinoProduccion::all();
        $unidades  = UnidadMedida::all(); // 👈 nuevo

        return view('producciones.create', compact('lotes', 'destinos', 'unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loteid'              => 'required|exists:lote,loteid',
            'cantidadkg'          => 'nullable|numeric|min:0',
            'unidadmedidaid'      => 'nullable|exists:unidadmedida,unidadmedidaid',
            'fechacosecha'        => 'nullable|date',
            'destinoproduccionid' => 'nullable|exists:destinoproduccion,destinoproduccionid',
            'imagenurl'           => 'nullable|string|max:250',
            'observaciones'       => 'nullable|string',
        ]);

        Produccion::create($data);

        return redirect()
            ->route('producciones.index')
            ->with('success', 'Producción creada.');
    }

    public function show(Produccion $produccion)
    {
        $produccion->load(['lote', 'destino', 'unidadMedida', 'almacenamientos']);

        return view('producciones.show', compact('produccion'));
    }

    public function edit(Produccion $produccion)
    {
        $lotes     = Lote::all();
        $destinos  = DestinoProduccion::all();
        $unidades  = UnidadMedida::all(); // 👈 nuevo

        return view('producciones.edit', compact('produccion', 'lotes', 'destinos', 'unidades'));
    }

    public function update(Request $request, Produccion $produccion)
    {
        $data = $request->validate([
            'loteid'              => 'required|exists:lote,loteid',
            'cantidadkg'          => 'nullable|numeric|min:0',
            'unidadmedidaid'      => 'nullable|exists:unidadmedida,unidadmedidaid',
            'fechacosecha'        => 'nullable|date',
            'destinoproduccionid' => 'nullable|exists:destinoproduccion,destinoproduccionid',
            'imagenurl'           => 'nullable|string|max:250',
            'observaciones'       => 'nullable|string',
        ]);

        $produccion->update($data);

        return redirect()
            ->route('producciones.index')
            ->with('success', 'Producción actualizada.');
    }

    public function destroy(Produccion $produccion)
    {
        $produccion->delete();

        return redirect()
            ->route('producciones.index')
            ->with('success', 'Producción eliminada.');
    }
}