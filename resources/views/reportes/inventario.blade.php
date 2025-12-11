@extends('layouts.app')

@section('title', 'Reporte de Inventario | AgroNexus')
@section('page_title', 'Dashboard de Inventario')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #2c5530;">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}" style="color: #2c5530;">Reportes</a></li>
    <li class="breadcrumb-item active">Inventario</li>
@endsection

@push('styles')
    <style>
        :root {
            --primary-color: #2c5530;
            --inventory-blue: #1890ff;
            --inventory-red: #f5222d;
            --inventory-green: #52c41a;
            --inventory-orange: #fa8c16;
        }

        .small-box {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .small-box .icon {
            font-size: 70px !important;
        }

        .small-box-inventory-total {
            background: linear-gradient(135deg, var(--inventory-blue), #40a9ff) !important;
        }

        .small-box-inventory-low {
            background: linear-gradient(135deg, var(--inventory-red), #ff7875) !important;
        }

        .small-box-inventory-good {
            background: linear-gradient(135deg, var(--inventory-green), #73d13d) !important;
        }

        .small-box-inventory-value {
            background: linear-gradient(135deg, var(--inventory-orange), #ffa940) !important;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: white;
            border-bottom: 2px solid #f1f3f4;
            font-weight: 600;
        }

        .chart-container {
            height: 300px;
        }

        .alert-inventory {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            background: #fff5f5;
            border-radius: 8px;
            border-left: 4px solid var(--inventory-red);
        }

        .alert-inventory.warning {
            background: #fffbe6;
            border-left-color: var(--inventory-orange);
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--inventory-red);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .alert-inventory.warning .alert-icon {
            background: var(--inventory-orange);
        }

        .inventory-progress {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            margin-top: 8px;
        }

        .inventory-progress-bar {
            height: 100%;
            border-radius: 3px;
        }

        .inventory-progress-bar.critico {
            background: var(--inventory-red);
        }

        .inventory-progress-bar.bajo {
            background: var(--inventory-orange);
        }

        .inventory-progress-bar.bueno {
            background: var(--inventory-green);
        }

        .inventory-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #f1f3f4;
        }

        .inventory-item:last-child {
            border-bottom: none;
        }

        .inventory-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }

        .inventory-icon.fertilizante {
            background: var(--inventory-green);
        }

        .inventory-icon.semilla {
            background: var(--inventory-orange);
        }

        .inventory-icon.pesticida,
        .inventory-icon.herbicida {
            background: var(--inventory-red);
        }

        .inventory-icon.otro {
            background: var(--inventory-blue);
        }

        .stock-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .stock-status.critico {
            background: #ffebee;
            color: var(--inventory-red);
        }

        .stock-status.bajo {
            background: #fff8e1;
            color: #f57c00;
        }

        .stock-status.bueno {
            background: #e8f5e9;
            color: var(--inventory-green);
        }

        .stock-status.optimo {
            background: #e3f2fd;
            color: var(--inventory-blue);
        }

        .almacen-item {
            background: #f8f9fc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .almacen-progress {
            height: 10px;
            background: #e9ecef;
            border-radius: 5px;
            margin-top: 10px;
        }

        .almacen-progress-bar {
            height: 100%;
            border-radius: 5px;
            background: var(--primary-color);
        }
    </style>
@endpush

@section('content')
    <!-- Métricas principales -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box small-box-inventory-total">
                <div class="inner">
                    <h3>{{ $stats['total_insumos'] }}</h3>
                    <p>Total de Insumos</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <a href="{{ route('insumos.index') }}" class="small-box-footer">Ver catálogo <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box small-box-inventory-low">
                <div class="inner">
                    <h3>{{ $stats['stock_critico'] }}</h3>
                    <p>Stock Crítico</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                <a href="#alertas" class="small-box-footer">Ver alertas <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box small-box-inventory-good">
                <div class="inner">
                    <h3>{{ $stats['stock_disponible'] }}</h3>
                    <p>Stock Disponible</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <a href="{{ route('insumos.index') }}" class="small-box-footer">Ver disponibles <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box small-box-inventory-value">
                <div class="inner">
                    <h3>Bs. {{ number_format($stats['valor_total'], 0) }}</h3>
                    <p>Valor Total</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <a href="#" class="small-box-footer">Ver valorización <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Gráficos y Alertas -->
    <div class="row">
        <!-- Gráfico por tipo -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Stock por Tipo de Insumo</h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="stockTipoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas de stock bajo -->
        <div class="col-md-4" id="alertas">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-2 text-danger"></i>Alertas de Stock Bajo
                    </h3>
                </div>
                <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                    @forelse($alertasStock as $insumo)
                        @php
                            $stockMin = $insumo->stockminimo ?? 10;
                            $porcentaje = $stockMin > 0 ? ($insumo->stock / $stockMin) * 100 : 0;
                            $esCritico = $porcentaje < 30;
                        @endphp
                        <div class="alert-inventory {{ $esCritico ? '' : 'warning' }}">
                            <div class="alert-icon">
                                <i class="fas fa-{{ $esCritico ? 'exclamation-triangle' : 'exclamation-circle' }}"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="mb-1">{{ $insumo->nombre }}</h6>
                                <small class="text-muted">
                                    Stock: {{ $insumo->stock }} {{ $insumo->unidadMedida->abreviatura ?? '' }} |
                                    Mínimo: {{ $stockMin }}
                                </small>
                                <div class="inventory-progress">
                                    <div class="inventory-progress-bar {{ $esCritico ? 'critico' : 'bajo' }}"
                                        style="width: {{ min($porcentaje, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-success py-4">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <p>Todo el inventario está en niveles óptimos</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Stock en Almacenes y Consumo -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-warehouse mr-2"></i>Stock en Almacenes</h3>
                </div>
                <div class="card-body">
                    @forelse($stockAlmacenes as $almacen)
                        @php
                            $porcentaje = $almacen->capacidadmaxima > 0 ? ($almacen->stockactual / $almacen->capacidadmaxima) * 100 : 0;
                        @endphp
                        <div class="almacen-item">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $almacen->nombre }}</strong>
                                <span>{{ number_format($almacen->stockactual, 0) }} /
                                    {{ number_format($almacen->capacidadmaxima, 0) }} kg</span>
                            </div>
                            <div class="almacen-progress">
                                <div class="almacen-progress-bar" style="width: {{ min($porcentaje, 100) }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format($porcentaje, 1) }}% de capacidad</small>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">No hay almacenes configurados</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Consumo reciente -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-2"></i>Consumo Reciente (30 días)</h3>
                </div>
                <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                    @forelse($consumoReciente as $consumo)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <strong>{{ $consumo->insumo->nombre ?? '-' }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $consumo->lote->nombre ?? '-' }} -
                                    {{ $consumo->fechauo instanceof \Carbon\Carbon ? $consumo->fechauo->format('d/m/Y') : $consumo->fechauo }}
                                </small>
                            </div>
                            <span class="badge badge-danger">
                                -{{ $consumo->cantidadusada }} {{ $consumo->insumo->unidadMedida->abreviatura ?? '' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">Sin consumo registrado</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Inventario completo -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-list mr-2"></i>Inventario Completo</h3>
            <div>
                <a href="{{ route('reportes.exportar', 'inventario') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-file-csv mr-1"></i>Exportar CSV
                </a>
                <a href="{{ route('reportes.exportar', ['tipo' => 'inventario', 'formato' => 'pdf']) }}"
                    class="btn btn-sm btn-danger ml-2">
                    <i class="fas fa-file-pdf mr-1"></i>Exportar PDF
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @foreach($insumosPorTipo as $tipo)
                @php
                    $tipoNombre = $tipo->tipo ?? 'Otros';
                    $tipoSlug = strtolower($tipoNombre);
                    $tipoIcon = match (true) {
                        str_contains($tipoSlug, 'fertil') => 'seedling',
                        str_contains($tipoSlug, 'semilla') => 'leaf',
                        str_contains($tipoSlug, 'pestic') || str_contains($tipoSlug, 'herbic') => 'spray-can',
                        default => 'box'
                    };
                @endphp
                <div style="background: #f8f9fc; padding: 12px 20px; border-bottom: 2px solid #e9ecef;">
                    <h5 class="mb-0">
                        <i class="fas fa-{{ $tipoIcon }} mr-2"></i>
                        {{ ucfirst($tipoNombre) }} ({{ $tipo->cantidad }} productos)
                    </h5>
                </div>
                @foreach($insumos->filter(fn($i) => ($i->tipo->nombre ?? null) == $tipo->tipo) as $insumo)
                    @php
                        $stockMin = $insumo->stockminimo ?? 10;
                        $porcentaje = $stockMin > 0 ? ($insumo->stock / $stockMin) * 100 : 100;
                        if ($porcentaje < 30)
                            $estado = 'critico';
                        elseif ($porcentaje < 80)
                            $estado = 'bajo';
                        elseif ($porcentaje < 150)
                            $estado = 'bueno';
                        else
                            $estado = 'optimo';

                        $insumoTipoSlug = strtolower($insumo->tipo->nombre ?? 'otro');
                        $iconClass = match (true) {
                            str_contains($insumoTipoSlug, 'fertil') => 'fertilizante',
                            str_contains($insumoTipoSlug, 'semilla') => 'semilla',
                            str_contains($insumoTipoSlug, 'pestic') || str_contains($insumoTipoSlug, 'herbic') => 'pesticida',
                            default => 'otro'
                        };
                    @endphp
                    <div class="inventory-item">
                        <div class="d-flex align-items-center flex-1">
                            <div class="inventory-icon {{ $iconClass }}">
                                <i class="fas fa-{{ $tipoIcon }}"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $insumo->nombre }}</h6>
                                <small class="text-muted">{{ $insumo->descripcion ?? 'Sin descripción' }}</small>
                            </div>
                        </div>
                        <div class="text-center" style="min-width: 100px;">
                            <div style="font-size: 18px; font-weight: 700;"
                                class="text-{{ $estado == 'critico' ? 'danger' : ($estado == 'bajo' ? 'warning' : 'success') }}">
                                {{ number_format($insumo->stock, 2) }}
                            </div>
                            <small class="text-muted text-uppercase">{{ $insumo->unidadMedida->abreviatura ?? '-' }}</small>
                        </div>
                        <div style="min-width: 80px;">
                            <span class="stock-status {{ $estado }}">{{ ucfirst($estado) }}</span>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        var tiposData = @json($insumosPorTipo);

        new Chart(document.getElementById('stockTipoChart'), {
            type: 'bar',
            data: {
                labels: tiposData.map(t => (t.tipo || 'Otros')),
                datasets: [{
                    label: 'Cantidad de productos',
                    data: tiposData.map(t => parseInt(t.cantidad)),
                    backgroundColor: '#1890ff'
                }, {
                    label: 'Stock total',
                    data: tiposData.map(t => parseFloat(t.stock) || 0),
                    backgroundColor: '#52c41a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endpush