@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Detalles de Almacenamiento de Producción</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $registro->produccionalmacenamientoid }}</p>

        <p><strong>Producción:</strong>
            #{{ $registro->produccionid }}
            @if($registro->produccion && $registro->produccion->lote)
                (Lote: {{ $registro->produccion->lote->nombre }})
            @endif
        </p>

        <p><strong>Almacén:</strong> {{ $registro->almacen->nombre ?? '-' }}</p>

        <p><strong>Cantidad:</strong>
            {{ $registro->cantidad }}
            {{ $registro->unidadMedida->nombre ?? '' }}
        </p>

        <p><strong>Temperatura actual (°C):</strong> {{ $registro->temperatura }}</p>
        <p><strong>Humedad actual (%):</strong> {{ $registro->humedad }}</p>

        <p><strong>Rango temperatura (°C):</strong>
            {{ $registro->temperatura_min }} - {{ $registro->temperatura_max }}
        </p>
        <p><strong>Rango humedad (%):</strong>
            {{ $registro->humedad_min }} - {{ $registro->humedad_max }}
        </p>

        <p><strong>Fecha de entrada:</strong> {{ $registro->fechaentrada }}</p>
        <p><strong>Fecha de salida:</strong> {{ $registro->fechasalida ?? '-' }}</p>

        <p><strong>Observaciones:</strong> {{ $registro->observaciones }}</p>

    </div>

    <div class="card-footer">
        <a href="{{ route('producciones_almacenamiento.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('producciones_almacenamiento.edit', $registro) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('producciones_almacenamiento.destroy', $registro) }}"
              method="POST" class="d-inline"
              onsubmit="return confirm('¿Eliminar registro de almacenamiento?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>
    </div>

</div>
@endsection