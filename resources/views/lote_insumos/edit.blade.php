@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Editar Aplicación de Insumo</h3>
    </div>

    <form action="{{ route('lote-insumos.update', $loteInsumo) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Lote</label>
                <select name="loteid" class="form-control">
                    @foreach($lotes as $l)
                        <option value="{{ $l->loteid }}"
                            {{ $l->loteid == $loteInsumo->loteid ? 'selected' : '' }}>
                            {{ $l->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Insumo</label>
                <select name="insumoid" class="form-control">
                    @foreach($insumos as $i)
                        <option value="{{ $i->insumoid }}"
                            {{ $i->insumoid == $loteInsumo->insumoid ? 'selected' : '' }}>
                            {{ $i->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Usuario responsable</label>
                <select name="usuarioid" class="form-control">
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}"
                            {{ $u->usuarioid == $loteInsumo->usuarioid ? 'selected' : '' }}>
                            {{ $u->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cantidad usada</label>
                <input type="number" step="0.01" name="cantidadusada"
                       class="form-control"
                       value="{{ $loteInsumo->cantidadusada }}" min="0" required>
            </div>

            <div class="form-group">
                <label>Fecha de uso</label>
                <input type="datetime-local" name="fechauo"
                       class="form-control" value="{{ $loteInsumo->fechauo }}">
            </div>

            <div class="form-group">
                <label>Costo total</label>
                <input type="number" step="0.01" name="costototal"
                       class="form-control"
                       value="{{ $loteInsumo->costototal }}" min="0">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estadoloteinsumoid" class="form-control">
                    <option value="">-- Sin estado --</option>
                    @foreach($estados as $e)
                        <option value="{{ $e->estadoloteinsumoid }}"
                            {{ $e->estadoloteinsumoid == $loteInsumo->estadoloteinsumoid ? 'selected' : '' }}>
                            {{ $e->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" maxlength="200">
                    {{ $loteInsumo->observaciones }}
                </textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('lote-insumos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>

</div>
@endsection