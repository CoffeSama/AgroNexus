@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Roles</h3>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Rol
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $r)
                    <tr>
                        <td>{{ $r->rolid }}</td>
                        <td>{{ $r->nombre }}</td>
                        <td>{{ $r->descripcion ?? '-' }}</td>

                        <td>
                            <a href="{{ route('roles.show', $r) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('roles.edit', $r) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('roles.destroy', $r) }}" method="POST"
                                  class="d-inline" 
                                  onsubmit="return confirm('¿Eliminar rol?')">
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
                        <td colspan="4" class="text-center py-3">No hay roles registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $roles->links() }}
    </div>

</div>
@endsection