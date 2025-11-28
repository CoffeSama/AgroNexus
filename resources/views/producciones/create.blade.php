@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Registrar Producción</h3>
    </div>

    <form action="{{ route('producciones.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Lote</label>
                <select name="loteid" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($lotes as $l)
                        <option value="{{ $l->loteid }}">{{ $l->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" step="0.01" name="cantidadkg"
                       class="form-control" min="0">
            </div>

            <div class="form-group">
                <label>Unidad de medida</label>
                <select name="unidadmedidaid" class="form-control">
                    <option value="">Seleccione...</option>
                    @foreach($unidades as $u)
                        <option value="{{ $u->unidadmedidaid }}">{{ $u->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Fecha de cosecha</label>
                <input type="date" name="fechacosecha" class="form-control">
            </div>

            <div class="form-group">
                <label>Destino</label>
                <select name="destinoproduccionid" class="form-control">
                    <option value="">-- Sin destino --</option>
                    @foreach($destinos as $d)
                        <option value="{{ $d->destinoproduccionid }}">{{ $d->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>URL de imagen (opcional)</label>
                <input type="text" name="imagenurl" class="form-control" maxlength="250">
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control"></textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('producciones.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>

    </form>
</div>
@endsection