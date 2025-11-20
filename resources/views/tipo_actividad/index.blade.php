@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Tipos de Actividad</h3>
        <a href="{{ route('tipo-actividad.create') }}" class="btn btn-primary">
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
                @forelse($tipos as $t)
                    <tr>
                        <td>{{ $t->tipoactividadid }}</td>
                        <td>{{ $t->nombre }}</td>
                        <td>{{ $t->descripcion }}</td>

                        <td>
                            <a href="{{ route('tipo-actividad.show', $t) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('tipo-actividad.edit', $t) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('tipo-actividad.destroy', $t) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar tipo de actividad?')">
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
                        <td colspan="4" class="text-center py-3">No hay tipos de actividad registrados.</td>
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