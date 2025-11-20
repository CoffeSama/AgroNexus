@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Tipos de Estado de Lote</h3>
        <a href="{{ route('estado-lote-tipos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tipos as $t)
                <tr>
                    <td>{{ $t->estadolotetipoid }}</td>
                    <td>{{ $t->nombre }}</td>
                    <td>{{ $t->descripcion }}</td>
                    <td>
                        <a href="{{ route('estado-lote-tipos.show', $t) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="{{ route('estado-lote-tipos.edit', $t) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('estado-lote-tipos.destroy', $t) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar tipo de estado?')">
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
                    <td colspan="4" class="text-center py-3">No hay tipos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $tipos->links() }}
    </div>

</div>
@endsection