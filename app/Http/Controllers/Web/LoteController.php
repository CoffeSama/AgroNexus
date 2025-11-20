<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Usuario;
use App\Models\Cultivo;
use App\Models\EstadoLoteTipo;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function index()
    {
        $lotes = Lote::with(['usuario', 'cultivo', 'estadoTipo'])
            ->orderBy('loteid', 'desc')
            ->paginate(15);

        return view('lotes.index', compact('lotes'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        $cultivos = Cultivo::all();
        $estados = EstadoLoteTipo::all();

        return view('lotes.create', compact('usuarios', 'cultivos', 'estados'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:200',
            'superficie' => 'required|numeric|min:0',
            'cultivoid' => 'nullable|exists:cultivo,cultivoid',
            'fechasiembra' => 'nullable|date',
            'estadolotetipoid' => 'nullable|exists:estadolote_tipo,estadolotetipoid',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'imagenurl' => 'nullable|string|max:250',
        ]);

        Lote::create($data);

        return redirect()->route('lotes.index')->with('success', 'Lote creado.');
    }

    public function show(Lote $lote)
    {
        return view('lotes.show', compact('lote'));
    }

    public function edit(Lote $lote)
    {
        $usuarios = Usuario::all();
        $cultivos = Cultivo::all();
        $estados = EstadoLoteTipo::all();

        return view('lotes.edit', compact('lote', 'usuarios', 'cultivos', 'estados'));
    }

    public function update(Request $request, Lote $lote)
    {
        $data = $request->validate([
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'nullable|string|max:200',
            'superficie' => 'required|numeric|min:0',
            'cultivoid' => 'nullable|exists:cultivo,cultivoid',
            'fechasiembra' => 'nullable|date',
            'estadolotetipoid' => 'nullable|exists:estadolote_tipo,estadolotetipoid',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'imagenurl' => 'nullable|string|max:250',
        ]);

        $lote->update($data);

        return redirect()->route('lotes.index')->with('success', 'Lote actualizado.');
    }

    public function destroy(Lote $lote)
    {
        $lote->delete();

        return redirect()->route('lotes.index')->with('success', 'Lote eliminado.');
    }
}