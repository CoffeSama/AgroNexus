@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detalles de la Producción</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $produccion->produccionid }}</p>

        <p><strong>Lote:</strong> {{ $produccion->lote->nombre ?? '-' }}</p>
        <p><strong>Cantidad (kg):</strong> {{ $produccion->cantidadkg }}</p>
        <p><strong>Fecha de cosecha:</strong> {{ $produccion->fechacosecha }}</p>
        <p><strong>Destino:</strong> {{ $produccion->destino->nombre ?? '-' }}</p>
        <p><strong>Observaciones:</strong> {{ $produccion->observaciones }}</p>

        <p><strong>Imagen:</strong></p>
        @if($produccion->imagenurl)
            <img src="{{ $produccion->imagenurl }}" class="img-thumbnail" width="250">
        @else
            <p>No hay imagen.</p>
        @endif

    </div>

    <div class="card-footer">
        <a href="{{ route('producciones.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('producciones.edit', $produccion) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('producciones.destroy', $produccion) }}"
              method="POST" class="d-inline"
              onsubmit="return confirm('¿Eliminar producción?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>
    </div>
</div>
@endsection