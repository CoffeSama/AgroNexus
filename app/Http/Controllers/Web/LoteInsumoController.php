<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LoteInsumo;
use App\Models\Lote;
use App\Models\Insumo;
use App\Models\Usuario;
use App\Models\EstadoLoteInsumo;
use Illuminate\Http\Request;

class LoteInsumoController extends Controller
{
    public function index()
    {
        $loteInsumos = LoteInsumo::with(['lote', 'insumo', 'usuario', 'estado'])
            ->orderBy('loteinsumoid', 'desc')
            ->paginate(15);

        return view('lote_insumos.index', compact('loteInsumos'));
    }

    public function create()
    {
        $lotes = Lote::all();
        $insumos = Insumo::all();
        $usuarios = Usuario::all();
        $estados = EstadoLoteInsumo::all();

        return view('lote_insumos.create', compact('lotes', 'insumos', 'usuarios', 'estados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'insumoid' => 'required|exists:insumo,insumoid',
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'cantidadusada' => 'required|numeric|gt:0',
            'fechauo' => 'nullable|date',
            'costototal' => 'nullable|numeric|min:0',
            'estadoloteinsumoid' => 'nullable|exists:estadoloteinsumo,estadoloteinsumoid',
            'observaciones' => 'nullable|string|max:200',
        ]);

        LoteInsumo::create($data);

        return redirect()->route('lote-insumos.index')->with('success', 'Aplicación de insumo registrada.');
    }

    public function show(LoteInsumo $loteInsumo)
    {
        return view('lote_insumos.show', compact('loteInsumo'));
    }

    public function edit(LoteInsumo $loteInsumo)
    {
        $lotes = Lote::all();
        $insumos = Insumo::all();
        $usuarios = Usuario::all();
        $estados = EstadoLoteInsumo::all();

        return view('lote_insumos.edit', compact('loteInsumo', 'lotes', 'insumos', 'usuarios', 'estados'));
    }

    public function update(Request $request, LoteInsumo $loteInsumo)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'insumoid' => 'required|exists:insumo,insumoid',
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'cantidadusada' => 'required|numeric|gt:0',
            'fechauo' => 'nullable|date',
            'costototal' => 'nullable|numeric|min:0',
            'estadoloteinsumoid' => 'nullable|exists:estadoloteinsumo,estadoloteinsumoid',
            'observaciones' => 'nullable|string|max:200',
        ]);

        $loteInsumo->update($data);

        return redirect()->route('lote-insumos.index')->with('success', 'Aplicación de insumo actualizada.');
    }

    public function destroy(LoteInsumo $loteInsumo)
    {
        $loteInsumo->delete();

        return redirect()->route('lote-insumos.index')->with('success', 'Aplicación de insumo eliminada.');
    }
}