@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Usuarios</h3>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>

    <div class="card-body p-0">

        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Usuario</th>
                    <th>Teléfono</th>
                    <th>Activo</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $u)
                    <tr>
                        <td>{{ $u->usuarioid }}</td>
                        <td>{{ $u->nombre }} {{ $u->apellido }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->nombreusuario }}</td>
                        <td>{{ $u->telefono }}</td>
                        <td>
                            @if($u->activo)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('usuarios.show', $u) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('usuarios.destroy', $u) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar usuario?')">
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
                        <td colspan="7" class="text-center py-3">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <div class="card-footer">
        {{ $usuarios->links() }}
    </div>

</div>
@endsection