@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Lotes</h3>
        <a href="{{ route('lotes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Lote
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Cultivo</th>
                    <th>Estado</th>
                    <th>Superficie</th>
                    <th>Ubicación</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lotes as $l)
                    <tr>
                        <td>{{ $l->loteid }}</td>
                        <td>{{ $l->nombre }}</td>
                        <td>{{ $l->usuario->nombre ?? '-' }}</td>
                        <td>{{ $l->cultivo->nombre ?? '-' }}</td>
                        <td>{{ $l->estadoTipo->nombre ?? '-' }}</td>
                        <td>{{ $l->superficie }} ha</td>
                        <td>{{ $l->ubicacion }}</td>

                        <td>
                            <a href="{{ route('lotes.show', $l) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('lotes.edit', $l) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('lotes.destroy', $l) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar lote?')">
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
                        <td colspan="8" class="text-center py-3">No hay lotes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $lotes->links() }}
    </div>

</div>
@endsection