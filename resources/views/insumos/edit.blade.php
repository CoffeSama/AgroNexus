@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Editar Insumo</h3>
    </div>

    <form action="{{ route('insumos.update', $insumo) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre"
                       class="form-control"
                       value="{{ $insumo->nombre }}" maxlength="100" required>
            </div>

            <div class="form-group">
                <label>Tipo de insumo</label>
                <select name="tipoinsumoid" class="form-control" required>
                    @foreach($tipos as $t)
                        <option value="{{ $t->tipoinsumoid }}"
                            {{ $t->tipoinsumoid == $insumo->tipoinsumoid ? 'selected' : '' }}>
                            {{ $t->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Unidad de medida</label>
                <select name="unidadmedidaid" class="form-control" required>
                    @foreach($unidades as $u)
                        <option value="{{ $u->unidadmedidaid }}"
                            {{ $u->unidadmedidaid == $insumo->unidadmedidaid ? 'selected' : '' }}>
                            {{ $u->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Stock actual</label>
                <input type="number" step="0.01" name="stock"
                       class="form-control" min="0"
                       value="{{ $insumo->stock }}" required>
            </div>

            <div class="form-group">
                <label>Stock mínimo</label>
                <input type="number" step="0.01" name="stockminimo"
                       class="form-control" min="0"
                       value="{{ $insumo->stockminimo }}" required>
            </div>

            <div class="form-group">
                <label>Proveedor</label>
                <input type="text" name="proveedor"
                       class="form-control"
                       value="{{ $insumo->proveedor }}" maxlength="100">
            </div>

            <div class="form-group">
                <label>Precio unitario</label>
                <input type="number" step="0.01" name="preciounitario"
                       class="form-control" min="0"
                       value="{{ $insumo->preciounitario }}">
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control">{{ $insumo->descripcion }}</textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('insumos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>

</div>
@endsection