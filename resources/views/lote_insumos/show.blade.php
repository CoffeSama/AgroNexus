@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Detalles de la Aplicación de Insumo</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $loteInsumo->loteinsumoid }}</p>

        <p><strong>Lote:</strong> {{ $loteInsumo->lote->nombre ?? '-' }}</p>
        <p><strong>Insumo:</strong> {{ $loteInsumo->insumo->nombre ?? '-' }}</p>
        <p><strong>Usuario:</strong> {{ $loteInsumo->usuario->nombre ?? '-' }}</p>

        <p><strong>Cantidad:</strong> {{ $loteInsumo->cantidadusada }}</p>

        <p><strong>Fecha uso:</strong> {{ $loteInsumo->fechauo }}</p>

        <p><strong>Costo total:</strong> {{ $loteInsumo->costototal ?? '-' }}</p>

        <p><strong>Estado:</strong> {{ $loteInsumo->estado->nombre ?? '-' }}</p>

        <p><strong>Observaciones:</strong> {{ $loteInsumo->observaciones }}</p>

    </div>

    <div class="card-footer">

        <a href="{{ route('lote-insumos.index') }}" class="btn btn-secondary">Volver</a>

        <a href="{{ route('lote-insumos.edit', $loteInsumo) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('lote-insumos.destroy', $loteInsumo) }}"
              method="POST" class="d-inline"
              onsubmit="return confirm('¿Eliminar registro?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>

    </div>

</div>
@endsection