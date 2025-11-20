@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Registrar Venta</h3>
    </div>

    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Producción</label>
                <select name="produccionid" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($producciones as $p)
                        <option value="{{ $p->produccionid }}">
                            Prod #{{ $p->produccionid }}
                            @if($p->lote ?? false)
                                - {{ $p->lote->nombre }}
                            @endif
                            @if(!is_null($p->cantidadkg))
                                ({{ $p->cantidadkg }} kg)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cliente</label>
                <input type="text"
                       name="cliente"
                       class="form-control"
                       maxlength="100">
            </div>

            <div class="form-group">
                <label>Cantidad vendida (kg)</label>
                <input type="number"
                       step="0.01"
                       name="cantidadkg"
                       class="form-control"
                       min="0">
            </div>

            <div class="form-group">
                <label>Precio por kg</label>
                <input type="number"
                       step="0.01"
                       name="preciokg"
                       class="form-control"
                       min="0">
            </div>

            <div class="form-group">
                <label>Fecha de venta</label>
                <input type="date"
                       name="fechaventa"
                       class="form-control">
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones"
                          class="form-control"
                          maxlength="200"></textarea>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>

    </form>
</div>
@endsection