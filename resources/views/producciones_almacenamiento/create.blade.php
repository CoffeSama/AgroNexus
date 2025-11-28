@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Registrar Almacenamiento de Producción</h3>
    </div>

    <form action="{{ route('producciones_almacenamiento.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Producción</label>
                <select name="produccionid" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($producciones as $p)
                        <option value="{{ $p->produccionid }}">
                            #{{ $p->produccionid }}
                            @if($p->lote)
                                - Lote: {{ $p->lote->nombre }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Almacén</label>
                <select name="almacenid" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($almacenes as $a)
                        <option value="{{ $a->almacenid }}">{{ $a->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" step="0.01" min="0.01" name="cantidad" class="form-control" required>
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

            <hr>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Temperatura actual (°C)</label>
                    <input type="number" step="0.01" name="temperatura" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Humedad actual (%)</label>
                    <input type="number" step="0.01" name="humedad" class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Temperatura mínima recomendada (°C)</label>
                    <input type="number" step="0.01" name="temperatura_min" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Temperatura máxima recomendada (°C)</label>
                    <input type="number" step="0.01" name="temperatura_max" class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Humedad mínima recomendada (%)</label>
                    <input type="number" step="0.01" name="humedad_min" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Humedad máxima recomendada (%)</label>
                    <input type="number" step="0.01" name="humedad_max" class="form-control">
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label>Fecha de entrada</label>
                <input type="datetime-local" name="fechaentrada" class="form-control">
            </div>

            <div class="form-group">
                <label>Fecha de salida (opcional)</label>
                <input type="datetime-local" name="fechasalida" class="form-control">
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" maxlength="250"></textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('producciones_almacenamiento.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>

    </form>
</div>
@endsection