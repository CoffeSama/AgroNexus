@extends('layouts.app')

@section('title', 'Registrar Cosecha | AgroNexus')
@section('page_title', 'Registrar Cosecha')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #2c5530;">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('producciones.index') }}" style="color: #2c5530;">Producciones</a></li>
    <li class="breadcrumb-item active">Nueva Cosecha</li>
@endsection

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #2c5530, #4a7c59);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem;
    }
    
    .form-control {
        border-radius: 8px;
        border: 2px solid #dee2e6;
        padding: 12px 15px;
        height: auto;
        min-height: 46px;
        font-size: 0.95rem;
    }
    .form-control:focus {
        border-color: #2c5530;
        box-shadow: 0 0 0 0.2rem rgba(44,85,48,0.15);
    }
    select.form-control {
        padding-right: 35px;
    }

    /* Sección de almacenamiento */
    .almacen-section {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 20px;
        border: 2px dashed #6c757d;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    .almacen-section.active {
        border-color: #28a745;
        border-style: solid;
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
    }

    .almacen-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        border: 2px solid #dee2e6;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .almacen-card:hover {
        border-color: #28a745;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
    }
    .almacen-card.selected {
        border-color: #28a745;
        background: #d4edda;
    }
    .almacen-card .almacen-icon {
        font-size: 1.8rem;
        color: #6c757d;
        width: 45px;
    }
    .almacen-card.selected .almacen-icon {
        color: #28a745;
    }
    .almacen-card .almacen-nombre {
        font-weight: 600;
        color: #1a252f;
    }
    .almacen-card .almacen-tipo {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .capacidad-bar {
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 8px;
    }
    .capacidad-bar .fill {
        height: 100%;
        border-radius: 3px;
    }
    .capacidad-bar .fill.low { background: #28a745; }
    .capacidad-bar .fill.medium { background: #ffc107; }
    .capacidad-bar .fill.high { background: #dc3545; }

    .info-panel {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid #2c5530;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card form-card">
            <div class="card-header">
                <h3 class="card-title mb-0"><i class="fas fa-tractor mr-2"></i>Registrar Cosecha</h3>
            </div>

            @if($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('producciones.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    
                    {{-- Lote --}}
                    <div class="form-group">
                        <label><i class="fas fa-map-marked-alt mr-1 text-success"></i> Lote a Cosechar <span class="text-danger">*</span></label>
                        <select name="loteid" id="loteid" class="form-control" required>
                            <option value="">-- Seleccione un lote en producción --</option>
                            @foreach($lotes as $l)
                                <option value="{{ $l->loteid }}"
                                        data-responsable="{{ $l->usuario->nombre ?? '' }} {{ $l->usuario->apellido ?? '' }}"
                                        data-cultivo="{{ $l->cultivo->nombre ?? 'Sin cultivo' }}"
                                        data-superficie="{{ $l->superficie }}">
                                    {{ $l->nombre }} - {{ $l->cultivo->nombre ?? 'Sin cultivo' }} ({{ $l->superficie }} ha)
                                </option>
                            @endforeach
                        </select>
                        @if($lotes->isEmpty())
                            <small class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle"></i> No hay lotes en estado "en producción"
                            </small>
                        @else
                            <small class="form-text text-muted">Solo lotes en estado "en producción"</small>
                        @endif
                    </div>

                    <div id="loteInfo" class="info-panel mb-3" style="display: none;">
                        <strong><i class="fas fa-leaf mr-1 text-success"></i> Cultivo:</strong> <span id="infoCultivo"></span>
                        <span class="mx-2">|</span>
                        <strong><i class="fas fa-user mr-1"></i> Responsable:</strong> <span id="infoResponsable"></span>
                    </div>

                    {{-- Cantidad --}}
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><i class="fas fa-balance-scale mr-1 text-success"></i> Cantidad Cosechada <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="cantidad" id="cantidad"
                                       class="form-control" min="0.01" required value="{{ old('cantidad') }}"
                                       placeholder="Ej: 500">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Unidad <span class="text-danger">*</span></label>
                                <select name="unidadmedidaid" id="unidadmedidaid" class="form-control" required>
                                    @foreach($unidades as $u)
                                        <option value="{{ $u->unidadmedidaid }}" 
                                                data-abrev="{{ $u->abreviatura }}"
                                                {{ $u->abreviatura == 'kg' ? 'selected' : '' }}>
                                            {{ $u->abreviatura }} ({{ $u->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Sección de Almacenamiento --}}
                    <div class="almacen-section" id="almacenSection">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fas fa-warehouse mr-2"></i>Enviar a Almacén</h6>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="enviarAlmacen" name="enviar_almacen" value="1">
                                <label class="custom-control-label" for="enviarAlmacen">Almacenar cosecha</label>
                            </div>
                        </div>

                        <div id="almacenOptions" style="display: none;">
                            <p class="text-muted small mb-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                Seleccione el almacén o silo donde guardar la producción
                            </p>

                            <div class="row" id="almacenesContainer">
                                @forelse($almacenes as $almacen)
                                    @php
                                        $usado = $almacen->almacenamientos->whereNull('fechasalida')->sum('cantidad');
                                        $disponible = $almacen->capacidad - $usado;
                                        $porcentaje = $almacen->capacidad > 0 ? ($usado / $almacen->capacidad) * 100 : 0;
                                        $fillClass = $porcentaje < 50 ? 'low' : ($porcentaje < 80 ? 'medium' : 'high');
                                    @endphp
                                    <div class="col-md-6 mb-2">
                                        <div class="almacen-card" data-id="{{ $almacen->almacenid }}" data-disponible="{{ $disponible }}" data-nombre="{{ $almacen->nombre }}" data-um-almacen="{{ $almacen->unidadMedida->abreviatura }}">>
                                            <div class="d-flex align-items-start">
                                                <div class="almacen-icon mr-2 text-center">
                                                    @if(str_contains(strtolower($almacen->tipoAlmacen->nombre ?? ''), 'silo'))
                                                        <i class="fas fa-database"></i>
                                                    @elseif(str_contains(strtolower($almacen->tipoAlmacen->nombre ?? ''), 'bodega'))
                                                        <i class="fas fa-warehouse"></i>
                                                    @elseif(str_contains(strtolower($almacen->tipoAlmacen->nombre ?? ''), 'fría') || str_contains(strtolower($almacen->tipoAlmacen->nombre ?? ''), 'frio'))
                                                        <i class="fas fa-snowflake"></i>
                                                    @else
                                                        <i class="fas fa-box"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="almacen-nombre">{{ $almacen->nombre }}</div>
                                                    <div class="almacen-tipo">
                                                        {{ $almacen->tipoAlmacen->nombre ?? 'General' }}
                                                        @if($almacen->ubicacion)
                                                            • {{ $almacen->ubicacion }}
                                                        @endif
                                                    </div>
                                                    <div class="small mt-1">
                                                        <span class="text-success font-weight-bold">{{ number_format($disponible, 0) }}</span>
                                                        <span class="text-muted">/ {{ number_format($almacen->capacidad, 0) }} {{ $almacen->unidadMedida->abreviatura ?? 'kg' }}</span>
                                                    </div>
                                                    <div class="capacidad-bar">
                                                        <div class="fill {{ $fillClass }}" style="width: {{ min($porcentaje, 100) }}%"></div>
                                                    </div>
                                                </div>
                                                <div class="ml-2">
                                                    <i class="fas fa-check-circle text-success fa-lg" style="display: none;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            No hay almacenes registrados. 
                                            <a href="{{ route('almacenes.create') }}">Crear uno</a>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <input type="hidden" name="almacenid" id="almacenid" value="">
                        </div>
                    </div>

                    {{-- Observaciones --}}
                    <div class="form-group mt-4">
                        <label><i class="fas fa-comment mr-1"></i> Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2" 
                                  placeholder="Calidad, condiciones de la cosecha, etc...">{{ old('observaciones') }}</textarea>
                    </div>

                </div>

                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('producciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg" {{ $lotes->isEmpty() ? 'disabled' : '' }}>
                            <i class="fas fa-save mr-1"></i> Registrar Cosecha
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Panel lateral --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-info-circle mr-1 text-success"></i> Información</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">
                    <i class="fas fa-calendar mr-1"></i> <strong>Fecha:</strong> Se registra automáticamente (hoy)
                </p>
                <p class="small text-muted mb-0" id="infoAlmacenSelected">
                    <i class="fas fa-warehouse mr-1"></i> <strong>Almacén:</strong> No seleccionado
                </p>
            </div>
        </div>

        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-check-circle mr-1"></i> Acciones Automáticas</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-1"></i>
                        Registrar producción
                    </li>
                    <li class="mb-2" id="actionAlmacen" style="display: none;">
                        <i class="fas fa-check text-success mr-1"></i>
                        Enviar al almacén seleccionado
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success mr-1"></i>
                        Cambiar estado del lote a "cosechado"
                    </li>
                    <li>
                        <i class="fas fa-check text-success mr-1"></i>
                        Guardar en historial de estados
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-sitemap mr-1"></i> Flujo de Producción</h6>
            </div>
            <div class="card-body small text-center">
                <div class="mb-1"><i class="fas fa-seedling text-success"></i> Siembra</div>
                <div class="mb-1"><i class="fas fa-arrow-down text-muted"></i></div>
                <div class="mb-1 font-weight-bold text-success"><i class="fas fa-tractor"></i> <strong>Cosecha</strong></div>
                <div class="mb-1"><i class="fas fa-arrow-down text-muted"></i></div>
                <div class="mb-1"><i class="fas fa-warehouse text-info"></i> Almacenamiento</div>
                <div class="mb-1"><i class="fas fa-arrow-down text-muted"></i></div>
                <div><i class="fas fa-dollar-sign text-warning"></i> Venta</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function convertirAKg(cantidad, unidad) {
    unidad = unidad.toLowerCase().trim();

    const factores = {
        'kg': 1,
        'kilogramo': 1,
        'kilogramos': 1,

        'g': 0.001,
        'gramo': 0.001,
        'gramos': 0.001,

        't': 1000,
        'tn': 1000,
        'ton': 1000,
        'tonelada': 1000,
        'toneladas': 1000,

        'qq': 46,
        'quintal': 46,
        'quintales': 46,
    };

    return cantidad * (factores[unidad] || 1);
}

$(document).ready(function() {
    // Mostrar info del lote
    $('#loteid').on('change', function() {
        const selected = $(this).find(':selected');
        if (selected.val()) {
            $('#infoCultivo').text(selected.data('cultivo'));
            $('#infoResponsable').text(selected.data('responsable'));
            $('#loteInfo').slideDown();
        } else {
            $('#loteInfo').slideUp();
        }
    });

    // Switch de almacén
    $('#enviarAlmacen').on('change', function() {
        if ($(this).is(':checked')) {
            $('#almacenOptions').slideDown();
            $('#almacenSection').addClass('active');
            $('#actionAlmacen').show();
        } else {
            $('#almacenOptions').slideUp();
            $('#almacenSection').removeClass('active');
            $('#actionAlmacen').hide();
            $('.almacen-card').removeClass('selected');
            $('.almacen-card .fa-check-circle').hide();
            $('#almacenid').val('');
            $('#infoAlmacenSelected').html('<i class="fas fa-warehouse mr-1"></i> <strong>Almacén:</strong> No seleccionado');
        }
    });

    // Seleccionar almacén
    $('.almacen-card').on('click', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const disponible = $(this).data('disponible');

        // Deseleccionar otros
        $('.almacen-card').removeClass('selected');
        $('.almacen-card .fa-check-circle').hide();

        // Seleccionar este
        $(this).addClass('selected');
        $(this).find('.fa-check-circle').show();

        $('#almacenid').val(id);
        $('#infoAlmacenSelected').html('<i class="fas fa-warehouse mr-1"></i> <strong>Almacén:</strong> ' + nombre);

        // Verificar capacidad
        // Verificar capacidad CON CONVERSIÓN DE UNIDADES
        const cantidad = parseFloat($('#cantidad').val()) || 0;

        if (cantidad > 0) {
            const umProduccion = $('#unidadmedidaid option:selected').data('abrev');
            const umAlmacen = $(this).data('um-almacen');

            const cantidadKg = convertirAKg(cantidad, umProduccion);
            const disponibleKg = convertirAKg(disponible, umAlmacen);

            if (cantidadKg > disponibleKg) {
                alert(
                    '⚠️ Advertencia: La cantidad (' + cantidad + ' ' + umProduccion + ')' +
                    ' excede la capacidad disponible del almacén (' + disponible + ' ' + umAlmacen + ')'
                );
            }
        }
    });

    // Verificar al cambiar cantidad
    $('#cantidad').on('change', function() {
        const cantidad = parseFloat($(this).val()) || 0;
        const almacenCard = $('.almacen-card.selected');
        if (almacenCard.length) {
            const disponible = almacenCard.data('disponible');
            const umProduccion = $('#unidadmedidaid option:selected').data('abrev');
            const umAlmacen = almacenCard.data('um-almacen');

            const cantidadKg = convertirAKg(cantidad, umProduccion);
            const disponibleKg = convertirAKg(disponible, umAlmacen);

            if (cantidadKg > disponibleKg) {
                alert(
                    '⚠️ Advertencia: La cantidad (' + cantidad + ' ' + umProduccion + ')' +
                    ' excede la capacidad disponible del almacén (' + disponible + ' ' + umAlmacen + ')'
                );
            }
        }
    });
});
</script>
@endpush