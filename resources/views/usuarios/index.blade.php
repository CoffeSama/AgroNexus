@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- MENSAJES --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- TABLA DE USUARIOS --}}
        {{-- ========================================================= --}}
        <div class="card mb-5">
            <div class="card-header d-flex justify-content-between">
                <h4>Usuarios</h4>
                <a href="{{ route('gestion.index') }}#userForm" class="btn btn-primary">
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
                            <th>Rol (Spatie)</th>
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
                                    {{-- Spatie usa 'roles' relationship --}}
                                    @if($usuario->roles->isNotEmpty())
                                        @foreach($usuario->roles as $role)
                                            <span class="badge badge-info">{{ $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-secondary">Sin rol</span>
                                    @endif
                                </td>

                                <td>{{ $usuario->activo ? 'Sí' : 'No' }}</td>

                                <td>

                                    {{-- BOTÓN EDITAR --}}
                                    <a href="{{ url('gestion-usuarios?editarUsuario=' . $usuario->usuarioid) }}"
                                        class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    {{-- BOTÓN ELIMINAR --}}
                                    <form action="{{ route('gestion.usuario.destroy', $usuario) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar usuario?')">
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
    {{-- FORMULARIO UNIFICADO CREAR / EDITAR USUARIO --}}
    {{-- ========================================================= --}}
    <div class="card mt-5 mb-5" id="userForm">
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
                               value="{{ $editarUsuario->nombre ?? old('nombre') }}"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label>Apellido</label>
                        <input class="form-control"
                               name="apellido"
                               value="{{ $editarUsuario->apellido ?? old('apellido') }}"
                               required>
                    </div>
                </div>

                <label class="mt-2">Email</label>
                <input class="form-control"
                       type="email"
                       name="email"
                       value="{{ $editarUsuario->email ?? old('email') }}"
                       required>

                <label class="mt-2">Nombre de usuario</label>
                <input class="form-control"
                       name="nombreusuario"
                       value="{{ $editarUsuario->nombreusuario ?? old('nombreusuario') }}"
                       required>

                <label class="mt-2">Teléfono</label>
                <input class="form-control"
                       name="telefono"
                       value="{{ $editarUsuario->telefono ?? old('telefono') }}">

                <label class="mt-2">
                    Contraseña
                    @if($editarUsuario)
                        <small>(déjalo vacío si no deseas cambiarla)</small>
                    @endif
                </label>
                <input class="form-control"
                       type="password"
                       name="passwordhash">

                <label class="mt-2">Rol (Asignación)</label>
                <select name="rolid" class="form-control">
                    <option value="">Sin Rol</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}"
                            @if(old('rolid') == $rol->id) selected @endif
                            @if($editarUsuario && $editarUsuario->roles->contains('id', $rol->id))
                                selected
                            @endif>
                            {{ $rol->name }}
                        </option>
                    @endforeach
                </select>

                <label class="mt-2">Activo</label>
                <select name="activo" class="form-control">
                    <option value="1" @if(old('activo', $editarUsuario->activo ?? 1) == 1) selected @endif>Si</option>
                    <option value="0" @if(old('activo', $editarUsuario->activo ?? 1) == 0) selected @endif>No</option>
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

    </div>
@endsection