@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Editar Lote</h3>
    </div>

    <form action="{{ route('lotes.update', $lote) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Usuario propietario</label>
                <select name="usuarioid" class="form-control" required>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}"
                            {{ $u->usuarioid == $lote->usuarioid ? 'selected' : '' }}>
                            {{ $u->nombre }} {{ $u->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nombre del lote</label>
                <input type="text" name="nombre" class="form-control"
                       value="{{ $lote->nombre }}" maxlength="100" required>
            </div>

            <div class="form-group">
                <label>Ubicación (texto)</label>
                <input type="text" name="ubicacion" class="form-control"
                       value="{{ $lote->ubicacion }}" maxlength="200">
            </div>

            <div class="form-group">
                <label>Superficie (ha)</label>
                <input type="number" step="0.01" name="superficie"
                       class="form-control" min="0"
                       value="{{ $lote->superficie }}" required>
            </div>

            <div class="form-group">
                <label>Cultivo</label>
                <select name="cultivoid" class="form-control">
                    <option value="">-- Sin cultivo --</option>
                    @foreach($cultivos as $c)
                        <option value="{{ $c->cultivoid }}"
                            {{ $c->cultivoid == $lote->cultivoid ? 'selected' : '' }}>
                            {{ $c->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Fecha de siembra</label>
                <input type="date" name="fechasiembra" class="form-control"
                       value="{{ $lote->fechasiembra }}">
            </div>

            <div class="form-group">
                <label>Estado del lote</label>
                <select name="estadolotetipoid" class="form-control">
                    <option value="">-- Sin estado --</option>
                    @foreach($estados as $e)
                        <option value="{{ $e->estadolotetipoid }}"
                            {{ $e->estadolotetipoid == $lote->estadolotetipoid ? 'selected' : '' }}>
                            {{ $e->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Latitud</label>
                    <input type="number" step="0.0000001" name="latitud"
                           class="form-control"
                           min="-90" max="90"
                           value="{{ $lote->latitud }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Longitud</label>
                    <input type="number" step="0.0000001" name="longitud"
                           class="form-control"
                           min="-180" max="180"
                           value="{{ $lote->longitud }}">
                </div>
            </div>

            <div class="form-group">
                <label>URL de imagen</label>
                <input type="text" name="imagenurl" class="form-control"
                       maxlength="250" value="{{ $lote->imagenurl }}">
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('lotes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
@endsection