<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('usuario', 'email')->ignore($user->usuarioid, 'usuarioid')],
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->nombre = $data['nombre'];
        $user->apellido = $data['apellido'];
        $user->email = $data['email'];
        $user->telefono = $data['telefono'];

        if ($request->filled('password')) {
            $user->passwordhash = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
    }
}
