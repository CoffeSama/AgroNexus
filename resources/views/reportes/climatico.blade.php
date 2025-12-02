@extends('layouts.app')

@section('title', 'Reporte Climático | AgroNexus')
@section('page_title', 'Dashboard Climático')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #2c5530;">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}" style="color: #2c5530;">Reportes</a></li>
    <li class="breadcrumb-item active">Climático</li>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #2c5530;
        --climate-blue: #17a2b8;
        --climate-orange: #fd7e14;
        --climate-cyan: #20c997;
    }
    
    .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .card-header { background: white; border-bottom: 2px solid #f1f3f4; font-weight: 600; }
    .chart-container { height: 300px; }
    
    .weather-current {
        background: linear-gradient(135deg, #17a2b8, #6dd5ed);
        border-radius: 15px;
        color: white;
        padding: 30px;
        text-align: center;
    }
    
    .weather-current .temp-main {
        font-size: 4rem;
        font-weight: 300;
        line-height: 1;
    }
    
    .weather-current .weather-desc {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-top: 10px;
    }
    
    .weather-icon {
        font-size: 5rem;
        margin-bottom: 10px;
    }
    
    .weather-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 20px;
    }
    
    .weather-detail-item {
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
    }
    
    .weather-detail-item i {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }
    
    .weather-detail-item .value {
        font-size: 1.3rem;
        font-weight: 600;
    }
    
    .weather-detail-item .label {
        font-size: 0.8rem;
        opacity: 0.8;
    }
    
    .forecast-card {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .forecast-card:hover {
        background: #e8f4f8;
        transform: translateY(-3px);
    }
    
    .forecast-card .day {
        font-weight: 600;
        color: #1a252f;
        margin-bottom: 10px;
    }
    
    .forecast-card .temp {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--climate-blue);
    }
    
    .forecast-card .temp-min {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .stat-clima {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid var(--climate-blue);
    }
    
    .stat-clima h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--climate-blue);
        margin-bottom: 5px;
    }
    
    .stat-clima.temp { border-left-color: #fd7e14; }
    .stat-clima.temp h3 { color: #fd7e14; }
    .stat-clima.hum { border-left-color: #17a2b8; }
    .stat-clima.hum h3 { color: #17a2b8; }
    .stat-clima.rain { border-left-color: #6f42c1; }
    .stat-clima.rain h3 { color: #6f42c1; }
    
    .filter-card {
        background: #f8f9fc;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .historial-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .historial-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')
<!-- Filtros -->
<div class="filter-card">
    <form method="GET" action="{{ route('reportes.climatico') }}" class="row align-items-end">
        <div class="col-md-4 mb-2">
            <label><i class="fas fa-map-marker-alt mr-1"></i>Lote</label>
            <select name="lote_id" class="form-control">
                <option value="">Todos los lotes</option>
                @foreach($lotes as $lote)
                    <option value="{{ $lote->loteid }}" {{ $loteId == $lote->loteid ? 'selected' : '' }}>
                        {{ $lote->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <label><i class="fas fa-calendar mr-1"></i>Período</label>
            <select name="dias" class="form-control">
                <option value="7" {{ $dias == 7 ? 'selected' : '' }}>Últimos 7 días</option>
                <option value="15" {{ $dias == 15 ? 'selected' : '' }}>Últimos 15 días</option>
                <option value="30" {{ $dias == 30 ? 'selected' : '' }}>Últimos 30 días</option>
                <option value="60" {{ $dias == 60 ? 'selected' : '' }}>Últimos 60 días</option>
            </select>
        </div>
        <div class="col-md-4 mb-2">
            <button type="submit" class="btn btn-info btn-block">
                <i class="fas fa-filter mr-1"></i>Actualizar
            </button>
        </div>
    </form>
</div>

<div class="row">
    <!-- Clima actual -->
    <div class="col-md-4">
        <div class="weather-current mb-4">
            <img src="https://openweathermap.org/img/wn/{{ $climaActual['icono'] }}@4x.png" alt="Clima" style="width: 100px;">
            <div class="temp-main">{{ $climaActual['temperatura'] }}°C</div>
            <div class="weather-desc">{{ $climaActual['descripcion'] }}</div>
            <small><i class="fas fa-map-marker-alt mr-1"></i>{{ $climaActual['ubicacion'] }}</small>
            
            <div class="weather-details">
                <div class="weather-detail-item">
                    <i class="fas fa-thermometer-half"></i>
                    <div class="value">{{ $climaActual['sensacion'] }}°C</div>
                    <div class="label">Sensación</div>
                </div>
                <div class="weather-detail-item">
                    <i class="fas fa-tint"></i>
                    <div class="value">{{ $climaActual['humedad'] }}%</div>
                    <div class="label">Humedad</div>
                </div>
                <div class="weather-detail-item">
                    <i class="fas fa-wind"></i>
                    <div class="value">{{ $climaActual['viento'] }} km/h</div>
                    <div class="label">Viento</div>
                </div>
                <div class="weather-detail-item">
                    <i class="fas fa-compress-arrows-alt"></i>
                    <div class="value">{{ $climaActual['presion'] }} hPa</div>
                    <div class="label">Presión</div>
                </div>
            </div>
        </div>
        
        <!-- Pronóstico -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Pronóstico</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($pronostico as $dia)
                        <div class="col mb-2">
                            <div class="forecast-card">
                                <div class="day">{{ $dia['dia'] }}</div>
                                <img src="https://openweathermap.org/img/wn/{{ $dia['icono'] }}@2x.png" alt="" style="width: 50px;">
                                <div class="temp">{{ $dia['temp_max'] }}°</div>
                                <div class="temp-min">{{ $dia['temp_min'] }}°</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráficos y estadísticas -->
    <div class="col-md-8">
        <!-- Promedios -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-clima temp">
                    <h3>{{ number_format($promedios['temperatura'], 1) }}°C</h3>
                    <p class="text-muted mb-0"><i class="fas fa-thermometer-half mr-1"></i>Temp. Promedio</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-clima hum">
                    <h3>{{ number_format($promedios['humedad'], 1) }}%</h3>
                    <p class="text-muted mb-0"><i class="fas fa-tint mr-1"></i>Humedad Promedio</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-clima rain">
                    <h3>{{ number_format($promedios['precipitacion'], 1) }} mm</h3>
                    <p class="text-muted mb-0"><i class="fas fa-cloud-rain mr-1"></i>Precipitación Total</p>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de tendencias -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Tendencias Climáticas</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="climaChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Historial -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history mr-2"></i>Historial de Registros</h3>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($historialClima as $registro)
                    <div class="historial-item">
                        <div>
                            <strong>{{ $registro->fecha instanceof \Carbon\Carbon ? $registro->fecha->format('d/m/Y H:i') : $registro->fecha }}</strong>
                            <br>
                            <small class="text-muted">{{ $registro->lote->nombre ?? 'General' }}</small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-warning">{{ $registro->temperatura }}°C</span>
                            <span class="badge badge-info">{{ $registro->humedad }}%</span>
                            <span class="badge badge-primary">{{ $registro->lluvia ?? 0 }} mm</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3">No hay registros climáticos en el período seleccionado</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
var datosGrafico = @json($datosGrafico);

if (datosGrafico.length > 0) {
    new Chart(document.getElementById('climaChart'), {
        type: 'line',
        data: {
            labels: datosGrafico.map(d => d.dia),
            datasets: [{
                label: 'Temperatura (°C)',
                data: datosGrafico.map(d => parseFloat(d.temp)),
                borderColor: '#fd7e14',
                backgroundColor: 'rgba(253, 126, 20, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Humedad (%)',
                data: datosGrafico.map(d => parseFloat(d.hum)),
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                yAxisID: 'y1'
            }, {
                label: 'Lluvia (mm)',
                data: datosGrafico.map(d => parseFloat(d.prec) || 0),
                borderColor: '#6f42c1',
                backgroundColor: 'rgba(111, 66, 193, 0.3)',
                borderWidth: 2,
                type: 'bar',
                yAxisID: 'y2'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Temperatura (°C)' },
                    grid: { color: '#f1f3f4' }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: { display: true, text: 'Humedad (%)' },
                    grid: { drawOnChartArea: false }
                },
                y2: {
                    type: 'linear',
                    position: 'right',
                    title: { display: true, text: 'Lluvia (mm)' },
                    grid: { drawOnChartArea: false },
                    display: false
                }
            }
        }
    });
} else {
    document.getElementById('climaChart').parentNode.innerHTML = '<p class="text-muted text-center py-5">No hay datos suficientes para mostrar el gráfico</p>';
}
</script>
@endpush