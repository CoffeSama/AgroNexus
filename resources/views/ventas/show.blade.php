@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Detalle de la Venta</h3>
    </div>

    <div class="card-body">

        <p><strong>ID:</strong> {{ $venta->ventaid }}</p>

        <p><strong>Producción:</strong>
            @if($venta->produccion)
                Prod #{{ $venta->produccion->produccionid }}
                @if($venta->produccion->lote ?? false)
                    - {{ $venta->produccion->lote->nombre }}
                @endif
            @else
                -
            @endif
        </p>

        <p><strong>Cliente:</strong> {{ $venta->cliente ?? '-' }}</p>

        <p><strong>Cantidad (kg):</strong> {{ $venta->cantidadkg }}</p>

        <p><strong>Precio por kg:</strong> {{ $venta->preciokg }}</p>

        <p><strong>Total:</strong>
            @if(!is_null($venta->cantidadkg) && !is_null($venta->preciokg))
                {{ $venta->cantidadkg * $venta->preciokg }}
            @else
                -
            @endif
        </p>

        <p><strong>Fecha de venta:</strong> {{ $venta->fechaventa }}</p>

        <p><strong>Observaciones:</strong> {{ $venta->observaciones ?? '-' }}</p>

    </div>

    <div class="card-footer">
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('ventas.destroy', $venta) }}" method="POST"
              class="d-inline"
              onsubmit="return confirm('¿Eliminar venta?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>
    </div>

</div>
@endsection