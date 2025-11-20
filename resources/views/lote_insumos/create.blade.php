@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Registrar Aplicación de Insumo</h3>
    </div>

    <form action="{{ route('lote-insumos.store') }}" method="POST">
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
                <label>Insumo</label>
                <select name="insumoid" class="form-control" required>
                    @foreach($insumos as $i)
                        <option value="{{ $i->insumoid }}">{{ $i->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Usuario responsable</label>
                <select name="usuarioid" class="form-control" required>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}">{{ $u->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cantidad usada</label>
                <input type="number" step="0.01" name="cantidadusada"
                       class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label>Fecha de uso</label>
                <input type="datetime-local" name="fechauo" class="form-control">
            </div>

            <div class="form-group">
                <label>Costo total (opcional)</label>
                <input type="number" step="0.01" name="costototal"
                       class="form-control" min="0">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estadoloteinsumoid" class="form-control">
                    <option value="">-- Sin estado --</option>
                    @foreach($estados as $e)
                        <option value="{{ $e->estadoloteinsumoid }}">{{ $e->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" maxlength="200"></textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('lote-insumos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>

    </form>

</div>
@endsection