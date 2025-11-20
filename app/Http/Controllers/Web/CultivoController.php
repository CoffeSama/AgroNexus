<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cultivo;
use Illuminate\Http\Request;

class CultivoController extends Controller
{
    public function index()
    {
        $cultivos = Cultivo::orderBy('cultivoid', 'desc')->paginate(15);

        return view('cultivos.index', compact('cultivos'));
    }

    public function create()
    {
        return view('cultivos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
        ]);

        Cultivo::create($data);

        return redirect()
            ->route('cultivos.index')
            ->with('success', 'Cultivo creado correctamente.');
    }

    public function show(Cultivo $cultivo)
    {
        return view('cultivos.show', compact('cultivo'));
    }

    public function edit(Cultivo $cultivo)
    {
        return view('cultivos.edit', compact('cultivo'));
    }

    public function update(Request $request, Cultivo $cultivo)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
        ]);

        $cultivo->update($data);

        return redirect()
            ->route('cultivos.index')
            ->with('success', 'Cultivo actualizado correctamente.');
    }

    public function destroy(Cultivo $cultivo)
    {
        $cultivo->delete();

        return redirect()
            ->route('cultivos.index')
            ->with('success', 'Cultivo eliminado correctamente.');
    }
}