@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-file-invoice mr-2"></i>
                    Pedido #{{ $pedido->pedidoid }}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Información Principal -->
            <div class="col-lg-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Información del Pedido
                        </h3>
                        <div class="card-tools">
                            <span class="badge {{ 
                                $pedido->estado === 'pendiente' ? 'badge-info' : 
                                ($pedido->estado === 'confirmado' ? 'badge-success' : 
                                ($pedido->estado === 'en produccion' ? 'badge-warning' : 'badge-danger'))
                            }} badge-lg">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-primary">
                                        <i class="fas fa-seedling"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Planta</span>
                                        <span class="info-box-number">{{ $pedido->nombre_planta }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-leaf"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Cultivo</span>
                                        <span class="info-box-number">
                                            {{ $pedido->cultivo->nombre ?? $pedido->cultivo_personalizado }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-weight-hanging"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Cantidad</span>
                                        <span class="info-box-number">
                                            {{ number_format($pedido->cantidad, 2) }} {{ $pedido->unidadMedida->nombre }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Fecha Pedido</span>
                                        <span class="info-box-number">
                                            {{ \Carbon\Carbon::parse($pedido->fechapedido)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <dl class="row">
                            <dt class="col-sm-4">
                                <i class="fas fa-truck mr-2 text-primary"></i>
                                Fecha Entrega Deseada
                            </dt>
                            <dd class="col-sm-8">
                                @if($pedido->fechaEntregaDeseada)
                                    <span class="badge badge-light">
                                        {{ \Carbon\Carbon::parse($pedido->fechaEntregaDeseada)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">No especificada</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">
                                <i class="fas fa-map-marker-alt mr-2 text-danger"></i>
                                Coordenadas
                            </dt>
                            <dd class="col-sm-8">
                                <code>{{ $pedido->latitud }}, {{ $pedido->longitud }}</code>
                            </dd>

                            @if($pedido->direccion_texto)
                            <dt class="col-sm-4">
                                <i class="fas fa-location-arrow mr-2 text-info"></i>
                                Dirección
                            </dt>
                            <dd class="col-sm-8">
                                {{ $pedido->direccion_texto }}
                            </dd>
                            @endif

                            @if($pedido->observaciones)
                            <dt class="col-sm-4">
                                <i class="fas fa-comment-dots mr-2 text-warning"></i>
                                Observaciones
                            </dt>
                            <dd class="col-sm-8">
                                <div class="callout callout-info">
                                    {{ $pedido->observaciones }}
                                </div>
                            </dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Mapa -->
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map mr-2"></i>
                            Ubicación del Pedido
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Ubicación: {{ $pedido->latitud }}, {{ $pedido->longitud }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
                <!-- Actualizar Estado -->
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit mr-2"></i>
                            Actualizar Estado
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pedidos.update', $pedido) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="estado">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Estado del Pedido
                                </label>
                                <select name="estado" id="estado" class="form-control form-control-lg">
                                    @foreach(['pendiente','confirmado','en produccion','rechazado'] as $estado)
                                        <option value="{{ $estado }}"
                                            {{ $pedido->estado === $estado ? 'selected' : '' }}>
                                            {{ ucfirst($estado) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-save mr-2"></i>
                                Actualizar Estado
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog mr-2"></i>
                            Acciones
                        </h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('pedidos.index') }}" class="btn btn-default btn-block">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Listado
                        </a>
                        
                        <a href="#" class="btn btn-info btn-block" onclick="window.print(); return false;">
                            <i class="fas fa-print mr-2"></i>
                            Imprimir Pedido
                        </a>

                        <hr>

                        <form action="{{ route('pedidos.destroy', $pedido) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Está seguro de eliminar este pedido? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash mr-2"></i>
                                Eliminar Pedido
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Timeline (Historial) -->
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>
                            Historial
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="time-label">
                                <span class="bg-primary">
                                    {{ \Carbon\Carbon::parse($pedido->fechapedido)->format('d M Y') }}
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-plus bg-success"></i>
                                <div class="timeline-item">
                                    <span class="time">
                                        <i class="fas fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($pedido->fechapedido)->format('H:i') }}
                                    </span>
                                    <h3 class="timeline-header">Pedido Creado</h3>
                                    <div class="timeline-body">
                                        El pedido fue registrado en el sistema
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .info-box-number {
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }

    .callout {
        border-left: 5px solid #e9ecef;
        border-radius: 0.25rem;
        padding: 1rem;
        margin: 1rem 0;
    }

    .callout-info {
        border-left-color: #17a2b8;
        background-color: #d1ecf1;
    }

    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }

    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }

    .timeline > div > .timeline-item {
        margin-right: 0;
        margin-left: 60px;
        margin-top: 0;
        border-radius: 0.25rem;
        background: #fff;
        border: 1px solid #dee2e6;
        padding: 0;
    }

    .timeline > div > .fas,
    .timeline > div > .far,
    .timeline > div > .ion {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #fff;
        background: #6c757d;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }

    .timeline-header {
        margin: 0;
        padding: 10px;
        font-size: 16px;
        font-weight: 600;
        border-bottom: 1px solid #dee2e6;
    }

    .timeline-body {
        padding: 10px;
    }

    .time-label > span {
        font-weight: 600;
        padding: 5px 10px;
        display: inline-block;
        border-radius: 0.25rem;
    }

    @media print {
        .card-tools,
        .btn,
        .breadcrumb,
        .content-header {
            display: none !important;
        }
    }

    .leaflet-popup-content {
        font-size: 14px;
        line-height: 1.6;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el mapa
        const lat = {{ $pedido->latitud }};
        const lng = {{ $pedido->longitud }};
        
        const map = L.map('map').setView([lat, lng], 15);
        
        // Capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Crear icono personalizado
        const customIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        
        // Agregar marcador
        const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
        
        // Contenido del popup
        const popupContent = `
            <div style="min-width: 200px;">
                <h6 style="margin: 0 0 10px 0; font-weight: bold; color: #007bff;">
                    <i class="fas fa-seedling"></i> ${@json($pedido->nombre_planta)}
                </h6>
                <p style="margin: 5px 0;">
                    <strong>Cultivo:</strong> ${@json($pedido->cultivo->nombre ?? $pedido->cultivo_personalizado)}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Cantidad:</strong> ${@json($pedido->cantidad)} ${@json($pedido->unidadMedida->nombre)}
                </p>
                ${@json($pedido->direccion_texto) ? `
                <p style="margin: 5px 0; color: #6c757d; font-size: 12px;">
                    <i class="fas fa-map-marker-alt"></i> ${@json($pedido->direccion_texto)}
                </p>
                ` : ''}
                <hr style="margin: 10px 0;">
                <small style="color: #6c757d;">
                    <i class="fas fa-location-arrow"></i> ${lat.toFixed(6)}, ${lng.toFixed(6)}
                </small>
            </div>
        `;
        
        marker.bindPopup(popupContent).openPopup();
        
        // Ajustar el zoom para mostrar el marcador
        map.setView([lat, lng], 15);
    });
</script>
@endpush
@endsection