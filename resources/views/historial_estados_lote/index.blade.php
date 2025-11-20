@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Historial de Estados de Lote</h3>
        <a href="{{ route('historial-estados-lote.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Registro
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lote</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Fecha cambio</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historial as $h)
                    <tr>
                        <td>{{ $h->historial_estado_id }}</td>
                        <td>{{ $h->lote->nombre ?? '-' }}</td>
                        <td>{{ $h->estadoTipo->nombre ?? '-' }}</td>
                        <td>{{ $h->usuario->nombre ?? '-' }}</td>
                        <td>{{ $h->fecha_cambio }}</td>

                        <td>
                            <a href="{{ route('historial-estados-lote.show', $h) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('historial-estados-lote.edit', $h) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('historial-estados-lote.destroy', $h) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar registro de historial?')">
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
                        <td colspan="6" class="text-center py-3">
                            No hay registros de historial.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $historial->links() }}
    </div>

</div>
@endsection