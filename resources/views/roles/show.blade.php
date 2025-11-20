@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Detalles del Rol</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $rol->rolid }}</p>
        <p><strong>Nombre:</strong> {{ $rol->nombre }}</p>
        <p><strong>Descripción:</strong> {{ $rol->descripcion ?? '-' }}</p>

    </div>

    <div class="card-footer">

        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver</a>

        <a href="{{ route('roles.edit', $rol) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('roles.destroy', $rol) }}" method="POST" 
              class="d-inline"
              onsubmit="return confirm('¿Eliminar rol?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>

    </div>

</div>
@endsection