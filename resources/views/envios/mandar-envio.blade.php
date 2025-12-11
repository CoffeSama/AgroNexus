@extends('layouts.app')

@section('title', 'Crear Envío')

@section('page_title', 'Sistema de Envíos')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Mandar Envío</li>
@endsection

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .wizard-step { display: none; }
    .wizard-step.active { display: block; }
    #map { height: 100%; }
    .readonly-input { background-color: #f4f6f9; cursor: not-allowed; }
    .equal-height-row { display: flex; flex-wrap: wrap; }
    .equal-height-row > [class*='col-'] { display: flex; flex-direction: column; }
    .equal-height-row .card { flex: 1; }
    
    /* Estilos del indicador de conexión */
    #conexion-indicator {
        position: fixed;
        top: 70px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }
    
    .indicador-online {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .indicador-offline {
        background: linear-gradient(135deg, #dc3545, #e74a3b);
        color: white;
        animation: pulse-offline 2s infinite;
    }
    
    @keyframes pulse-offline {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .cola-pendientes-card {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>

<!-- Indicador de conexión -->
<div id="conexion-indicator" class="indicador-online">
    <i class="fas fa-wifi"></i>
    <span id="conexion-texto">Verificando conexión...</span>
    <span class="badge badge-light ml-2" id="pendientes-badge" style="display: none;">0 pendientes</span>
</div>

<!-- Alert de cola local si hay pendientes -->
<div id="cola-alert" class="cola-pendientes-card" style="display: none;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-clock text-warning mr-2"></i>
            <strong>Tienes envíos en cola local</strong>
            <p class="mb-0 small text-muted">Se sincronizarán automáticamente cuando la conexión esté disponible</p>
        </div>
        <button class="btn btn-warning btn-sm" onclick="ToleranciaFallos.sincronizarPendientes()">
            <i class="fas fa-sync-alt"></i> Sincronizar Ahora
        </button>
    </div>
</div>

<!-- Alert informativo -->
<div class="alert alert-info alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h5><i class="icon fas fa-info-circle"></i> ¡Bienvenido!</h5>
    Crea tu solicitud de envío en 3 simples pasos: <strong>Ubicación</strong>, <strong>Detalles del envío</strong> y <strong>Confirmación</strong>.
    <br><small class="text-muted"><i class="fas fa-shield-alt"></i> Sistema con tolerancia a fallos: tus envíos se guardarán localmente si no hay conexión.</small>
</div>

<!-- Progress Steps usando BS4 -->
<div class="card card-outline card-primary mb-3">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-4 step-indicator" data-step="1">
                <div class="mb-2">
                    <span class="step-badge badge badge-primary badge-lg" style="width: 50px; height: 50px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">1</span>
                </div>
                <h6 class="font-weight-bold">Ubicación</h6>
                <small class="text-muted d-none d-md-block">Origen y Destino</small>
            </div>
            <div class="col-md-4 step-indicator" data-step="2">
                <div class="mb-2">
                    <span class="step-badge badge badge-secondary badge-lg" style="width: 50px; height: 50px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">2</span>
                </div>
                <h6>Detalles</h6>
                <small class="text-muted d-none d-md-block">Cargas y Transporte</small>
            </div>
            <div class="col-md-4 step-indicator" data-step="3">
                <div class="mb-2">
                    <span class="step-badge badge badge-secondary badge-lg" style="width: 50px; height: 50px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">3</span>
                </div>
                <h6>Confirmación</h6>
                <small class="text-muted d-none d-md-block">Resumen y Envío</small>
            </div>
        </div>
    </div>
</div>

<!-- Wizard Content -->
<div class="wizard-content">
    
    <!-- STEP 1: UBICACIÓN -->
    <div class="wizard-step active" data-step="1">
        <div class="row equal-height-row">
            <!-- Formulario -->
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> Datos del Envío</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_remitente" placeholder="Ej: Juan Pérez" required>
                        </div>
                        <div class="form-group">
                            <label>Teléfono <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telefono_remitente" placeholder="Ej: 77123456" required>
                        </div>
                        <div class="form-group">
                            <label>Email <small class="text-muted">(opcional)</small></label>
                            <input type="email" class="form-control" id="email_remitente" placeholder="correo@example.com">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="text-success"><i class="fas fa-map-marker-alt"></i> Origen</label>
                            <input type="text" class="form-control readonly-input" id="txtNombreOrigen" readonly placeholder="Marca el origen en el mapa...">
                            <small id="txtOrigen" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label class="text-danger"><i class="fas fa-map-marker-alt"></i> Destino</label>
                            <input type="text" class="form-control readonly-input" id="txtNombreDestino" readonly placeholder="Marca el destino en el mapa...">
                            <small id="txtDestino" class="form-text text-muted"></small>
                        </div>
                        <div class="callout callout-info">
                            <p class="mb-0"><i class="fas fa-info-circle"></i> Haz clic en el mapa para marcar primero el <strong>Origen</strong> y luego el <strong>Destino</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="col-md-8">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-map"></i> Mapa Interactivo</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" id="btnResetMap">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0" style="flex: 1; display: flex;">
                        <div id="map" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 2: PARTICIONES -->
    <div class="wizard-step" data-step="2">
        <div id="particionesContainer"></div>
        <div class="text-center mt-3">
            <button type="button" class="btn btn-outline-primary btn-lg" id="btnAgregarParticion">
                <i class="fas fa-plus-circle"></i> Agregar otro camión / partición
            </button>
        </div>
    </div>

    <!-- STEP 3: CONFIRMACIÓN -->
    <div class="wizard-step" data-step="3">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-check-circle"></i> Resumen de la Solicitud</h3>
            </div>
            <div class="card-body">
                <div id="alertContainer"></div>

                <!-- Datos Remitente -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Datos del Remitente</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Nombre</span>
                                <span class="info-box-number" id="resNombre">--</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info"><i class="fas fa-phone"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Teléfono</span>
                                <span class="info-box-number" id="resTelefono">--</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info"><i class="fas fa-envelope"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Email</span>
                                <span class="info-box-number" style="font-size: 0.9rem;" id="resEmail">--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ruta -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Ruta del Envío</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="callout callout-success">
                            <h6><i class="fas fa-map-marker-alt"></i> Origen</h6>
                            <p id="resOrigen" class="mb-0">--</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="callout callout-danger">
                            <h6><i class="fas fa-map-marker-alt"></i> Destino</h6>
                            <p id="resDestino" class="mb-0">--</p>
                        </div>
                    </div>
                </div>

                <!-- Particiones -->
                <h5 class="text-primary border-bottom pb-2 mb-3">Detalle de Envíos / Particiones</h5>
                <div id="resumenParticiones"></div>
            </div>
        </div>
    </div>

</div>

<!-- Botones de navegación -->
<div class="row mt-4">
    <div class="col-6">
        <button type="button" class="btn btn-default" id="btnPrev" disabled>
            <i class="fas fa-arrow-left"></i> Anterior
        </button>
    </div>
    <div class="col-6 text-right">
        <button type="button" class="btn btn-primary" id="btnNext">
            Siguiente <i class="fas fa-arrow-right"></i>
        </button>
        <button type="button" class="btn btn-success" id="btnFinish" style="display: none;">
            <i class="fas fa-check"></i> Confirmar y Crear Envío
        </button>
    </div>
</div>

<!-- Template Partición -->
<template id="tplParticion">
    <div class="card card-outline card-primary mb-3" data-index="{index}">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-truck"></i> Envío / Camión #<span class="num">1</span>
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-danger" onclick="removeParticion(this)">
                    <i class="fas fa-trash"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Tipo de Transporte <span class="text-danger">*</span></label>
                <select class="form-control js-tipo-transporte" required>
                    <option value="">Seleccione...</option>
                </select>
                <small class="text-muted js-transporte-offline" style="display: none;">
                    <i class="fas fa-info-circle"></i> Tipos de transporte cargados desde cache local
                </small>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Fecha Recogida <span class="text-danger">*</span></label>
                        <input type="date" class="form-control js-fecha-recogida" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hora Recogida <span class="text-danger">*</span></label>
                        <input type="time" class="form-control js-hora-recogida" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hora Entrega <span class="text-danger">*</span></label>
                        <input type="time" class="form-control js-hora-entrega" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Instrucciones de Recogida</label>
                        <textarea class="form-control js-instr-recogida" rows="2" placeholder="Ej: Puerta trasera..."></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Instrucciones de Entrega</label>
                        <textarea class="form-control js-instr-entrega" rows="2" placeholder="Ej: Dejar en recepción..."></textarea>
                    </div>
                </div>
            </div>
            <h6 class="text-primary mt-3"><i class="fas fa-boxes"></i> Cargas / Productos</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Variedad</th>
                            <th>Empaque</th>
                            <th width="100">Cantidad</th>
                            <th width="100">Peso (kg)</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody class="cargas-container"></tbody>
                </table>
            </div>
            <button type="button" class="btn btn-success btn-sm" onclick="addCarga(this)">
                <i class="fas fa-plus"></i> Agregar Producto
            </button>
        </div>
    </div>
</template>

<!-- Template Carga -->
<template id="tplCarga">
    <tr class="carga-item">
        <td>
            <select class="form-control form-control-sm js-carga-tipo" required>
                <option value="">Seleccione...</option>
                <option value="Frutas">Frutas</option>
                <option value="Verduras">Verduras</option>
                <option value="Cereales">Cereales</option>
                <option value="Lacteos">Lácteos</option>
                <option value="Carnes">Carnes</option>
                <option value="Otros">Otros</option>
            </select>
        </td>
        <td><input type="text" class="form-control form-control-sm js-carga-variedad" required placeholder="Ej: Manzana"></td>
        <td><input type="text" class="form-control form-control-sm js-carga-empaque" required placeholder="Ej: Cajas"></td>
        <td><input type="number" class="form-control form-control-sm js-carga-cantidad" required min="1" placeholder="100"></td>
        <td><input type="number" class="form-control form-control-sm js-carga-peso" required min="0.01" step="0.01" placeholder="500"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-xs" onclick="removeCarga(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // URL de la API - Configurable desde .env
    const API_URL = '{{ env("ORGTRACK_API_URL", "http://192.168.0.11:8000") }}';
    const ORS_KEY = '5b3ce3597851110001cf6248dbff311ed4d34185911c2eb9e6c50080';

    // ========================================
    // SISTEMA DE TOLERANCIA A FALLOS
    // ========================================
    const ToleranciaFallos = {
        conectado: true,
        ultimaVerificacion: null,
        colaLocal: [],

        init: function() {
            this.cargarColaLocal();
            this.verificarConexion();
            setInterval(() => this.verificarConexion(), 30000);
        },

        verificarConexion: async function() {
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 5000);
                
                const response = await fetch(`${API_URL}/api/tipo-transporte`, {
                    method: 'GET',
                    signal: controller.signal
                });
                
                clearTimeout(timeoutId);
                this.conectado = response.ok;
                
                if (this.conectado && this.colaLocal.length > 0) {
                    this.sincronizarPendientes();
                }
            } catch (error) {
                console.warn('API no disponible:', error.message);
                this.conectado = false;
            }
            
            this.actualizarIndicador();
            return this.conectado;
        },

        actualizarIndicador: function() {
            const indicator = document.getElementById('conexion-indicator');
            const texto = document.getElementById('conexion-texto');
            const badge = document.getElementById('pendientes-badge');
            const colaAlert = document.getElementById('cola-alert');
            
            if (this.conectado) {
                indicator.className = 'indicador-online';
                indicator.querySelector('i').className = 'fas fa-wifi';
                texto.textContent = 'Conectado';
            } else {
                indicator.className = 'indicador-offline';
                indicator.querySelector('i').className = 'fas fa-wifi-slash';
                texto.textContent = 'Sin Conexión - Modo Offline';
            }
            
            if (this.colaLocal.length > 0) {
                badge.style.display = 'inline';
                badge.textContent = `${this.colaLocal.length} pendientes`;
                colaAlert.style.display = 'block';
            } else {
                badge.style.display = 'none';
                colaAlert.style.display = 'none';
            }
        },

        guardarEnCola: function(datos) {
            const envio = {
                id: Date.now(),
                datos: datos,
                fecha: new Date().toISOString(),
                intentos: 0,
                estado: 'pendiente'
            };
            
            this.colaLocal.push(envio);
            localStorage.setItem('agronexus_envios_pendientes', JSON.stringify(this.colaLocal));
            this.actualizarIndicador();
            
            return envio;
        },

        cargarColaLocal: function() {
            try {
                const stored = localStorage.getItem('agronexus_envios_pendientes');
                this.colaLocal = stored ? JSON.parse(stored) : [];
            } catch (e) {
                this.colaLocal = [];
            }
        },

        sincronizarPendientes: async function() {
            if (!this.conectado || this.colaLocal.length === 0) return;

            Swal.fire({
                title: 'Sincronizando...',
                text: `Procesando ${this.colaLocal.length} envío(s) pendiente(s)`,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            let sincronizados = 0;
            const pendientes = [...this.colaLocal];

            for (const envio of pendientes) {
                if (envio.estado === 'enviado') continue;
                
                try {
                    const resDireccion = await fetch(`${API_URL}/api/public/direccion`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(envio.datos.direccion)
                    });

                    if (!resDireccion.ok) continue;

                    const { id_direccion } = await resDireccion.json();
                    envio.datos.envio.id_direccion = id_direccion;

                    const resEnvio = await fetch(`${API_URL}/api/public/envios`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(envio.datos.envio)
                    });

                    if (resEnvio.ok) {
                        envio.estado = 'enviado';
                        sincronizados++;
                    }
                } catch (error) {
                    console.error('Error sincronizando:', error);
                }
            }

            this.colaLocal = this.colaLocal.filter(e => e.estado !== 'enviado');
            localStorage.setItem('agronexus_envios_pendientes', JSON.stringify(this.colaLocal));
            this.actualizarIndicador();

            Swal.fire({
                icon: sincronizados > 0 ? 'success' : 'warning',
                title: sincronizados > 0 ? '¡Sincronización exitosa!' : 'Sin cambios',
                text: `${sincronizados} envío(s) sincronizado(s)`,
                timer: 3000
            });
        }
    };

    // ========================================
    // ESTADO Y VARIABLES GLOBALES
    // ========================================
    const state = {
        currentStep: 1,
        map: null,
        markers: { origin: null, destination: null },
        routeLayer: null,
        originCoords: null,
        destinationCoords: null,
        geoJSON: null,
        tiposTransporte: []
    };

    let partitionCounter = 0;

    // ========================================
    // INICIALIZACIÓN
    // ========================================
    document.addEventListener('DOMContentLoaded', async () => {
        ToleranciaFallos.init();
        initMap();
        await loadTiposTransporte();
        addPartition();
        setupEventListeners();
        setMinDate();
    });

    function setMinDate() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('.js-fecha-recogida').forEach(input => {
            input.min = today;
        });
    }

    function setupEventListeners() {
        document.getElementById('btnNext').addEventListener('click', nextStep);
        document.getElementById('btnPrev').addEventListener('click', prevStep);
        document.getElementById('btnFinish').addEventListener('click', submitForm);
        document.getElementById('btnResetMap').addEventListener('click', resetMap);
        document.getElementById('btnAgregarParticion').addEventListener('click', addPartition);
    }

    // ========================================
    // MAPA
    // ========================================
    function initMap() {
        state.map = L.map('map').setView([-17.3935, -66.1570], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(state.map);
        state.map.on('click', onMapClick);
    }

    async function onMapClick(e) {
        const { lat, lng } = e.latlng;

        if (!state.markers.origin) {
            state.markers.origin = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: '<i class="fas fa-map-marker-alt" style="color: #28a745; font-size: 32px;"></i>',
                    className: 'custom-marker',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                })
            }).addTo(state.map);

            state.originCoords = { lat, lng };
            document.getElementById('txtOrigen').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            const address = await reverseGeocode(lat, lng);
            document.getElementById('txtNombreOrigen').value = address;

        } else if (!state.markers.destination) {
            state.markers.destination = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: '<i class="fas fa-map-marker-alt" style="color: #dc3545; font-size: 32px;"></i>',
                    className: 'custom-marker',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                })
            }).addTo(state.map);

            state.destinationCoords = { lat, lng };
            document.getElementById('txtDestino').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            const address = await reverseGeocode(lat, lng);
            document.getElementById('txtNombreDestino').value = address;
            await drawRoute();
        }
    }

    async function reverseGeocode(lat, lng) {
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
            const data = await res.json();
            return data.display_name || `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        } catch (e) {
            return `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        }
    }

    async function drawRoute() {
        const { origin, destination } = state.markers;
        if (!origin || !destination) return;

        const start = origin.getLatLng();
        const end = destination.getLatLng();

        try {
            const url = `https://api.openrouteservice.org/v2/directions/driving-car?api_key=${ORS_KEY}&start=${start.lng},${start.lat}&end=${end.lng},${end.lat}`;
            const res = await fetch(url);
            const data = await res.json();

            if (data.features && data.features.length > 0) {
                const geoJSON = data.features[0];
                if (state.routeLayer) state.map.removeLayer(state.routeLayer);
                state.routeLayer = L.geoJSON(geoJSON, {
                    style: { color: '#3b82f6', weight: 5, opacity: 0.8 }
                }).addTo(state.map);
                state.map.fitBounds(state.routeLayer.getBounds(), { padding: [50, 50] });
                state.geoJSON = JSON.stringify({ type: "FeatureCollection", features: [geoJSON] });
            }
        } catch (e) {
            const line = [[start.lat, start.lng], [end.lat, end.lng]];
            if (state.routeLayer) state.map.removeLayer(state.routeLayer);
            state.routeLayer = L.polyline(line, { color: 'red', weight: 4, dashArray: '10, 10' }).addTo(state.map);
            state.map.fitBounds(state.routeLayer.getBounds(), { padding: [50, 50] });
            state.geoJSON = JSON.stringify({
                type: "FeatureCollection",
                features: [{ type: "Feature", geometry: { type: "LineString", coordinates: [[start.lng, start.lat], [end.lng, end.lat]] }}]
            });
        }
    }

    function resetMap() {
        if (state.markers.origin) state.map.removeLayer(state.markers.origin);
        if (state.markers.destination) state.map.removeLayer(state.markers.destination);
        if (state.routeLayer) state.map.removeLayer(state.routeLayer);
        state.markers = { origin: null, destination: null };
        state.routeLayer = null;
        state.originCoords = null;
        state.destinationCoords = null;
        state.geoJSON = null;
        document.getElementById('txtOrigen').textContent = '';
        document.getElementById('txtDestino').textContent = '';
        document.getElementById('txtNombreOrigen').value = '';
        document.getElementById('txtNombreDestino').value = '';
    }

    // ========================================
    // TIPOS DE TRANSPORTE (con cache local)
    // ========================================
    async function loadTiposTransporte() {
        // Intentar cargar desde cache primero
        const cached = localStorage.getItem('tipos_transporte_cache');
        if (cached) {
            state.tiposTransporte = JSON.parse(cached);
        }

        try {
            const res = await fetch(`${API_URL}/api/tipo-transporte`);
            if (res.ok) {
                state.tiposTransporte = await res.json();
                // Guardar en cache
                localStorage.setItem('tipos_transporte_cache', JSON.stringify(state.tiposTransporte));
            }
        } catch (e) {
            console.warn('Error cargando tipos de transporte, usando cache:', e);
        }

        // Si no hay datos, mostrar mensaje
        if (state.tiposTransporte.length === 0) {
            console.warn('No hay tipos de transporte disponibles');
        }
    }

    // ========================================
    // PARTICIONES
    // ========================================
    function addPartition() {
        partitionCounter++;
        const template = document.getElementById('tplParticion');
        const clone = template.content.cloneNode(true);
        const card = clone.querySelector('.card');
        card.dataset.index = partitionCounter;
        card.querySelector('.num').textContent = partitionCounter;

        const select = clone.querySelector('.js-tipo-transporte');
        const offlineMsg = clone.querySelector('.js-transporte-offline');
        
        state.tiposTransporte.forEach(tipo => {
            const option = document.createElement('option');
            option.value = tipo.id;
            option.textContent = tipo.nombre;
            select.appendChild(option);
        });

        // Mostrar mensaje si estamos en modo offline
        if (!ToleranciaFallos.conectado && state.tiposTransporte.length > 0) {
            offlineMsg.style.display = 'block';
        }

        const today = new Date().toISOString().split('T')[0];
        clone.querySelector('.js-fecha-recogida').value = today;
        clone.querySelector('.js-fecha-recogida').min = today;

        document.getElementById('particionesContainer').appendChild(clone);
        const addedCard = document.querySelector(`.card[data-index="${partitionCounter}"]`);
        addCarga(addedCard.querySelector('.btn-success'));
    }

    function removeParticion(btn) {
        btn.closest('.card').remove();
        renumberParticiones();
    }

    function renumberParticiones() {
        document.querySelectorAll('#particionesContainer .card').forEach((card, idx) => {
            card.querySelector('.num').textContent = idx + 1;
        });
    }

    function addCarga(btn) {
        const card = btn.closest('.card');
        const container = card.querySelector('.cargas-container');
        const template = document.getElementById('tplCarga');
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    }

    function removeCarga(btn) {
        btn.closest('.carga-item').remove();
    }

    // ========================================
    // NAVEGACIÓN WIZARD
    // ========================================
    function nextStep() {
        if (validateCurrentStep()) {
            goToStep(state.currentStep + 1);
        }
    }

    function prevStep() {
        goToStep(state.currentStep - 1);
    }

    function goToStep(step) {
        document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
        document.querySelector(`.wizard-step[data-step="${step}"]`).classList.add('active');

        document.querySelectorAll('.step-indicator').forEach(ind => {
            const badge = ind.querySelector('.step-badge');
            const stepNum = parseInt(ind.dataset.step);
            const h6 = ind.querySelector('h6');
            
            if (stepNum === step) {
                badge.classList.remove('badge-secondary', 'badge-success');
                badge.classList.add('badge-primary');
                h6.classList.add('font-weight-bold', 'text-primary');
            } else if (stepNum < step) {
                badge.classList.remove('badge-secondary', 'badge-primary');
                badge.classList.add('badge-success');
                h6.classList.remove('font-weight-bold', 'text-primary');
            } else {
                badge.classList.remove('badge-primary', 'badge-success');
                badge.classList.add('badge-secondary');
                h6.classList.remove('font-weight-bold', 'text-primary');
            }
        });

        state.currentStep = step;
        document.getElementById('btnPrev').disabled = step === 1;

        if (step === 3) {
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('btnFinish').style.display = 'inline-block';
            renderSummary();
        } else {
            document.getElementById('btnNext').style.display = 'inline-block';
            document.getElementById('btnFinish').style.display = 'none';
        }

        if (step === 1 && state.map) {
            setTimeout(() => state.map.invalidateSize(), 200);
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateCurrentStep() {
        if (state.currentStep === 1) {
            const nombre = document.getElementById('nombre_remitente').value.trim();
            const telefono = document.getElementById('telefono_remitente').value.trim();
            if (!nombre || !telefono) {
                Swal.fire('Campos requeridos', 'Por favor completa tu nombre y teléfono.', 'warning');
                return false;
            }
            if (!state.markers.origin || !state.markers.destination) {
                Swal.fire('Ubicación requerida', 'Por favor marca el origen y destino en el mapa.', 'warning');
                return false;
            }
            return true;
        }

        if (state.currentStep === 2) {
            const cards = document.querySelectorAll('#particionesContainer .card');
            if (cards.length === 0) {
                Swal.fire('Sin envíos', 'Debes agregar al menos un envío/camión.', 'warning');
                return false;
            }
            let isValid = true;
            cards.forEach((card, idx) => {
                const inputs = card.querySelectorAll('input[required], select[required]');
                inputs.forEach(input => {
                    if (!input.value) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                const cargas = card.querySelectorAll('.carga-item');
                if (cargas.length === 0) {
                    Swal.fire('Sin productos', `El envío #${idx + 1} debe tener al menos un producto/carga.`, 'warning');
                    isValid = false;
                }
            });
            if (!isValid) Swal.fire('Campos incompletos', 'Por favor completa todos los campos obligatorios.', 'warning');
            return isValid;
        }

        return true;
    }

    function renderSummary() {
        document.getElementById('resNombre').textContent = document.getElementById('nombre_remitente').value;
        document.getElementById('resTelefono').textContent = document.getElementById('telefono_remitente').value;
        document.getElementById('resEmail').textContent = document.getElementById('email_remitente').value || 'No proporcionado';
        document.getElementById('resOrigen').textContent = document.getElementById('txtNombreOrigen').value;
        document.getElementById('resDestino').textContent = document.getElementById('txtNombreDestino').value;

        const container = document.getElementById('resumenParticiones');
        container.innerHTML = '';

        document.querySelectorAll('#particionesContainer .card').forEach((card, idx) => {
            const tipoTransporte = card.querySelector('.js-tipo-transporte');
            const fecha = card.querySelector('.js-fecha-recogida').value;
            const horaRecogida = card.querySelector('.js-hora-recogida').value;
            const horaEntrega = card.querySelector('.js-hora-entrega').value;

            let cargasHTML = '<div class="table-responsive"><table class="table table-sm table-bordered mt-2"><thead class="thead-light"><tr><th>Producto</th><th>Variedad</th><th>Cantidad</th><th>Peso</th></tr></thead><tbody>';
            card.querySelectorAll('.carga-item').forEach(carga => {
                const tipo = carga.querySelector('.js-carga-tipo').value;
                const variedad = carga.querySelector('.js-carga-variedad').value;
                const cantidad = carga.querySelector('.js-carga-cantidad').value;
                const peso = carga.querySelector('.js-carga-peso').value;
                cargasHTML += `<tr><td>${tipo}</td><td>${variedad}</td><td>${cantidad}</td><td>${peso} kg</td></tr>`;
            });
            cargasHTML += '</tbody></table></div>';

            container.innerHTML += `
                <div class="card card-outline card-info mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-truck"></i> Envío #${idx + 1}</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Tipo de Transporte:</dt>
                            <dd class="col-sm-8">${tipoTransporte.selectedOptions[0]?.text || 'No seleccionado'}</dd>
                            <dt class="col-sm-4">Fecha Recogida:</dt>
                            <dd class="col-sm-8">${fecha}</dd>
                            <dt class="col-sm-4">Hora Recogida:</dt>
                            <dd class="col-sm-8">${horaRecogida}</dd>
                            <dt class="col-sm-4">Hora Entrega:</dt>
                            <dd class="col-sm-8">${horaEntrega}</dd>
                        </dl>
                        <h6 class="mt-3 text-primary">Productos:</h6>
                        ${cargasHTML}
                    </div>
                </div>
            `;
        });
    }

    // ========================================
    // ENVÍO CON TOLERANCIA A FALLOS
    // ========================================
    async function submitForm() {
        const alertContainer = document.getElementById('alertContainer');
        
        // Preparar datos de dirección
        const direccionData = {
            nombreorigen: document.getElementById('txtNombreOrigen').value,
            nombredestino: document.getElementById('txtNombreDestino').value,
            origen_lat: state.originCoords.lat,
            origen_lng: state.originCoords.lng,
            destino_lat: state.destinationCoords.lat,
            destino_lng: state.destinationCoords.lng,
            rutageojson: state.geoJSON
        };

        // Preparar particiones
        const particiones = [];
        document.querySelectorAll('#particionesContainer .card').forEach(card => {
            const cargas = [];
            card.querySelectorAll('.carga-item').forEach(carga => {
                cargas.push({
                    tipo: carga.querySelector('.js-carga-tipo').value,
                    variedad: carga.querySelector('.js-carga-variedad').value,
                    cantidad: parseFloat(carga.querySelector('.js-carga-cantidad').value),
                    peso: parseFloat(carga.querySelector('.js-carga-peso').value),
                    empaquetado: carga.querySelector('.js-carga-empaque').value
                });
            });

            particiones.push({
                id_tipo_transporte: parseInt(card.querySelector('.js-tipo-transporte').value),
                cargas: cargas,
                recogidaEntrega: {
                    fecha_recogida: card.querySelector('.js-fecha-recogida').value,
                    hora_recogida: card.querySelector('.js-hora-recogida').value,
                    hora_entrega: card.querySelector('.js-hora-entrega').value,
                    instrucciones_recogida: card.querySelector('.js-instr-recogida').value || null,
                    instrucciones_entrega: card.querySelector('.js-instr-entrega').value || null
                }
            });
        });

        const envioData = {
            nombre_remitente: document.getElementById('nombre_remitente').value,
            telefono_remitente: document.getElementById('telefono_remitente').value,
            email_remitente: document.getElementById('email_remitente').value || null,
            particiones: particiones
        };

        // ========================================
        // INTENTAR ENVIAR O GUARDAR EN COLA
        // ========================================
        if (ToleranciaFallos.conectado) {
            alertContainer.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Creando envío...</div>';

            try {
                // Crear dirección
                const resDireccion = await fetch(`${API_URL}/api/public/direccion`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(direccionData)
                });

                if (!resDireccion.ok) throw new Error('Error creando dirección');
                const { id_direccion } = await resDireccion.json();

                // Crear envío
                envioData.id_direccion = id_direccion;
                const resEnvio = await fetch(`${API_URL}/api/public/envios`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(envioData)
                });

                const contentType = resEnvio.headers.get('content-type');
                let result;
                
                if (contentType && contentType.includes('application/json')) {
                    result = await resEnvio.json();
                } else {
                    throw new Error('Respuesta inválida del servidor');
                }

                if (resEnvio.ok) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h5><i class="icon fas fa-check"></i> ¡Envío creado exitosamente!</h5>
                            ID del Envío: <strong>#${result.id_envio}</strong><br>
                            Tu solicitud ha sido registrada en el servidor.
                        </div>
                    `;
                    document.getElementById('btnFinish').disabled = true;
                    document.getElementById('btnFinish').innerHTML = '<i class="fas fa-check"></i> Envío Creado';
                    return;
                } else {
                    throw new Error(result.error || result.message || 'Error desconocido');
                }

            } catch (error) {
                console.error('Error al enviar, guardando en cola local:', error);
                ToleranciaFallos.conectado = false;
                ToleranciaFallos.actualizarIndicador();
            }
        }

        // ========================================
        // MODO OFFLINE - GUARDAR EN COLA LOCAL
        // ========================================
        const envioLocal = ToleranciaFallos.guardarEnCola({
            direccion: direccionData,
            envio: envioData
        });

        alertContainer.innerHTML = `
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fas fa-cloud-upload-alt"></i> Envío guardado localmente</h5>
                <strong>Sin conexión con el servidor.</strong><br>
                Tu envío ha sido guardado con ID local: <strong>#${envioLocal.id}</strong><br>
                <small>Se sincronizará automáticamente cuando la conexión esté disponible.</small>
            </div>
        `;
        
        document.getElementById('btnFinish').disabled = true;
        document.getElementById('btnFinish').innerHTML = '<i class="fas fa-cloud"></i> Guardado Localmente';
        
        Swal.fire({
            icon: 'info',
            title: 'Modo Offline',
            html: `
                <p>Tu envío se ha guardado localmente porque no hay conexión con el servidor.</p>
                <p><strong>Se sincronizará automáticamente</strong> cuando la conexión esté disponible.</p>
            `,
            confirmButtonText: 'Entendido'
        });
    }
</script>
@endpush