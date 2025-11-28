@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Editar Producción</h3>
    </div>

    <form action="{{ route('producciones.update', $produccion) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Lote</label>
                <select name="loteid" class="form-control">
                    @foreach($lotes as $l)
                        <option value="{{ $l->loteid }}"
                            {{ $l->loteid == $produccion->loteid ? 'selected' : '' }}>
                            {{ $l->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" step="0.01" name="cantidadkg"
                       class="form-control" min="0"
                       value="{{ $produccion->cantidadkg }}">
            </div>

            <div class="form-group">
                <label>Unidad de medida</label>
                <select name="unidadmedidaid" class="form-control">
                    <option value="">Seleccione...</option>
                    @foreach($unidades as $u)
                        <option value="{{ $u->unidadmedidaid }}"
                            {{ $produccion->unidadmedidaid == $u->unidadmedidaid ? 'selected' : '' }}>
                            {{ $u->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Fecha de cosecha</label>
                <input type="date" name="fechacosecha" class="form-control"
                       value="{{ $produccion->fechacosecha }}">
            </div>

            <div class="form-group">
                <label>Destino</label>
                <select name="destinoproduccionid" class="form-control">
                    <option value="">-- Sin destino --</option>
                    @foreach($destinos as $d)
                        <option value="{{ $d->destinoproduccionid }}"
                            {{ $d->destinoproduccionid == $produccion->destinoproduccionid ? 'selected' : '' }}>
                            {{ $d->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>URL de imagen</label>
                <input type="text" name="imagenurl" class="form-control"
                       value="{{ $produccion->imagenurl }}" maxlength="250">
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control">{{ $produccion->observaciones }}</textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('producciones.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
@endsection