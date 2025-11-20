@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Insumos</h3>
        <a href="{{ route('insumos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Insumo
        </a>
    </div>

    <div class="card-body p-0">

        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Unidad</th>
                    <th>Stock</th>
                    <th>Stock mínimo</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($insumos as $i)
                    <tr>
                        <td>{{ $i->insumoid }}</td>
                        <td>{{ $i->nombre }}</td>
                        <td>{{ $i->tipo->nombre ?? '-' }}</td>
                        <td>{{ $i->unidadMedida->nombre ?? '-' }}</td>
                        <td>{{ $i->stock }}</td>
                        <td>{{ $i->stockminimo }}</td>

                        <td>
                            <a href="{{ route('insumos.show', $i) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('insumos.edit', $i) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('insumos.destroy', $i) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar insumo?')">
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
                        <td colspan="7" class="text-center py-3">No hay insumos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="card-footer">
        {{ $insumos->links() }}
    </div>

</div>
@endsection