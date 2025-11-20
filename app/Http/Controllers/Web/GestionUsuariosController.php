<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\UsuarioRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GestionUsuariosController extends Controller
{
    // =========================================================
    // INDEX GLOBAL
    // =========================================================
    public function index()
    {
        $usuarios = Usuario::with(['roles'])->orderBy('usuarioid', 'desc')->paginate(15);
        $roles = Rol::orderBy('rolid', 'asc')->get();
        $editarUsuario = null;
        $editarRol = null;
        if (request()->has('editarUsuario')) {
            $editarUsuario = Usuario::with('roles')->find(request('editarUsuario'));
        }

        if (request()->has('editarRol')) {
            $editarRol = Rol::find(request('editarRol'));
        }

        return view('usuarios.index', compact('usuarios','roles','editarUsuario','editarRol'));
    }

    // =========================================================
    // USUARIOS CRUD
    // =========================================================

    public function storeUsuario(Request $request)
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
            'rolid' => 'nullable|exists:rol,rolid'
        ]);

        // Hashear password
        $data['passwordhash'] = Hash::make($data['passwordhash']);

        $usuario = Usuario::create($data);

        if ($request->filled('rolid')) {
            UsuarioRol::create([
                'usuarioid' => $usuario->usuarioid,
                'rolid' => $request->rolid
            ]);
        }

        return redirect()->route('gestion.index')->with('success', 'Usuario creado.');
    }

    public function updateUsuario(Request $request, Usuario $usuario)
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
            'rolid' => 'nullable|exists:rol,rolid'
        ]);

        // Si viene nueva contraseña, la hasheamos; si no, la quitamos del array
        if ($request->filled('passwordhash')) {
            $data['passwordhash'] = Hash::make($data['passwordhash']);
        } else {
            unset($data['passwordhash']);
        }

        $usuario->update($data);

        if ($request->filled('rolid')) {
            UsuarioRol::updateOrCreate(
                ['usuarioid' => $usuario->usuarioid],
                ['rolid' => $request->rolid]
            );
        }

        return redirect()->route('gestion.index')->with('success', 'Usuario actualizado.');
    }

    public function destroyUsuario(Usuario $usuario)
    {
        UsuarioRol::where('usuarioid', $usuario->usuarioid)->delete();
        $usuario->delete();

        return redirect()->route('gestion.index')->with('success', 'Usuario eliminado.');
    }

    // =========================================================
    // ROLES CRUD
    // =========================================================

    public function storeRol(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:200',
        ]);

        Rol::create($data);

        return redirect()->route('gestion.index')->with('success', 'Rol creado.');
    }

    public function updateRol(Request $request, Rol $rol)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:200',
        ]);

        $rol->update($data);

        return redirect()->route('gestion.index')->with('success', 'Rol actualizado.');
    }

    public function destroyRol(Rol $rol)
    {
        UsuarioRol::where('rolid', $rol->rolid)->delete();
        $rol->delete();

        return redirect()->route('gestion.index')->with('success', 'Rol eliminado.');
    }
}