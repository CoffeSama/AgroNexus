@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Usuarios y Roles</h3>
        <a href="{{ route('usuario-roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Asignación
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarioRoles as $ur)
                    <tr>
                        <td>{{ $ur->usuariorolid }}</td>
                        <td>{{ $ur->usuario->nombre ?? '' }} {{ $ur->usuario->apellido ?? '' }}</td>
                        <td>{{ $ur->rol->nombre ?? '-' }}</td>

                        <td>
                            <a href="{{ route('usuario-roles.show', $ur) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('usuario-roles.edit', $ur) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('usuario-roles.destroy', $ur) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar esta asignación?')">
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
                        <td colspan="4" class="text-center py-3">No hay asignaciones de roles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $usuarioRoles->links() }}
    </div>

</div>
@endsection