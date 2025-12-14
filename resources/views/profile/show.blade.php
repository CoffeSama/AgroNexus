@extends('layouts.app')

@section('title', 'Mi Perfil | AgroNexus')
@section('page_title', 'Mi Perfil')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #2c5530;">Inicio</a></li>
    <li class="breadcrumb-item active">Perfil</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <!-- Card de Perfil -->
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ asset('images/user.png') }}"
                            alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ $user->nombre }} {{ $user->apellido }}</h3>
                    <p class="text-muted text-center">{{ '@' . $user->nombreusuario }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Rol</b> <a
                                class="float-right badge badge-success">{{ $user->getRoleNames()->first() ?? 'Sin Rol' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Teléfono</b> <a class="float-right">{{ $user->telefono ?? 'No registrado' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Miembro desde</b> <a
                                class="float-right">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Formulario de Edición -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <h3 class="card-title p-1"><i class="fas fa-user-edit mr-2"></i>Editar Mis Datos</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check mr-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="nombre" class="col-sm-3 col-form-label">Nombre</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="{{ old('nombre', $user->nombre) }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="apellido" class="col-sm-3 col-form-label">Apellido</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="apellido" name="apellido"
                                    value="{{ old('apellido', $user->apellido) }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telefono" class="col-sm-3 col-form-label">Teléfono</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="telefono" name="telefono"
                                    value="{{ old('telefono', $user->telefono) }}">
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3 text-muted">Cambiar Contraseña (Opcional)</h5>

                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label">Nueva Contraseña</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Dejar en blanco para no cambiar">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-sm-3 col-form-label">Confirmar Contraseña</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Repetir nueva contraseña">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i>Guardar
                                    Cambios</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection