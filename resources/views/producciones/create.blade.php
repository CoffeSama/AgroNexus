@extends('layouts.app')

@section('title', 'Registrar Cosecha | AgroNexus')

@section('content')
<div class="card">

    <div class="card-header bg-success text-white">
        <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i>Registrar Cosecha</h3>
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
            <div class="row">
                <div class="col-md-8">

                    <div class="form-group">
                        <label><i class="fas fa-map-marked-alt mr-1"></i> Lote a Cosechar <span class="text-danger">*</span></label>
                        <select name="loteid" id="loteid" class="form-control" required>
                            <option value="">-- Seleccione un lote en produccion --</option>
                            @foreach($lotes as $l)
                                <option value="{{ $l->loteid }}"
                                        data-responsable="{{ $l->usuario->nombre ?? '' }} {{ $l->usuario->apellido ?? '' }}"
                                        data-cultivo="{{ $l->cultivo->nombre ?? 'Sin cultivo' }}"
                                        data-estado="{{ $l->estadoTipo->nombre ?? '' }}">
                                    {{ $l->nombre }} - {{ $l->cultivo->nombre ?? 'Sin cultivo' }}
                                </option>
                            @endforeach
                        </select>
                        @if($lotes->isEmpty())
                            <small class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle"></i> No hay lotes en estado "en produccion" disponibles para cosechar.
                            </small>
                        @else
                            <small class="form-text text-muted">
                                Solo se muestran lotes en estado "en produccion"
                            </small>
                        @endif
                    </div>

                    <div id="loteInfo" class="alert alert-info" style="display: none;">
                        <strong><i class="fas fa-info-circle mr-1"></i> Lote seleccionado:</strong><br>
                        Cultivo: <span id="infoCultivo"></span> | 
                        Responsable: <span id="infoResponsable"></span>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-balance-scale mr-1"></i> Cantidad Cosechada <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="cantidad" id="cantidad"
                                   class="form-control" min="0.01" required value="{{ old('cantidad') }}"
                                   placeholder="Ej: 500">
                            <div class="input-group-append">
                                <select name="unidadmedidaid" id="unidadmedidaid" class="form-control" required>
                                    @foreach($unidades as $u)
                                        <option value="{{ $u->unidadmedidaid }}" {{ $u->es_base ? 'selected' : '' }}>
                                            {{ $u->abreviatura }} ({{ $u->nombre }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-comment mr-1"></i> Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2" 
                                  placeholder="Calidad, condiciones de la cosecha, etc...">{{ old('observaciones') }}</textarea>
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-info-circle mr-1"></i> Informacion</h6>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted mb-2">
                                <i class="fas fa-calendar mr-1"></i> <strong>Fecha:</strong> Se registra automaticamente (hoy)
                            </p>
                            <p class="small text-muted mb-0">
                                <i class="fas fa-warehouse mr-1"></i> <strong>Almacen:</strong> Se asigna automaticamente segun el cultivo
                            </p>
                        </div>
                    </div>

                    <div class="card mt-3 border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-check-circle mr-1"></i> Acciones automaticas</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-1"></i>
                                    Registrar produccion
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-1"></i>
                                    Enviar al almacen correspondiente
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
                            <h6 class="mb-0"><i class="fas fa-route mr-1"></i> Flujo</h6>
                        </div>
                        <div class="card-body small">
                            <p class="mb-1"><i class="fas fa-seedling text-success"></i> Siembra</p>
                            <p class="mb-1"><i class="fas fa-arrow-down text-muted"></i></p>
                            <p class="mb-1"><i class="fas fa-truck-loading text-success"></i> <strong>Cosecha (actual)</strong></p>
                            <p class="mb-1"><i class="fas fa-arrow-down text-muted"></i></p>
                            <p class="mb-1"><i class="fas fa-warehouse text-info"></i> Almacen (automatico)</p>
                            <p class="mb-1"><i class="fas fa-arrow-down text-muted"></i></p>
                            <p class="mb-0"><i class="fas fa-dollar-sign text-purple"></i> Venta (posterior)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('producciones.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success" {{ $lotes->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-save mr-1"></i> Registrar Cosecha
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
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
});
</script>
@endpush