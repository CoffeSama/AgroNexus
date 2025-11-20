@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Crear Insumo</h3>
    </div>

    <form action="{{ route('insumos.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Nombre del insumo</label>
                <input type="text" name="nombre" class="form-control" maxlength="100" required>
            </div>

            <div class="form-group">
                <label>Tipo de insumo</label>
                <select name="tipoinsumoid" class="form-control" required>
                    @foreach($tipos as $t)
                        <option value="{{ $t->tipoinsumoid }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Unidad de medida</label>
                <select name="unidadmedidaid" class="form-control" required>
                    @foreach($unidades as $u)
                        <option value="{{ $u->unidadmedidaid }}">{{ $u->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Stock actual</label>
                <input type="number" step="0.01" name="stock" class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label>Stock mínimo</label>
                <input type="number" step="0.01" name="stockminimo" class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label>Proveedor (opcional)</label>
                <input type="text" name="proveedor" class="form-control" maxlength="100">
            </div>

            <div class="form-group">
                <label>Precio unitario (opcional)</label>
                <input type="number" step="0.01" name="preciounitario" class="form-control" min="0">
            </div>

            <div class="form-group">
                <label>Descripción (opcional)</label>
                <textarea name="descripcion" class="form-control"></textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('insumos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>

    </form>

</div>
@endsection