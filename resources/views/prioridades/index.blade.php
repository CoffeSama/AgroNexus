@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Prioridades</h3>
        <a href="{{ route('prioridades.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Prioridad
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
                @forelse($prioridades as $p)
                    <tr>
                        <td>{{ $p->prioridadid }}</td>
                        <td>{{ $p->nombre }}</td>

                        <td>
                            <a href="{{ route('prioridades.show', $p) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('prioridades.edit', $p) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('prioridades.destroy', $p) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar prioridad?')">
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
                        <td colspan="3" class="text-center py-3">No hay prioridades registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $prioridades->links() }}
    </div>

</div>
@endsection