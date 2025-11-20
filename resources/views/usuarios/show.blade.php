@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detalles del Usuario</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $usuario->usuarioid }}</p>
        <p><strong>Nombre:</strong> {{ $usuario->nombre }} {{ $usuario->apellido }}</p>
        <p><strong>Email:</strong> {{ $usuario->email }}</p>
        <p><strong>Usuario:</strong> {{ $usuario->nombreusuario }}</p>
        <p><strong>Teléfono:</strong> {{ $usuario->telefono ?? '-' }}</p>
        <p><strong>Activo:</strong> {{ $usuario->activo ? 'Sí' : 'No' }}</p>

        <p><strong>Información adicional:</strong><br>
        {{ $usuario->informacionadicional ?? '-' }}</p>

        <p><strong>Imagen:</strong></p>
        @if($usuario->imagenurl)
            <img src="{{ $usuario->imagenurl }}" width="200" class="img-thumbnail">
        @else
            <p>No hay imagen.</p>
        @endif

    </div>

    <div class="card-footer">

        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
              class="d-inline"
              onsubmit="return confirm('¿Eliminar usuario?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>

    </div>

</div>
@endsection