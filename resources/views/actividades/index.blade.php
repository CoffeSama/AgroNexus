@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Listado de Actividades</h3>
        <a href="{{ route('actividades.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Actividad
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lote</th>
                    <th>Usuario</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Prioridad</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th style="width: 130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($actividades as $act)
                    <tr>
                        <td>{{ $act->actividadid }}</td>
                        <td>{{ $act->lote->nombre ?? '-' }}</td>
                        <td>{{ $act->usuario->nombre ?? '-' }}</td>
                        <td>{{ $act->descripcion }}</td>
                        <td>{{ $act->tipoActividad->nombre ?? '-' }}</td>
                        <td>{{ $act->prioridad->nombre ?? '-' }}</td>
                        <td>{{ $act->fechainicio }}</td>
                        <td>{{ $act->fechafin }}</td>

                        <td>
                            <a href="{{ route('actividades.show', $act) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('actividades.edit', $act) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('actividades.destroy', $act) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar actividad?')">
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
                        <td colspan="9" class="text-center py-3">No hay actividades registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $actividades->links() }}
    </div>
</div>
@endsection