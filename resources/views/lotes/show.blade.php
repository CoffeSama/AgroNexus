@extends('layouts.app')

@section('title', 'Detalle Lote | AgroNexus')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 350px; width: 100%; border-radius: 5px; border: 2px solid #ddd; }
    .info-label { font-weight: 600; color: #555; }
    .lote-image { max-width: 100%; max-height: 250px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0"><i class="fas fa-map-marked-alt mr-2"></i>Detalle del Lote: {{ $lote->nombre }}</h3>
        <span class="badge badge-light">ID: {{ $lote->loteid }}</span>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Columna izquierda: Información -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light"><i class="fas fa-info-circle mr-1"></i> Informacion General</div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td class="info-label" width="40%">Nombre:</td>
                                <td>{{ $lote->nombre }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Propietario:</td>
                                <td>{{ $lote->usuario->nombre ?? '-' }} {{ $lote->usuario->apellido ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Cultivo:</td>
                                <td>
                                    @if($lote->cultivo)
                                        <span class="badge badge-success">{{ $lote->cultivo->nombre }}</span>
                                    @else
                                        <span class="text-muted">Sin cultivo</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="info-label">Estado:</td>
                                <td>
                                    @php
                                        $estadoColor = [
                                            'disponible' => 'secondary',
                                            'en preparación' => 'info',
                                            'sembrado' => 'primary',
                                            'en producción' => 'success',
                                            'cosechado' => 'warning',
                                            'en descanso' => 'dark'
                                        ];
                                        $color = $estadoColor[$lote->estadoTipo->nombre ?? ''] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $color }}">{{ ucfirst($lote->estadoTipo->nombre ?? 'Sin estado') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="info-label">Superficie:</td>
                                <td><strong>{{ $lote->superficie }}</strong> hectareas</td>
                            </tr>
                            <tr>
                                <td class="info-label">Ubicacion:</td>
                                <td>{{ $lote->ubicacion ?? 'No especificada' }}</td>
                            </tr>
                            <tr>
                                <td class="info-label">Fecha Siembra:</td>
                                <td>{{ $lote->fechasiembra ? \Carbon\Carbon::parse($lote->fechasiembra)->format('d/m/Y') : 'No registrada' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Imagen del lote -->
                @if($lote->imagenurl)
                <div class="card mb-3">
                    <div class="card-header bg-light"><i class="fas fa-image mr-1"></i> Imagen del Lote</div>
                    <div class="card-body text-center">
                        <img src="{{ asset($lote->imagenurl) }}" alt="Imagen del lote" class="lote-image">
                    </div>
                </div>
                @endif

                <!-- Coordenadas -->
                <div class="card">
                    <div class="card-header bg-light"><i class="fas fa-map-pin mr-1"></i> Coordenadas</div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <small class="text-muted">Latitud</small>
                                <h5>{{ $lote->latitud ?? 'N/A' }}</h5>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Longitud</small>
                                <h5>{{ $lote->longitud ?? 'N/A' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: Mapa -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-light"><i class="fas fa-map mr-1"></i> Ubicacion en el Mapa</div>
                    <div class="card-body">
                        @if($lote->latitud && $lote->longitud)
                            <div id="map"></div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Este lote no tiene coordenadas registradas.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="d-flex justify-content-between">
            <a href="{{ route('lotes.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Volver</a>
            <div>
                <a href="{{ route('lotes.edit', $lote) }}" class="btn btn-warning"><i class="fas fa-edit mr-1"></i> Editar</a>
                <form action="{{ route('lotes.destroy', $lote) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este lote?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger"><i class="fas fa-trash mr-1"></i> Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($lote->latitud && $lote->longitud)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var lat = {{ $lote->latitud }};
var lng = {{ $lote->longitud }};
var superficie = {{ $lote->superficie ?? 0 }};

var map = L.map('map').setView([lat, lng], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

// Marcador
var marker = L.marker([lat, lng]).addTo(map);
marker.bindPopup('<b>{{ $lote->nombre }}</b><br>{{ $lote->ubicacion ?? "Sin ubicacion" }}<br>{{ $lote->superficie }} ha').openPopup();

// Circulo representando el area
if (superficie > 0) {
    var radio = Math.sqrt(superficie * 10000 / Math.PI);
    L.circle([lat, lng], {
        color: 'green',
        fillColor: '#28a745',
        fillOpacity: 0.3,
        radius: radio
    }).addTo(map);
}
</script>
@endif
@endpush