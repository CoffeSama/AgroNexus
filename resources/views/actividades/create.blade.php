@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Crear Actividad</h3>
    </div>

    <form action="{{ route('actividades.store') }}" method="POST">
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
                <label>Usuario Responsable</label>
                <select name="usuarioid" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}">{{ $u->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <input type="text" name="descripcion" class="form-control" required maxlength="200">
            </div>

            <div class="form-group">
                <label>Fecha Inicio</label>
                <input type="datetime-local" name="fechainicio" class="form-control">
            </div>

            <div class="form-group">
                <label>Fecha Fin</label>
                <input type="datetime-local" name="fechafin" class="form-control">
            </div>

            <div class="form-group">
                <label>Tipo de Actividad</label>
                <select name="tipoactividadid" class="form-control" required>
                    @foreach($tipos as $t)
                        <option value="{{ $t->tipoactividadid }}">{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Prioridad</label>
                <select name="prioridadid" class="form-control" required>
                    @foreach($prioridades as $p)
                        <option value="{{ $p->prioridadid }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" maxlength="250"></textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('actividades.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
@endsection