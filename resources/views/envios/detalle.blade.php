@extends('layouts.app')

@section('title', 'Seguimiento del Envío')

@section('page_title', 'Seguimiento del Envío')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('envios.seguimiento') }}">Seguimiento</a></li>
    <li class="breadcrumb-item active">Detalle</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="detalleEnvio"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
.timeline-item {
    border-left: 3px solid #dee2e6;
    padding-left: 15px;
    margin-left: 5px;
}
.timeline-item.recogida {
    border-left-color: #28a745;
}
.timeline-item.entrega {
    border-left-color: #dc3545;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
if (!window.__envioDetalleInitialized) {
    window.__envioDetalleInitialized = true;
    
    const envioId = {{ $id ?? 0 }};
    const cont = document.getElementById('detalleEnvio');
    const state = { envio: null };
    const loaderHtml = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando información del envío...</div>';

    function badgeFor(estado){
        const map = { 
            'En curso': 'badge-info', 
            'Pendiente': 'badge-warning', 
            'Asignado': 'badge-primary', 
            'Entregado': 'badge-success',
            'Completado': 'badge-success',
            'Finalizado': 'badge-secondary',
            'Completada': 'badge-success'
        };
        const cls = map[estado] || 'badge-light';
        return `<span class="badge ${cls} ml-2">${estado}</span>`;
    }

    function renderEnvio(envio){
        state.envio = envio;
        const particiones = Array.isArray(envio.particiones) ? envio.particiones : [];
        
        if (particiones.length === 0){
            cont.innerHTML = '<div class="alert alert-info"><i class="fas fa-info-circle mr-2"></i>Este envío no tiene particiones.</div>';
            return;
        }
        
        const wrapper = document.createElement('div');
        
        particiones.forEach((p, idx) => {
            const card = document.createElement('div');
            card.className = 'card mb-4';
            
            const body = document.createElement('div');
            body.className = 'card-body';

            const row = document.createElement('div');
            row.className = 'row';

            const colLeft = document.createElement('div');
            colLeft.className = 'col-lg-6';
            colLeft.innerHTML = `
                <h5 class="mb-3 d-flex align-items-center justify-content-between">
                    Partición ${idx + 1}
                    ${badgeFor(p.estado)}
                </h5>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Transportista</h6>
                    <p class="mb-1">Nombre: ${(p.transportista?.nombre || '—')} ${(p.transportista?.apellido || '')}</p>
                    <p class="mb-1">Teléfono: ${p.transportista?.telefono || '—'}</p>
                    <p class="mb-0">CI: ${p.transportista?.ci || '—'}</p>
                </div>

                <div class="mb-3">
                    <h6 class="font-weight-bold">Vehículo</h6>
                    <p class="mb-0">Placa: ${p.vehiculo?.placa || '—'}</p>
                </div>

                <div class="mb-3">
                    <h6 class="font-weight-bold">Transporte</h6>
                    <p class="mb-1">Tipo de transporte: ${p.tipoTransporte?.nombre || '—'}</p>
                    <p class="mb-0">Descripción: ${p.tipoTransporte?.descripcion || '—'}</p>
                </div>

                <div class="timeline-item recogida mb-3">
                    <div class="mb-2">
                        <i class="fas fa-circle text-success mr-1" style="font-size: 0.5rem;"></i>
                        <strong>Recogida:</strong> ${p.recogidaEntrega?.fecha_recogida || '—'} – ${p.recogidaEntrega?.hora_recogida || '—'}
                    </div>
                    <div class="p-2 bg-light rounded">
                        <strong>Origen:</strong> ${envio.nombre_origen || '—'}<br>
                        ${Array.isArray(p.cargas) && p.cargas.length ? p.cargas.map(c => `
                            <div class="mt-1">• ${c.tipo} - ${c.variedad} (${Number(c.cantidad || 0)} uds, ${Number(c.peso || 0).toFixed(1)} kg, ${c.empaquetado || '—'})</div>
                        `).join('') : '<div class="mt-1">Sin productos</div>'}
                        <div class="mt-2 text-muted" style="font-size: 0.9rem;">
                            ${p.recogidaEntrega?.instrucciones_recogida || 'Sin instrucciones'}
                        </div>
                    </div>
                </div>

                <div class="timeline-item entrega">
                    <div class="mb-2">
                        <i class="fas fa-circle text-danger mr-1" style="font-size: 0.5rem;"></i>
                        <strong>Entrega:</strong> ${p.recogidaEntrega?.fecha_recogida || '—'} – ${p.recogidaEntrega?.hora_entrega || '—'}
                    </div>
                    <div class="p-2 bg-light rounded">
                        <strong>Destino:</strong> ${envio.nombre_destino || '—'}<br>
                        ${Array.isArray(p.cargas) && p.cargas.length ? p.cargas.map(c => `
                            <div class="mt-1">• ${c.tipo} - ${c.variedad} (${Number(c.cantidad || 0)} uds, ${Number(c.peso || 0).toFixed(1)} kg, ${c.empaquetado || '—'})</div>
                        `).join('') : '<div class="mt-1">Sin productos</div>'}
                        <div class="mt-2 text-muted" style="font-size: 0.9rem;">
                            ${p.recogidaEntrega?.instrucciones_entrega || 'Sin instrucciones'}
                        </div>
                    </div>
                </div>
            `;

            const colRight = document.createElement('div');
            colRight.className = 'col-lg-6';
            const mapId = `map-${idx}`;
            colRight.innerHTML = `
                <div id="${mapId}" style="height: 420px;" class="rounded border mb-3"></div>
                <div class="p-3 bg-light rounded border text-center">
                    <h6 class="mb-2 font-weight-bold">Código de Acceso</h6>
                    <p class="mb-0 h4 text-primary" style="font-family: monospace; letter-spacing: 3px;">${p.codigo_acceso || 'No asignado'}</p>
                </div>
            `;

            row.appendChild(colLeft);
            row.appendChild(colRight);
            body.appendChild(row);

            if (p.id_transportista && p.id_vehiculo) {
                const alertSection = document.createElement('div');
                alertSection.className = 'mt-3';
                alertSection.innerHTML = `
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>Recursos confirmados</strong><br>
                        Esta partición ya tiene transportista y vehículo asignados.
                    </div>
                `;
                body.appendChild(alertSection);
            }

            card.appendChild(body);
            wrapper.appendChild(card);

            setTimeout(() => {
                const map = L.map(mapId).setView([
                    envio.coordenadas_origen?.lat || -0.1807, 
                    envio.coordenadas_origen?.lng || -78.4678
                ], 12);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
                    maxZoom: 19, 
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' 
                }).addTo(map);
                
                const o = [envio.coordenadas_origen?.lat, envio.coordenadas_origen?.lng];
                const d = [envio.coordenadas_destino?.lat, envio.coordenadas_destino?.lng];
                
                const iconoOrigen = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                
                const iconoDestino = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                
                if (o[0] && o[1]) {
                    L.marker(o, { icon: iconoOrigen }).addTo(map).bindPopup(`<strong>Origen:</strong><br>${envio.nombre_origen || 'Sin nombre'}`);
                }
                if (d[0] && d[1]) {
                    L.marker(d, { icon: iconoDestino }).addTo(map).bindPopup(`<strong>Destino:</strong><br>${envio.nombre_destino || 'Sin nombre'}`);
                }
                
                let routeCoordinates = [];
                
                try {
                    if (envio.rutaGeoJSON) {
                        const gj = JSON.parse(envio.rutaGeoJSON);
                        const layer = L.geoJSON(gj, { 
                            style: { color: '#007bff', weight: 4, opacity: 0.7 } 
                        }).addTo(map);
                        map.fitBounds(layer.getBounds(), { padding: [20, 20] });
                        
                        if (gj.type === 'LineString' && Array.isArray(gj.coordinates)) {
                            routeCoordinates = gj.coordinates.map(coord => [coord[1], coord[0]]);
                        } else if (gj.type === 'FeatureCollection' && Array.isArray(gj.features)) {
                            gj.features.forEach(feature => {
                                if (feature.geometry?.type === 'LineString' && Array.isArray(feature.geometry.coordinates)) {
                                    feature.geometry.coordinates.forEach(coord => {
                                        routeCoordinates.push([coord[1], coord[0]]);
                                    });
                                }
                            });
                        }
                    } else if (o[0] && d[0]) {
                        const line = L.polyline([o, d], { color: '#007bff', weight: 4, opacity: 0.7 }).addTo(map);
                        map.fitBounds(line.getBounds(), { padding: [20, 20] });
                        routeCoordinates = [o, d];
                    }
                } catch (e) {
                    console.error('Error al procesar ruta:', e);
                }
                
                if (p.estado === 'En curso' && routeCoordinates.length > 0) {
                    function interpolatePoints(coord1, coord2, steps = 20) {
                        const points = [];
                        for (let i = 0; i <= steps; i++) {
                            const ratio = i / steps;
                            const lat = coord1[0] + (coord2[0] - coord1[0]) * ratio;
                            const lng = coord1[1] + (coord2[1] - coord1[1]) * ratio;
                            points.push([lat, lng]);
                        }
                        return points;
                    }
                    
                    const smoothRoute = [];
                    for (let i = 0; i < routeCoordinates.length - 1; i++) {
                        const interpolated = interpolatePoints(routeCoordinates[i], routeCoordinates[i + 1], 20);
                        smoothRoute.push(...interpolated);
                    }
                    
                    const greenDotIcon = L.divIcon({
                        className: 'animated-marker',
                        html: '<div style="width: 16px; height: 16px; background-color: #28a745; border: 3px solid white; border-radius: 50%; box-shadow: 0 0 10px rgba(40, 167, 69, 0.8);"></div>',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });
                    
                    const animatedMarker = L.marker(smoothRoute[0], { icon: greenDotIcon }).addTo(map);
                    animatedMarker.bindPopup('Vehículo en tránsito');
                    
                    let currentIndex = 0;
                    const animationSpeed = 150;
                    
                    const animateMarker = setInterval(() => {
                        currentIndex++;
                        if (currentIndex >= smoothRoute.length) {
                            currentIndex = 0;
                        }
                        animatedMarker.setLatLng(smoothRoute[currentIndex]);
                    }, animationSpeed);
                    
                    map._animationInterval = animateMarker;
                }
            }, 100);
        });
        
        cont.innerHTML = '';
        cont.appendChild(wrapper);
    }

    async function obtenerEnvio() {
        const res = await fetch(`http://localhost:8000/api/public/envios/${envioId}/seguimiento`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });
        
        if (!res.ok) {
            throw new Error('No se pudo cargar el envío');
        }
        
        return await res.json();
    }

    async function cargarEnvio() {
        cont.innerHTML = loaderHtml;
        try {
            const envio = await obtenerEnvio();
            console.log('Envío cargado:', envio);
            renderEnvio(envio);
        } catch (e) {
            cont.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>${e.message}</div>`;
        }
    }

    cargarEnvio();
}
</script>
@endpush
