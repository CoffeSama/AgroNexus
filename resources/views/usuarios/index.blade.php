@extends('layouts.app')

@section('content')

<div class="container">

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    {{-- ========================================================= --}}
    {{-- TABLA DE USUARIOS --}}
    {{-- ========================================================= --}}
    <div class="card mb-5">
        <div class="card-header d-flex justify-content-between">
            <h4>Usuarios</h4>
            <a href="{{ route('gestion.index') }}" class="btn btn-primary">
                Crear Nuevo Usuario
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre completo</th>
                        <th>Email</th>
                        <th>Usuario</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Activo</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->usuarioid }}</td>
                        <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->nombreusuario }}</td>
                        <td>{{ $usuario->telefono }}</td>

                        <td>
                            {{ optional($usuario->roles->first())->nombre ?? 'Sin rol' }}
                        </td>

                        <td>{{ $usuario->activo ? 'Sí' : 'No' }}</td>

                        <td>

                            {{-- BOTÓN EDITAR --}}
                            <a href="{{ url('gestion-usuarios?editarUsuario=' . $usuario->usuarioid) }}"
                               class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            {{-- BOTÓN ELIMINAR --}}
                            <form action="{{ route('gestion.usuario.destroy', $usuario) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Eliminar usuario?')">
                                    Eliminar
                                </button>
                            </form>

                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>

            {{ $usuarios->links() }}

        </div>
    </div>



    {{-- ========================================================= --}}
    {{-- TABLA DE ROLES --}}
    {{-- ========================================================= --}}
    <div class="card mb-5">
        <div class="card-header d-flex justify-content-between">
            <h4>Roles</h4>
            <a href="{{ route('gestion.index') }}" class="btn btn-success">
                Crear Nuevo Rol
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($roles as $rol)
                    <tr>
                        <td>{{ $rol->rolid }}</td>
                        <td>{{ $rol->nombre }}</td>
                        <td>{{ $rol->descripcion }}</td>

                        <td>
                            <a href="{{ url('gestion-usuarios?editarRol=' . $rol->rolid) }}"
                               class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <form action="{{ route('gestion.rol.destroy', $rol) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Eliminar rol?')">
                                    Eliminar
                                </button>
                            </form>

                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>

        </div>
    </div>





    {{-- ========================================================= --}}
    {{-- FORMULARIO UNIFICADO CREAR / EDITAR USUARIO --}}
    {{-- ========================================================= --}}
    <div class="card mt-5">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                {{ $editarUsuario ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
            </h4>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ $editarUsuario
                            ? route('gestion.usuario.update', $editarUsuario)
                            : route('gestion.usuario.store') }}">

                @csrf
                @if($editarUsuario)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input class="form-control"
                               name="nombre"
                               value="{{ $editarUsuario->nombre ?? '' }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label>Apellido</label>
                        <input class="form-control"
                               name="apellido"
                               value="{{ $editarUsuario->apellido ?? '' }}"
                               required>
                    </div>
                </div>

                <label class="mt-2">Email</label>
                <input class="form-control"
                       type="email"
                       name="email"
                       value="{{ $editarUsuario->email ?? '' }}"
                       required>

                <label class="mt-2">Nombre de usuario</label>
                <input class="form-control"
                       name="nombreusuario"
                       value="{{ $editarUsuario->nombreusuario ?? '' }}"
                       required>

                <label class="mt-2">Teléfono</label>
                <input class="form-control"
                       name="telefono"
                       value="{{ $editarUsuario->telefono ?? '' }}">

                <label class="mt-2">
                    Contraseña
                    @if($editarUsuario)
                        <small>(déjalo vacío si no deseas cambiarla)</small>
                    @endif
                </label>
                <input class="form-control"
                       type="password"
                       name="passwordhash">

                <label class="mt-2">Rol</label>
                <select name="rolid" class="form-control">
                    <option value="">Sin Rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->rolid }}"
                            @if($editarUsuario && optional($editarUsuario->roles->first())->rolid == $rol->rolid)
                                selected
                            @endif>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>

                <label class="mt-2">Activo</label>
                <select name="activo" class="form-control">
                    <option value="1" @if($editarUsuario && $editarUsuario->activo) selected @endif>Si</option>
                    <option value="0" @if($editarUsuario && !$editarUsuario->activo) selected @endif>No</option>
                </select>

                <button class="btn btn-success mt-3">
                    {{ $editarUsuario ? 'Actualizar Usuario' : 'Crear Usuario' }}
                </button>

                @if($editarUsuario)
                    <a href="{{ route('gestion.index') }}" class="btn btn-secondary mt-3">
                        Cancelar Edición
                    </a>
                @endif

            </form>

        </div>
    </div>




    {{-- ========================================================= --}}
    {{-- FORMULARIO UNIFICADO CREAR / EDITAR ROL --}}
    {{-- ========================================================= --}}
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">
                {{ $editarRol ? 'Editar Rol' : 'Crear Nuevo Rol' }}
            </h4>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ $editarRol
                            ? route('gestion.rol.update', $editarRol)
                            : route('gestion.rol.store') }}">

                @csrf
                @if($editarRol)
                    @method('PUT')
                @endif

                <label>Nombre del Rol</label>
                <input class="form-control"
                       name="nombre"
                       value="{{ $editarRol->nombre ?? '' }}"
                       required>

                <label class="mt-2">Descripción</label>
                <textarea class="form-control" name="descripcion">{{ $editarRol->descripcion ?? '' }}</textarea>

                <button class="btn btn-success mt-3">
                    {{ $editarRol ? 'Actualizar Rol' : 'Crear Rol' }}
                </button>

                @if($editarRol)
                    <a href="{{ route('gestion.index') }}" class="btn btn-secondary mt-3">
                        Cancelar Edición
                    </a>
                @endif

            </form>

        </div>
    </div>
</div>

@endsection