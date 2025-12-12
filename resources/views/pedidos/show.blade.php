@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalle del Pedido #{{ $pedido->pedidoid }}</h3>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Planta</dt>
                <dd class="col-sm-9">{{ $pedido->nombre_planta }}</dd>

                <dt class="col-sm-3">Cultivo</dt>
                <dd class="col-sm-9">
                    {{ $pedido->cultivo->nombre ?? $pedido->cultivo_personalizado }}
                </dd>

                <dt class="col-sm-3">Cantidad</dt>
                <dd class="col-sm-9">
                    {{ $pedido->cantidad }} {{ $pedido->unidadMedida->nombre }}
                </dd>

                <dt class="col-sm-3">Ubicación</dt>
                <dd class="col-sm-9">
                    Lat: {{ $pedido->latitud }} <br>
                    Lng: {{ $pedido->longitud }} <br>
                    {{ $pedido->direccion_texto }}
                </dd>

                <dt class="col-sm-3">Fecha Pedido</dt>
                <dd class="col-sm-9">{{ $pedido->fechapedido }}</dd>

                <dt class="col-sm-3">Fecha Entrega Deseada</dt>
                <dd class="col-sm-9">{{ $pedido->fechaEntregaDeseada }}</dd>

                <dt class="col-sm-3">Observaciones</dt>
                <dd class="col-sm-9">{{ $pedido->observaciones }}</dd>
            </dl>

            <hr>

            <form action="{{ route('pedidos.update', $pedido) }}" method="POST" class="w-25">
                @csrf
                @method('PUT')

                <label>Estado</label>
                <select name="estado" class="form-control">
                    @foreach(['pendiente','confirmado','en produccion','rechazado'] as $estado)
                        <option value="{{ $estado }}"
                            {{ $pedido->estado === $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>

                <button class="btn btn-primary mt-3">
                    Actualizar estado
                </button>
            </form>
        </div>
    </div>
</div>
@endsection