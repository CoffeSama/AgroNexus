<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\UsuarioRol;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioRolController extends Controller
{
    public function index()
    {
        $usuarioRoles = UsuarioRol::with(['usuario', 'rol'])
            ->orderBy('usuariorolid', 'desc')
            ->paginate(15);

        return view('usuario_roles.index', compact('usuarioRoles'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        $roles = Rol::all();

        return view('usuario_roles.create', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'rolid' => 'required|exists:rol,rolid',
        ]);

        UsuarioRol::create($data);

        return redirect()->route('usuario-roles.index')->with('success', 'Rol asignado al usuario.');
    }

    public function show(UsuarioRol $usuarioRole)
    {
        return view('usuario_roles.show', compact('usuarioRole'));
    }

    public function edit(UsuarioRol $usuarioRole)
    {
        $usuarios = Usuario::all();
        $roles = Rol::all();

        return view('usuario_roles.edit', compact('usuarioRole', 'usuarios', 'roles'));
    }

    public function update(Request $request, UsuarioRol $usuarioRole)
    {
        $data = $request->validate([
            'usuarioid' => 'required|exists:usuario,usuarioid',
            'rolid' => 'required|exists:rol,rolid',
        ]);

        $usuarioRole->update($data);

        return redirect()->route('usuario-roles.index')->with('success', 'Asignación actualizada.');
    }

    public function destroy(UsuarioRol $usuarioRole)
    {
        $usuarioRole->delete();

        return redirect()->route('usuario-roles.index')->with('success', 'Asignación eliminada.');
    }
}