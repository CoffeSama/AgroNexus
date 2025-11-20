@extends('layouts.auth')

@section('title', 'Iniciar sesión | AgroNexus')
@section('card_title', 'Iniciar sesión')

@section('content')
<div class="container">
    <h5 class="mb-3 text-center">Acceso al panel</h5>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-3">
            <label for="email">Correo</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control">
        </div>

        <div class="mb-3">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required class="form-control">
        </div>

        <button type="submit" class="btn btn-success btn-block">Entrar</button>

        <p class="mt-3 text-center">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}">Registrarme</a>
        </p>
    </form>
</div>
@endsection