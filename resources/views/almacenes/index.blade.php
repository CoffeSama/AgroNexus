@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Almacenes</h3>
        <a href="{{ route('almacenes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Almacén
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Capacidad</th>
                    <th>Unidad</th>
                    <th>Activo</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($almacenes as $a)
                    <tr>
                        <td>{{ $a->almacenid }}</td>
                        <td>{{ $a->nombre }}</td>
                        <td>{{ $a->tipoAlmacen->nombre ?? '-' }}</td>
                        <td>{{ $a->capacidad }}</td>
                        <td>{{ $a->unidadMedida->nombre ?? '-' }}</td>
                        <td>
                            @if($a->activo)
                                <span class="badge badge-success">Sí</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('almacenes.show', $a) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('almacenes.edit', $a) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('almacenes.destroy', $a) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar almacén?')">
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
                        <td colspan="7" class="text-center py-3">No hay almacenes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $almacenes->links() }}
    </div>

</div>
@endsection