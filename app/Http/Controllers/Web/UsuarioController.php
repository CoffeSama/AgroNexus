<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::orderBy('usuarioid', 'desc')->paginate(15);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:usuario,email',
            'nombreusuario' => 'required|string|max:100|unique:usuario,nombreusuario',
            'telefono' => 'nullable|string|max:20',
            'passwordhash' => 'required|string|max:250',
            'imagenurl' => 'nullable|string|max:250',
            'informacionadicional' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        Usuario::create($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:usuario,email,' . $usuario->usuarioid . ',usuarioid',
            'nombreusuario' => 'required|string|max:100|unique:usuario,nombreusuario,' . $usuario->usuarioid . ',usuarioid',
            'telefono' => 'nullable|string|max:20',
            'passwordhash' => 'nullable|string|max:250',
            'imagenurl' => 'nullable|string|max:250',
            'informacionadicional' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        if (!$request->filled('passwordhash')) {
            unset($data['passwordhash']);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }
}