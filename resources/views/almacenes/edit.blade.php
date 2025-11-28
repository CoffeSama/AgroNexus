@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Editar Almacén</h3>
    </div>

    <form action="{{ route('almacenes.update', $almacen) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control"
                       maxlength="100" value="{{ $almacen->nombre }}" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <input type="text" name="descripcion" class="form-control"
                       maxlength="250" value="{{ $almacen->descripcion }}">
            </div>

            <div class="form-group">
                <label>Ubicación</label>
                <input type="text" name="ubicacion" class="form-control"
                       maxlength="200" value="{{ $almacen->ubicacion }}">
            </div>

            <div class="form-group">
                <label>Capacidad</label>
                <input type="number" step="0.01" min="0" name="capacidad"
                       class="form-control" value="{{ $almacen->capacidad }}">
            </div>

            <div class="form-group">
                <label>Unidad de medida de capacidad</label>
                <select name="unidadmedidaid" class="form-control">
                    <option value="">Seleccione...</option>
                    @foreach($unidades as $u)
                        <option value="{{ $u->unidadmedidaid }}"
                            {{ $almacen->unidadmedidaid == $u->unidadmedidaid ? 'selected' : '' }}>
                            {{ $u->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tipo de almacén</label>
                <select name="tipoalmacenid" class="form-control">
                    <option value="">Seleccione...</option>
                    @foreach($tipos as $t)
                        <option value="{{ $t->tipoalmacenid }}"
                            {{ $almacen->tipoalmacenid == $t->tipoalmacenid ? 'selected' : '' }}>
                            {{ $t->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" name="activo" value="1"
                       class="form-check-input" id="activoCheck"
                       {{ $almacen->activo ? 'checked' : '' }}>
                <label class="form-check-label" for="activoCheck">Activo</label>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('almacenes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
@endsection