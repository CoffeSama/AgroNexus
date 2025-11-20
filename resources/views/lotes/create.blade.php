@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Crear Lote</h3>
    </div>

    <form action="{{ route('lotes.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Usuario propietario</label>
                <select name="usuarioid" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}">{{ $u->nombre }} {{ $u->apellido }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nombre del lote</label>
                <input type="text" name="nombre" class="form-control" maxlength="100" required>
            </div>

            <div class="form-group">
                <label>Ubicación (texto)</label>
                <input type="text" name="ubicacion" class="form-control" maxlength="200">
            </div>

            <div class="form-group">
                <label>Superficie (ha)</label>
                <input type="number" step="0.01" name="superficie" class="form-control" min="0" required>
            </div>

            <div class="form-group">
                <label>Cultivo</label>
                <select name="cultivoid" class="form-control">
                    <option value="">-- Sin cultivo --</option>
                    @foreach($cultivos as $c)
                        <option value="{{ $c->cultivoid }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Fecha de siembra</label>
                <input type="date" name="fechasiembra" class="form-control">
            </div>

            <div class="form-group">
                <label>Estado del lote</label>
                <select name="estadolotetipoid" class="form-control">
                    <option value="">-- Sin estado inicial --</option>
                    @foreach($estados as $e)
                        <option value="{{ $e->estadolotetipoid }}">{{ $e->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Latitud</label>
                    <input type="number" step="0.0000001" name="latitud"
                           class="form-control" min="-90" max="90">
                </div>
                <div class="form-group col-md-6">
                    <label>Longitud</label>
                    <input type="number" step="0.0000001" name="longitud"
                           class="form-control" min="-180" max="180">
                </div>
            </div>

            <div class="form-group">
                <label>URL de imagen (opcional)</label>
                <input type="text" name="imagenurl" class="form-control" maxlength="250">
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('lotes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>

    </form>
</div>
@endsection