@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Detalles del Lote</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $lote->loteid }}</p>
        <p><strong>Nombre:</strong> {{ $lote->nombre }}</p>

        <p><strong>Usuario:</strong>
            {{ $lote->usuario->nombre ?? '-' }} {{ $lote->usuario->apellido ?? '' }}
        </p>

        <p><strong>Cultivo:</strong> {{ $lote->cultivo->nombre ?? '-' }}</p>
        <p><strong>Estado:</strong> {{ $lote->estadoTipo->nombre ?? '-' }}</p>

        <p><strong>Superficie:</strong> {{ $lote->superficie }} ha</p>
        <p><strong>Ubicación:</strong> {{ $lote->ubicacion }}</p>

        <p><strong>Fecha de siembra:</strong> {{ $lote->fechasiembra }}</p>

        <p><strong>Latitud:</strong> {{ $lote->latitud }}</p>
        <p><strong>Longitud:</strong> {{ $lote->longitud }}</p>

        <p><strong>Imagen:</strong></p>
        @if($lote->imagenurl)
            <img src="{{ $lote->imagenurl }}" alt="Imagen del lote" class="img-thumbnail" width="250">
        @else
            <p>No hay imagen asociada.</p>
        @endif

    </div>

    <div class="card-footer">
        <a href="{{ route('lotes.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('lotes.edit', $lote) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('lotes.destroy', $lote) }}" method="POST"
              class="d-inline" onsubmit="return confirm('¿Eliminar lote?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>
    </div>

</div>
@endsection