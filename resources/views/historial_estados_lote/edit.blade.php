@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Editar Registro de Historial</h3>
    </div>

    <form action="{{ route('historial-estados-lote.update', $registro) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Lote</label>
                <select name="loteid" class="form-control" required>
                    @foreach($lotes as $l)
                        <option value="{{ $l->loteid }}"
                            {{ $l->loteid == $registro->loteid ? 'selected' : '' }}>
                            {{ $l->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tipo de Estado</label>
                <select name="estadolotetipoid" class="form-control" required>
                    @foreach($tiposEstado as $t)
                        <option value="{{ $t->estadolotetipoid }}"
                            {{ $t->estadolotetipoid == $registro->estadolotetipoid ? 'selected' : '' }}>
                            {{ $t->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Usuario (opcional)</label>
                <select name="usuarioid" class="form-control">
                    <option value="">-- Sin usuario --</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}"
                            {{ $u->usuarioid == $registro->usuarioid ? 'selected' : '' }}>
                            {{ $u->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Fecha de cambio</label>
                <input type="datetime-local" name="fecha_cambio" class="form-control"
                       value="{{ $registro->fecha_cambio }}">
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control">{{ $registro->observaciones }}</textarea>
            </div>

            <div class="form-group">
                <label>URL de imagen (opcional)</label>
                <input type="text" name="imagenurl" class="form-control" maxlength="250"
                       value="{{ $registro->imagenurl }}">
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('historial-estados-lote.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
@endsection