@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Actividad</h3>
    </div>

    <form action="{{ route('actividades.update', $actividad) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Lote</label>
                <select name="loteid" class="form-control">
                    @foreach($lotes as $l)
                        <option value="{{ $l->loteid }}" {{ $l->loteid == $actividad->loteid ? 'selected' : '' }}>
                            {{ $l->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Usuario Responsable</label>
                <select name="usuarioid" class="form-control">
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}" {{ $u->usuarioid == $actividad->usuarioid ? 'selected' : '' }}>
                            {{ $u->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <input type="text" name="descripcion" class="form-control"
                       value="{{ $actividad->descripcion }}" maxlength="200">
            </div>

            <div class="form-group">
                <label>Fecha Inicio</label>
                <input type="datetime-local" name="fechainicio" class="form-control"
                       value="{{ $actividad->fechainicio }}">
            </div>

            <div class="form-group">
                <label>Fecha Fin</label>
                <input type="datetime-local" name="fechafin" class="form-control"
                       value="{{ $actividad->fechafin }}">
            </div>

            <div class="form-group">
                <label>Tipo de Actividad</label>
                <select name="tipoactividadid" class="form-control">
                    @foreach($tipos as $t)
                        <option value="{{ $t->tipoactividadid }}"
                            {{ $t->tipoactividadid == $actividad->tipoactividadid ? 'selected' : '' }}>
                            {{ $t->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Prioridad</label>
                <select name="prioridadid" class="form-control">
                    @foreach($prioridades as $p)
                        <option value="{{ $p->prioridadid }}"
                            {{ $p->prioridadid == $actividad->prioridadid ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" maxlength="250">
                    {{ $actividad->observaciones }}
                </textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('actividades.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
@endsection