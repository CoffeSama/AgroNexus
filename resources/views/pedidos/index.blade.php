@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Pedidos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Pedidos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $pedidos->where('estado', 'pendiente')->count() }}</h3>
                        <p>Pendientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $pedidos->where('estado', 'confirmado')->count() }}</h3>
                        <p>Confirmados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pedidos->where('estado', 'en produccion')->count() }}</h3>
                        <p>En Producción</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pedidos->where('estado', 'rechazado')->count() }}</h3>
                        <p>Rechazados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    Listado de Pedidos
                </h3>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 60px">#ID</th>
                                <th>
                                    <i class="fas fa-seedling mr-1"></i>Planta
                                </th>
                                <th>
                                    <i class="fas fa-leaf mr-1"></i>Cultivo
                                </th>
                                <th style="width: 150px">
                                    <i class="fas fa-weight mr-1"></i>Cantidad
                                </th>
                                <th style="width: 180px">
                                    <i class="fas fa-info-circle mr-1"></i>Estado
                                </th>
                                <th style="width: 130px">
                                    <i class="fas fa-calendar mr-1"></i>Fecha
                                </th>
                                <th style="width: 150px" class="text-center">
                                    <i class="fas fa-tools mr-1"></i>Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidos as $pedido)
                                <tr>
                                    <td class="font-weight-bold">
                                        #{{ $pedido->pedidoid }}
                                    </td>
                                    <td>
                                        <span class="text-primary font-weight-bold">
                                            {{ $pedido->nombre_planta }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($pedido->cultivo)
                                            <span class="badge badge-info">
                                                {{ $pedido->cultivo->nombre }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                {{ $pedido->cultivo_personalizado }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ number_format($pedido->cantidad, 2) }}</strong>
                                        <small class="text-muted">{{ $pedido->unidadMedida->nombre }}</small>
                                    </td>
                                    <td>
                                        <form action="{{ route('pedidos.update', $pedido) }}" method="POST" class="d-inline-block w-100">
                                            @csrf
                                            @method('PUT')

                                            <select name="estado" 
                                                    class="form-control form-control-sm estado-select {{ 
                                                        $pedido->estado === 'pendiente' ? 'bg-info' : 
                                                        ($pedido->estado === 'confirmado' ? 'bg-success' : 
                                                        ($pedido->estado === 'en produccion' ? 'bg-warning' : 'bg-danger'))
                                                    }}"
                                                    style="color: white; font-weight: 500;"
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
                                    <td>
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ \Carbon\Carbon::parse($pedido->fechapedido)->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('pedidos.show', $pedido) }}" 
                                               class="btn btn-info"
                                               title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('pedidos.destroy', $pedido) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Está seguro de eliminar este pedido?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p class="h5">No hay pedidos registrados</p>
                                            <a href="{{ route('pedidos.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus mr-1"></i> Crear primer pedido
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($pedidos->isNotEmpty())
            <div class="card-footer clearfix">
                <div class="float-left">
                    <small class="text-muted">
                        Mostrando {{ $pedidos->count() }} pedido(s)
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
    .estado-select {
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .estado-select:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }

    .small-box .icon {
        font-size: 70px;
        position: absolute;
        right: 15px;
        top: 15px;
        transition: all .3s linear;
        color: rgba(0,0,0,.15);
    }

    .small-box:hover .icon {
        transform: scale(1.1);
    }

    .card-primary.card-outline {
        border-top: 3px solid #007bff;
    }

    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-hide success messages
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 3000);
</script>
@endpush
@endsection