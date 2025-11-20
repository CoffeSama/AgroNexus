@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Registrar Usuario</h3>
    </div>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required maxlength="50">
                </div>
                <div class="form-group col-md-6">
                    <label>Apellido</label>
                    <input type="text" name="apellido" class="form-control" required maxlength="50">
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required maxlength="80">
            </div>

            <div class="form-group">
                <label>Nombre de usuario</label>
                <input type="text" name="nombreusuario" class="form-control" required maxlength="30">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" maxlength="20">
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="passwordhash" class="form-control" required minlength="6">
            </div>

            <div class="form-group">
                <label>URL de imagen (opcional)</label>
                <input type="text" name="imagenurl" class="form-control" maxlength="250">
            </div>

            <div class="form-group">
                <label>Información adicional</label>
                <textarea name="informacionadicional" class="form-control" maxlength="300"></textarea>
            </div>

            <div class="form-group">
                <label>Activo</label>
                <select name="activo" class="form-control">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>

    </form>
</div>
@endsection