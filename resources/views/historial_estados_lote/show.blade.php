@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Detalle del Historial</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $registro->historial_estado_id }}</p>
        <p><strong>Lote:</strong> {{ $registro->lote->nombre ?? '-' }}</p>
        <p><strong>Tipo de Estado:</strong> {{ $registro->estadoTipo->nombre ?? '-' }}</p>
        <p><strong>Usuario:</strong> {{ $registro->usuario->nombre ?? '-' }}</p>
        <p><strong>Fecha de cambio:</strong> {{ $registro->fecha_cambio }}</p>
        <p><strong>Observaciones:</strong> {{ $registro->observaciones }}</p>

        <p><strong>Imagen:</strong></p>
        @if($registro->imagenurl)
            <img src="{{ $registro->imagenurl }}" alt="Imagen historial" class="img-thumbnail" width="250">
        @else
            <p>No hay imagen asociada.</p>
        @endif

    </div>

    <div class="card-footer">
        <a href="{{ route('historial-estados-lote.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('historial-estados-lote.edit', $registro) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('historial-estados-lote.destroy', $registro) }}" method="POST"
              class="d-inline"
              onsubmit="return confirm('¿Eliminar este registro de historial?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>
    </div>

</div>
@endsection