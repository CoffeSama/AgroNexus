@extends('layouts.app')

@section('title', 'Seguimiento del Envío')

@section('page_title', 'Seguimiento del Envío')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('envios.seguimiento') }}">Envíos</a></li>
    <li class="breadcrumb-item active">Detalle #{{ $id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div id="detalleEnvio"></div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .leaflet-container {
        height: 400px !important;
        width: 100% !important;
        border-radius: 4px;
        z-index: 1;
    }
    .info-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.2rem;
    }
    .info-value {
        color: #555;
        margin-bottom: 1rem;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .timeline-item {
        position: relative;
        padding-left: 20px;
        margin-bottom: 1.5rem;
    }
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 6px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .dot-green { background-color: #28a745; }
    .dot-gray { background-color: #6c757d; }
    
    .location-box {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 4px;
        margin-top: 8px;
        border: 1px solid #e9ecef;
    }
    .access-code-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 15px;
        margin-top: 15px;
    }
    .access-code-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #007bff;
        font-family: monospace;
        letter-spacing: 1px;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
if (!window.__envioShowPublicInitialized) {
    window.__envioShowPublicInitialized = true;
    
    const API_URL = 'http://192.168.0.11:8000';
    const envioId = {{ (int)($id ?? 0) }};
    const cont = document.getElementById('detalleEnvio');
    const loaderHtml = '<div class="text-center text-muted py-4">Cargando particiones...</div>';

    function badgeFor(estado){
        const map = { 'En curso':'badge-info', 'Pendiente':'badge-warning', 'Asignado':'badge-primary', 'Entregado':'badge-success', 'Finalizado':'badge-secondary', 'Completado':'badge-success' };
        const cls = map[estado] || 'badge-light';
        return `<span class="badge ${cls}">${estado}</span>`;
    }

    function renderEnvio(envio){
        const particiones = Array.isArray(envio.particiones) ? envio.particiones : [];
        if (particiones.length === 0){
            cont.innerHTML = '<div class="text-muted">Este envío no tiene particiones.</div>';
            return;
        }
        
        const wrapper = document.createElement('div');
        
        particiones.forEach((p, idx) => {
            const card = document.createElement('div');
            card.className = 'card mb-4 shadow-sm';
            
            // Header
            const header = document.createElement('div');
            header.className = 'card-header bg-white d-flex justify-content-between align-items-center';
            header.innerHTML = `
                <h5 class="mb-0">Partición ${idx+1}</h5>
                ${badgeFor(p.estado)}
            `;
            card.appendChild(header);

            // Body
            const body = document.createElement('div');
            body.className = 'card-body';
            
            const row = document.createElement('div');
            row.className = 'row';

            // Left Column (Info)
            const colLeft = document.createElement('div');
            colLeft.className = 'col-lg-6';
            
            colLeft.innerHTML = `
                <div class="info-label">Transportista</div>
                <div class="info-value">
                    Nombre: ${(p.transportista?.nombre || '—')} ${(p.transportista?.apellido || '')}<br>
                    Teléfono: ${p.transportista?.telefono || '—'}<br>
                    CI: ${p.transportista?.ci || '—'}
                </div>

                <div class="info-label">Vehículo</div>
                <div class="info-value">
                    Placa: ${p.vehiculo?.placa || '—'}
                </div>

                <div class="info-label">Transporte</div>
                <div class="info-value">
                    Tipo de transporte: ${p.tipoTransporte?.nombre || '—'}<br>
                    Descripción: ${p.tipoTransporte?.descripcion || '—'}
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot dot-green"></div>
                    <strong class="text-success">Recogida:</strong> ${p.recogidaEntrega?.fecha_recogida || '—'} – ${p.recogidaEntrega?.hora_recogida || '—'}
                    <div class="location-box">
                        <strong>Origen:</strong> ${envio.nombre_origen || '—'}<br>
                        ${Array.isArray(p.cargas) && p.cargas.length ? p.cargas.map(c => `
                            <div class="mt-1">• ${c.tipo || 'Producto'} - ${c.variedad || ''} (${Number(c.cantidad || 0)} uds, ${Number(c.peso || 0).toFixed(1)} kg)</div>
                        `).join('') : '<div class="text-muted mt-1">Sin productos</div>'}
                        <div class="mt-2 text-muted small">${p.recogidaEntrega?.instrucciones_recogida || 'Sin instrucciones'}</div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot dot-gray"></div>
                    <strong class="text-dark">Entrega:</strong> ${p.recogidaEntrega?.fecha_entrega || '—'} – ${p.recogidaEntrega?.hora_entrega || '—'}
                    <div class="location-box">
                        <strong>Destino:</strong> ${envio.nombre_destino || '—'}<br>
                        ${Array.isArray(p.cargas) && p.cargas.length ? p.cargas.map(c => `
                            <div class="mt-1">• ${c.tipo || 'Producto'} - ${c.variedad || ''} (${Number(c.cantidad || 0)} uds, ${Number(c.peso || 0).toFixed(1)} kg)</div>
                        `).join('') : '<div class="text-muted mt-1">Sin productos</div>'}
                        <div class="mt-2 text-muted small">${p.recogidaEntrega?.instrucciones_entrega || 'Sin instrucciones'}</div>
                    </div>
                </div>
            `;

            // Right Column (Map & Code)
            const colRight = document.createElement('div');
            colRight.className = 'col-lg-6';
            const mapId = `map-${idx}`;
            
            colRight.innerHTML = `
                <div id="${mapId}" class="leaflet-container border"></div>
                <div class="access-code-box">
                    <h6 class="mb-2 font-weight-bold text-dark">Código de Acceso</h6>
                    <div class="access-code-value">${p.codigo_acceso || 'No asignado'}</div>
                </div>
            `;

            row.appendChild(colLeft);
            row.appendChild(colRight);
            body.appendChild(row);
            card.appendChild(body);
            wrapper.appendChild(card);
        });
        
        cont.innerHTML = '';
        cont.appendChild(wrapper);

        // Init maps
        setTimeout(() => {
            particiones.forEach((p, idx) => {
                initMap(`map-${idx}`, envio, p);
            });
        }, 100);
    }

    function initMap(mapId, envio, p) {
        const element = document.getElementById(mapId);
        if (!element) return;
        
        // Wait for dimensions
        if (element.offsetWidth === 0 || element.offsetHeight === 0) {
            setTimeout(() => initMap(mapId, envio, p), 100);
            return;
        }
        
        try {
            if (element._leaflet_id) element._leaflet_id = null;

            const map = L.map(mapId, {
                renderer: L.canvas(),
                fadeAnimation: false,
                zoomAnimation: false,
                markerZoomAnimation: false
            });
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
                maxZoom: 19, 
                attribution: '&copy; OpenStreetMap' 
            }).addTo(map);
            
            map.invalidateSize();

            const o = [envio.coordenadas_origen?.lat, envio.coordenadas_origen?.lng];
            const d = [envio.coordenadas_destino?.lat, envio.coordenadas_destino?.lng];
            
            if (o[0] && o[1]) L.marker(o).addTo(map).bindPopup('Origen');
            if (d[0] && d[1]) L.marker(d).addTo(map).bindPopup('Destino');
            
            let routeCoordinates = [];
            let bounds = null;
            
            try {
                const rutaData = envio.rutaGeoJSON || envio.ruta_geojson;
                if (rutaData){
                    const gj = JSON.parse(rutaData);
                    const layer = L.geoJSON(gj, { style: { color: '#007bff', weight: 4 } }).addTo(map);
                    bounds = layer.getBounds();
                    
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
                } else if (o[0] && d[0]){
                    const line = L.polyline([o, d], { color:'#007bff', weight:4 }).addTo(map);
                    bounds = line.getBounds();
                    routeCoordinates = [o, d];
                }
            } catch (err) {
                console.error('Error al procesar ruta:', err);
            }

            const fitMap = () => {
                map.invalidateSize();
                if (bounds && bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                } else if (o[0] && o[1]) {
                    map.setView(o, 12);
                } else {
                    map.setView([-17.7833, -63.1833], 12);
                }
            };

            fitMap();
            
            // Animation logic
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
            
            setTimeout(fitMap, 500);
            setTimeout(fitMap, 1000);

        } catch (error) {
            console.error('Error al inicializar mapa:', error);
        }
    }

    async function obtenerEnvio(){
        const res = await fetch(`${API_URL}/api/public/envios/${envioId}/seguimiento`);
        if (!res.ok){
            throw new Error('No se pudo cargar el envío');
        }
        return res.json();
    }

    async function cargarEnvio(){
        cont.innerHTML = loaderHtml;
        try {
            const envio = await obtenerEnvio();
            console.log('📦 Datos del envío recibidos:', envio);
            console.log('📋 Particiones:', envio.particiones);
            if (envio.particiones && envio.particiones.length > 0) {
                console.log('🔍 Primera partición:', envio.particiones[0]);
            }
            renderEnvio(envio);
        } catch(e){
            console.error('❌ Error al cargar envío:', e);
            cont.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i><strong>Error:</strong> ${e.message}</div>`;
        }
    }

    cargarEnvio();
    
} // Fin de window.__envioShowPublicInitialized
</script>
@endpush
