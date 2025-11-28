@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Detalles del Almacén</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $almacen->almacenid }}</p>
        <p><strong>Nombre:</strong> {{ $almacen->nombre }}</p>
        <p><strong>Descripción:</strong> {{ $almacen->descripcion }}</p>
        <p><strong>Ubicación:</strong> {{ $almacen->ubicacion }}</p>

        <p><strong>Capacidad:</strong>
            {{ $almacen->capacidad }}
            {{ $almacen->unidadMedida->nombre ?? '' }}
        </p>

        <p><strong>Tipo de almacén:</strong> {{ $almacen->tipoAlmacen->nombre ?? '-' }}</p>

        <p><strong>Activo:</strong>
            @if($almacen->activo)
                <span class="badge badge-success">Sí</span>
            @else
                <span class="badge badge-secondary">No</span>
            @endif
        </p>

    </div>

    <div class="card-footer">
        <a href="{{ route('almacenes.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('almacenes.edit', $almacen) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('almacenes.destroy', $almacen) }}"
              method="POST" class="d-inline"
              onsubmit="return confirm('¿Eliminar almacén?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>
    </div>

</div>
@endsection