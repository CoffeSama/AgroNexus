@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Ventas</h3>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Venta
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producción</th>
                    <th>Cliente</th>
                    <th>Cantidad (kg)</th>
                    <th>Precio/kg</th>
                    <th>Total</th>
                    <th>Fecha venta</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $v)
                    <tr>
                        <td>{{ $v->ventaid }}</td>
                        <td>
                            {{-- mostramos algo de la producción si existe --}}
                            @if($v->produccion)
                                Prod #{{ $v->produccion->produccionid }}
                                @if($v->produccion->lote ?? false)
                                    - {{ $v->produccion->lote->nombre }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $v->cliente ?? '-' }}</td>
                        <td>{{ $v->cantidadkg }}</td>
                        <td>{{ $v->preciokg }}</td>
                        <td>
                            @if(!is_null($v->cantidadkg) && !is_null($v->preciokg))
                                {{ $v->cantidadkg * $v->preciokg }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $v->fechaventa }}</td>

                        <td>
                            <a href="{{ route('ventas.show', $v) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('ventas.edit', $v) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('ventas.destroy', $v) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar venta?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-3">No hay ventas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $ventas->links() }}
    </div>

</div>
@endsection