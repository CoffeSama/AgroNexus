@extends('layouts.app')

@section('title', 'Seguimiento de Envíos')

@section('page_title', 'Gestión de Envíos')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Seguimiento Envío</li>
@endsection

@section('content')
<style>
    .filter-card { cursor: pointer; transition: all 0.3s; }
    .filter-card:hover { transform: translateY(-2px); }
    .filter-card.active { border: 2px solid #007bff; }
    .envio-card { cursor: pointer; transition: all 0.3s; }
    .envio-card:hover { transform: translateY(-5px); box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important; }
    .envio-route { border-left: 3px solid #dee2e6; padding-left: 1rem; }
    .text-truncate-2lines {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.4em;
        min-height: 2.8em;
        max-height: 2.8em;
    }
</style>

<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-2">
        <div class="info-box filter-card" data-filter="todos">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-clipboard-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Todos</span>
                <span class="info-box-number" id="statTodos">0</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
        <div class="info-box filter-card" data-filter="pendientes">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pendientes</span>
                <span class="info-box-number" id="statPendientes">0</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
        <div class="info-box filter-card" data-filter="asignados">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-file-signature"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Asignados</span>
                <span class="info-box-number" id="statAsignados">0</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
        <div class="info-box filter-card" data-filter="curso">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-truck"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">En curso</span>
                <span class="info-box-number" id="statCurso">0</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
        <div class="info-box filter-card" data-filter="parcial">
            <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-shipping-fast"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Parcialmente Entregado</span>
                <span class="info-box-number" id="statParcial">0</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
        <div class="info-box filter-card" data-filter="completados">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Completados</span>
                <span class="info-box-number" id="statCompletados">0</span>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Listado de envíos de clientes</h3>
        <div class="card-tools">
            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" id="inputBuscarEnvio" class="form-control" placeholder="Buscar envíos...">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="row p-3" id="envioGrid">
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-spinner fa-spin mr-2"></i>Cargando envíos...
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
    const API_URL = 'http://192.168.0.11:8000';
    
    const grid = document.getElementById('envioGrid');
    const searchInput = document.getElementById('inputBuscarEnvio');
    const statTodos = document.getElementById('statTodos');
    const statPendientes = document.getElementById('statPendientes');
    const statAsignados = document.getElementById('statAsignados');
    const statCurso = document.getElementById('statCurso');
    const statParcial = document.getElementById('statParcial');
    const statCompletados = document.getElementById('statCompletados');
    const filterCards = document.querySelectorAll('.filter-card');

    const STATUS_GROUPS = {
        pendientes: (estado) => ['pendiente', 'sin estado', 'sin asignar'].includes(estado),
        asignados: (estado) => ['asignado'].includes(estado),
        curso: (estado) => ['en curso'].includes(estado),
        parcial: (estado) => ['parcialmente entregado'].includes(estado),
        completados: (estado) => ['entregado', 'finalizado', 'completado'].includes(estado),
        todos: () => true
    };

    const STATUS_META = {
        'pendiente': { label: 'Pendiente', badge: 'badge-warning' },
        'sin estado': { label: 'Pendiente', badge: 'badge-warning' },
        'sin asignar': { label: 'Pendiente', badge: 'badge-warning' },
        'asignado': { label: 'Asignado', badge: 'badge-info' },
        'en curso': { label: 'En curso', badge: 'badge-primary' },
        'parcialmente entregado': { label: 'Parcialmente Entregado', badge: 'badge-orange' },
        'entregado': { label: 'Completado', badge: 'badge-success' },
        'finalizado': { label: 'Completado', badge: 'badge-success' },
        'completado': { label: 'Completado', badge: 'badge-success' },
    };

    let envios = [];
    let activeFilter = 'todos';
    let searchTerm = '';

    filterCards.forEach(card => {
        card.addEventListener('click', () => {
            const filter = card.getAttribute('data-filter');
            if (!filter || filter === activeFilter) return;
            activeFilter = filter;
            filterCards.forEach(c => c.classList.toggle('active', c.getAttribute('data-filter') === filter));
            renderGrid();
        });
    });

    searchInput.addEventListener('input', (event) => {
        searchTerm = event.target.value.trim().toLowerCase();
        renderGrid();
    });

    async function fetchEnvios() {
        grid.innerHTML = '<div class="col-12 text-center text-muted py-5"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando envíos...</div>';
        try {
            const res = await fetch(`${API_URL}/api/public/envios/all`);
            if (!res.ok) throw new Error('No se pudieron cargar los envíos');

            const data = await res.json();
            envios = Array.isArray(data) ? data : [];
            renderSummary();
            renderGrid();
        } catch (error) {
            console.error(error);
            grid.innerHTML = `<div class="col-12 text-center text-danger py-5"><i class="fas fa-exclamation-triangle mr-2"></i>${error.message}</div>`;
        }
    }

    function renderSummary() {
        const resumen = calcularResumen(envios);
        statTodos.textContent = resumen.todos;
        statPendientes.textContent = resumen.pendientes;
        statAsignados.textContent = resumen.asignados;
        statCurso.textContent = resumen.curso;
        statParcial.textContent = resumen.parcial;
        statCompletados.textContent = resumen.completados;
    }

    function calcularResumen(data) {
        const counts = { pendientes: 0, asignados: 0, curso: 0, parcial: 0, completados: 0, todos: data.length };
        data.forEach(envio => {
            const estado = normalizarEstado(envio.estado);
            if (STATUS_GROUPS.pendientes(estado)) counts.pendientes += 1;
            if (STATUS_GROUPS.asignados(estado)) counts.asignados += 1;
            if (STATUS_GROUPS.curso(estado)) counts.curso += 1;
            if (STATUS_GROUPS.parcial(estado)) counts.parcial += 1;
            if (STATUS_GROUPS.completados(estado)) counts.completados += 1;
        });
        return counts;
    }

    function renderGrid() {
        if (!envios.length) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-5"><i class="fas fa-inbox mr-2"></i>No hay envíos registrados.</div>';
            return;
        }
        const filtrados = envios
            .filter(envio => STATUS_GROUPS[activeFilter]?.(normalizarEstado(envio.estado)) ?? true)
            .filter(envio => coincideBusqueda(envio, searchTerm));

        if (!filtrados.length) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-5"><i class="fas fa-search mr-2"></i>No hay envíos que coincidan con el filtro.</div>';
            return;
        }

        grid.innerHTML = filtrados.map(envio => crearCard(envio)).join('');
    }

    function crearCard(envio) {
        const estado = normalizarEstado(envio.estado);
        const meta = STATUS_META[estado] || { label: envio.estado || 'Sin estado', badge: 'badge-secondary' };
        const fecha = formatearFecha(envio.fecha_creacion);
        const remitente = envio.nombre_remitente || 'Sin remitente';
        const urlDetalle = `{{ url('/envios') }}/${envio.id}`;

        return `
            <div class="col-xl-4 col-lg-6 mb-3">
                <div class="card card-outline card-primary envio-card" onclick="window.location.href='${urlDetalle}'">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <strong>#${envio.id}</strong>
                            <span class="badge ${meta.badge} float-right">${meta.label}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3"><i class="far fa-calendar mr-1"></i>${fecha}</p>
                        
                        <div class="mb-3 pb-3 envio-route">
                            <div class="mb-2">
                                <small class="text-muted text-uppercase">Recogida</small>
                                <div class="font-weight-bold text-truncate-2lines">${envio.direccion_origen || 'Sin origen'}</div>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase">Entrega</small>
                                <div class="font-weight-bold text-truncate-2lines">${envio.direccion_destino || 'Sin destino'}</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase">Remitente</small>
                                <div class="font-weight-bold">${remitente}</div>
                            </div>
                            <button class="btn btn-primary btn-sm" onclick="event.stopPropagation()">
                                <i class="fas fa-eye mr-1"></i>Ver
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    function normalizarEstado(estado) {
        return (estado || '').toString().trim().toLowerCase() || 'sin estado';
    }

    function formatearFecha(value) {
        if (!value) return 'Fecha no registrada';
        try {
            const date = new Date(value);
            if (Number.isNaN(date.getTime())) return value;
            const opciones = { weekday: 'short', day: 'numeric', month: 'short' };
            return date.toLocaleDateString('es-BO', opciones);
        } catch {
            return value;
        }
    }

    function coincideBusqueda(envio, termino) {
        if (!termino) return true;
        const texto = [
            `#${envio.id}`,
            envio.direccion_origen || '',
            envio.direccion_destino || '',
            envio.nombre_remitente || '',
            envio.estado || ''
        ].join(' ').toLowerCase();
        return texto.includes(termino);
    }

    // Cargar envíos al iniciar
    fetchEnvios();
})();
</script>
@endpush
