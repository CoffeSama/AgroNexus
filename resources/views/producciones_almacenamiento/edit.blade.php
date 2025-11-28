@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Editar Almacenamiento de Producción</h3>
    </div>

    <form action="{{ route('producciones_almacenamiento.update', $registro) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Producción</label>
                <select name="produccionid" class="form-control" required>
                    @foreach($producciones as $p)
                        <option value="{{ $p->produccionid }}"
                            {{ $registro->produccionid == $p->produccionid ? 'selected' : '' }}>
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
                    @foreach($almacenes as $a)
                        <option value="{{ $a->almacenid }}"
                            {{ $registro->almacenid == $a->almacenid ? 'selected' : '' }}>
                            {{ $a->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" step="0.01" min="0.01" name="cantidad"
                       class="form-control" value="{{ $registro->cantidad }}" required>
            </div>

            <div class="form-group">
                <label>Unidad de medida</label>
                <select name="unidadmedidaid" class="form-control">
                    <option value="">Seleccione...</option>
                    @foreach($unidades as $u)
                        <option value="{{ $u->unidadmedidaid }}"
                            {{ $registro->unidadmedidaid == $u->unidadmedidaid ? 'selected' : '' }}>
                            {{ $u->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Temperatura actual (°C)</label>
                    <input type="number" step="0.01" name="temperatura"
                           class="form-control" value="{{ $registro->temperatura }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Humedad actual (%)</label>
                    <input type="number" step="0.01" name="humedad"
                           class="form-control" value="{{ $registro->humedad }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Temperatura mínima recomendada (°C)</label>
                    <input type="number" step="0.01" name="temperatura_min"
                           class="form-control" value="{{ $registro->temperatura_min }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Temperatura máxima recomendada (°C)</label>
                    <input type="number" step="0.01" name="temperatura_max"
                           class="form-control" value="{{ $registro->temperatura_max }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Humedad mínima recomendada (%)</label>
                    <input type="number" step="0.01" name="humedad_min"
                           class="form-control" value="{{ $registro->humedad_min }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Humedad máxima recomendada (%)</label>
                    <input type="number" step="0.01" name="humedad_max"
                           class="form-control" value="{{ $registro->humedad_max }}">
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label>Fecha de entrada</label>
                <input type="datetime-local" name="fechaentrada" class="form-control"
                       value="{{ $registro->fechaentrada ? \Carbon\Carbon::parse($registro->fechaentrada)->format('Y-m-d\TH:i') : '' }}">
            </div>

            <div class="form-group">
                <label>Fecha de salida</label>
                <input type="datetime-local" name="fechasalida" class="form-control"
                       value="{{ $registro->fechasalida ? \Carbon\Carbon::parse($registro->fechasalida)->format('Y-m-d\TH:i') : '' }}">
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" maxlength="250">{{ $registro->observaciones }}</textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('producciones_almacenamiento.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
@endsection