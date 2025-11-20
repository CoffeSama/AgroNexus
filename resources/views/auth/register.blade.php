@extends('layouts.auth')

@section('title', 'Registro | AgroNexus')
@section('card_title', 'Crear cuenta')

@section('content')
<div class="container">
    <h5 class="mb-3 text-center">Registrarse</h5>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label>Apellido</label>
            <input type="text" name="apellido" value="{{ old('apellido') }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label>Correo</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label>Nombre de usuario</label>
            <input type="text" name="nombreusuario" value="{{ old('nombreusuario') }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label>Teléfono (opcional)</label>
            <input type="text" name="telefono" value="{{ old('telefono') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" required class="form-control">
        </div>

        <div class="mb-3">
            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation" required class="form-control">
        </div>

        <button type="submit" class="btn btn-success btn-block">Crear cuenta</button>

        <p class="mt-3 text-center">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}">Iniciar sesión</a>
        </p>
    </form>
</div>
@endsection