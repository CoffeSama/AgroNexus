@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Pedidos</h3>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Planta</th>
                        <th>Cultivo</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <th>Fecha Pedido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->pedidoid }}</td>
                            <td>{{ $pedido->nombre_planta }}</td>
                            <td>
                                {{ $pedido->cultivo->nombre ?? $pedido->cultivo_personalizado }}
                            </td>
                            <td>
                                {{ $pedido->cantidad }}
                                {{ $pedido->unidadMedida->nombre }}
                            </td>
                            <td>
                                <form action="{{ route('pedidos.update', $pedido) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <select name="estado" class="form-control form-control-sm"
                                            onchange="this.form.submit()">
                                        @foreach(['pendiente','confirmado','en produccion','rechazado'] as $estado)
                                            <option value="{{ $estado }}"
                                                {{ $pedido->estado === $estado ? 'selected' : '' }}>
                                                {{ ucfirst($estado) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>{{ $pedido->fechapedido }}</td>
                            <td>
                                <a href="{{ route('pedidos.show', $pedido) }}"
                                   class="btn btn-sm btn-info">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection