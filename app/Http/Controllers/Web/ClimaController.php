<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Clima;
use App\Models\Lote;
use Illuminate\Http\Request;

class ClimaController extends Controller
{
    public function index()
    {
        $climas = Clima::with('lote')
            ->orderBy('climaid', 'desc')
            ->paginate(15);

        return view('climas.index', compact('climas'));
    }

    public function create()
    {
        $lotes = Lote::all();

        return view('climas.create', compact('lotes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'fecha' => 'nullable|date',
            'temperatura' => 'nullable|numeric',
            'humedad' => 'nullable|numeric|min:0|max:100',
            'lluvia' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:200',
        ]);

        Clima::create($data);

        return redirect()->route('climas.index')->with('success', 'Registro climático creado.');
    }

    public function show(Clima $clima)
    {
        return view('climas.show', compact('clima'));
    }

    public function edit(Clima $clima)
    {
        $lotes = Lote::all();

        return view('climas.edit', compact('clima', 'lotes'));
    }

    public function update(Request $request, Clima $clima)
    {
        $data = $request->validate([
            'loteid' => 'required|exists:lote,loteid',
            'fecha' => 'nullable|date',
            'temperatura' => 'nullable|numeric',
            'humedad' => 'nullable|numeric|min:0|max:100',
            'lluvia' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:200',
        ]);

        $clima->update($data);

        return redirect()->route('climas.index')->with('success', 'Registro climático actualizado.');
    }

    public function destroy(Clima $clima)
    {
        $clima->delete();

        return redirect()->route('climas.index')->with('success', 'Registro climático eliminado.');
    }
}