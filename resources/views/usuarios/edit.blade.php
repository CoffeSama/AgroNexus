@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Usuario</h3>
    </div>

    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ $usuario->nombre }}" required maxlength="50">
                </div>

                <div class="form-group col-md-6">
                    <label>Apellido</label>
                    <input type="text" name="apellido" class="form-control"
                           value="{{ $usuario->apellido }}" required maxlength="50">
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ $usuario->email }}" required maxlength="80">
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="nombreusuario" class="form-control"
                       value="{{ $usuario->nombreusuario }}" required maxlength="30">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control"
                       value="{{ $usuario->telefono }}" maxlength="20">
            </div>

            <div class="form-group">
                <label>URL de imagen</label>
                <input type="text" name="imagenurl" class="form-control"
                       value="{{ $usuario->imagenurl }}" maxlength="250">
            </div>

            <div class="form-group">
                <label>Información adicional</label>
                <textarea name="informacionadicional" class="form-control"
                          maxlength="300">{{ $usuario->informacionadicional }}</textarea>
            </div>

            <div class="form-group">
                <label>Activo</label>
                <select name="activo" class="form-control">
                    <option value="1" {{ $usuario->activo ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !$usuario->activo ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
@endsection