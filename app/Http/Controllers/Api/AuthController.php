<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * REGISTRO - crea usuario y devuelve token + datos
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:100',
            'email'            => 'required|email|max:100|unique:usuario,email',
            'nombreusuario'    => 'required|string|max:100|unique:usuario,nombreusuario',
            'telefono'         => 'nullable|string|max:20',
            'password'         => 'required|string|min:6',
            'imagenurl'        => 'nullable|string|max:250',
            'informacionadicional' => 'nullable|string',
        ]);

        $usuario = new Usuario();
        $usuario->nombre               = $data['nombre'];
        $usuario->apellido             = $data['apellido'];
        $usuario->email                = $data['email'];
        $usuario->nombreusuario        = $data['nombreusuario'];
        $usuario->telefono             = $data['telefono'] ?? null;
        $usuario->passwordhash         = Hash::make($data['password']);
        $usuario->imagenurl            = $data['imagenurl'] ?? null;
        $usuario->informacionadicional = $data['informacionadicional'] ?? null;
        $usuario->activo               = true;

        $usuario->save();

        // Token para usar inmediatamente
        $token = $usuario->createToken('mobile')->plainTextToken;

        return response()->json([
            'user'  => $usuario,
            'token' => $token,
        ], 201);
    }

    /**
     * LOGIN - Devuelve token + datos del usuario
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email', $data['email'])->first();

        if (!$usuario || !Hash::check($data['password'], $usuario->passwordhash)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $token = $usuario->createToken('mobile')->plainTextToken;

        return response()->json([
            'user'  => $usuario,
            'token' => $token
        ]);
    }

    /**
     * Usuario autenticado (requiere token)
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * LOGOUT - revoca el token actual
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}