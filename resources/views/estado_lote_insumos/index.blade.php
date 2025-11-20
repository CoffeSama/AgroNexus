@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Estados de Insumo en Lote</h3>
        <a href="{{ route('estado-lote-insumos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Estado
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($estados as $e)
                    <tr>
                        <td>{{ $e->estadoloteinsumoid }}</td>
                        <td>{{ $e->nombre }}</td>
                        <td>
                            <a href="{{ route('estado-lote-insumos.show', $e) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('estado-lote-insumos.edit', $e) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('estado-lote-insumos.destroy', $e) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar estado de insumo?')">
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
                        <td colspan="3" class="text-center py-3">
                            No hay estados de insumo registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $estados->links() }}
    </div>

</div>
@endsection