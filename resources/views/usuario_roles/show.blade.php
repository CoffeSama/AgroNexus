@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Detalle de Asignación de Rol</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $usuarioRole->usuariorolid }}</p>

        <p><strong>Usuario:</strong>
            {{ $usuarioRole->usuario->nombre ?? '' }}
            {{ $usuarioRole->usuario->apellido ?? '' }}
            ({{ $usuarioRole->usuario->nombreusuario ?? '-' }})
        </p>

        <p><strong>Rol:</strong> {{ $usuarioRole->rol->nombre ?? '-' }}</p>

    </div>

    <div class="card-footer">

        <a href="{{ route('usuario-roles.index') }}" class="btn btn-secondary">Volver</a>

        <a href="{{ route('usuario-roles.edit', $usuarioRole) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('usuario-roles.destroy', $usuarioRole) }}" method="POST"
              class="d-inline"
              onsubmit="return confirm('¿Eliminar esta asignación?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>

    </div>

</div>
@endsection