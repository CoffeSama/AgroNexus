@extends('layouts.app')

@section('title', 'Actividades | AgroNexus')
@section('page_title', 'Gestión de Actividades')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #2c5530;">Inicio</a></li>
    <li class="breadcrumb-item active">Actividades</li>
@endsection

@push('styles')
<style>
    .small-box { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05); transition: transform 0.3s ease; }
    .small-box:hover { transform: translateY(-2px); }
    .small-box .icon { font-size: 70px !important; }
    .small-box-green { background: linear-gradient(135deg, #28a745, #34ce57) !important; }
    .small-box-blue { background: linear-gradient(135deg, #17a2b8, #20c997) !important; }
    .small-box-yellow { background: linear-gradient(135deg, #ffc107, #ffca2c) !important; }
    .small-box-red { background: linear-gradient(135deg, #dc3545, #e74a3b) !important; }

    .actividad-card { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 15px; overflow: hidden; transition: all 0.3s ease; border-left: 4px solid #2c5530; }
    .actividad-card:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(0,0,0,0.12); }
    .actividad-card.completada { border-left-color: #28a745; }
    .actividad-card.pendiente { border-left-color: #ffc107; }
    .actividad-header { padding: 15px 20px; display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #f1f3f4; }
    .actividad-body { padding: 15px 20px; }
    .actividad-info { display: flex; flex-wrap: wrap; gap: 20px; }
    .actividad-info-item { flex: 1; min-width: 140px; }
    .actividad-info-item label { display: block; font-size: 0.75rem; color: #6c757d; text-transform: uppercase; margin-bottom: 3px; }
    .actividad-info-item span { font-weight: 600; color: #1a252f; }
    .actividad-footer { padding: 12px 20px; background: #f8f9fc; display: flex; justify-content: space-between; align-items: center; }
    .actividad-tipo { display: inline-flex; align-items: center; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; color: white; background: #17a2b8; }
    .view-toggle .btn.active { background: #2c5530; color: white; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box small-box-green">
            <div class="inner"><h3>{{ $actividades->total() }}</h3><p>Total Actividades</p></div>
            <div class="icon"><i class="fas fa-tasks"></i></div>
            <a href="{{ route('actividades.calendario') }}" class="small-box-footer">Ver calendario <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box small-box-yellow">
            <div class="inner"><h3>{{ $actividades->filter(fn($a) => $a->fechafin === null)->count() }}</h3><p>Pendientes</p></div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <span class="small-box-footer">&nbsp;</span>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box small-box-blue">
            <div class="inner"><h3>{{ $actividades->filter(fn($a) => $a->fechafin !== null)->count() }}</h3><p>Completadas</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <span class="small-box-footer">&nbsp;</span>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box small-box-red">
            <div class="inner"><h3>{{ $actividades->filter(fn($a) => $a->fechainicio && \Carbon\Carbon::parse($a->fechainicio)->isToday())->count() }}</h3><p>Hoy</p></div>
            <div class="icon"><i class="fas fa-calendar-day"></i></div>
            <span class="small-box-footer">&nbsp;</span>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <a href="{{ route('actividades.create') }}" class="btn btn-success mr-2"><i class="fas fa-plus mr-1"></i> Nueva Actividad</a>
                <a href="{{ route('actividades.calendario') }}" class="btn btn-outline-success"><i class="fas fa-calendar-alt mr-1"></i> Calendario</a>
            </div>
            <div class="col-md-6 text-md-right mt-3 mt-md-0">
                <div class="btn-group view-toggle">
                    <button type="button" class="btn btn-outline-secondary active" id="btnCardView"><i class="fas fa-th-large"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="btnTableView"><i class="fas fa-list"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="cardView">
    @forelse($actividades as $act)
        @php $esCompletada = $act->fechafin !== null; $cardClass = $esCompletada ? 'completada' : 'pendiente'; @endphp
        <div class="actividad-card {{ $cardClass }}">
            <div class="actividad-header">
                <div>
                    <span class="actividad-tipo"><i class="fas fa-tasks mr-1"></i> {{ $act->tipoActividad->nombre ?? 'Sin tipo' }}</span>
                    @if($act->prioridad)<span class="badge badge-{{ strtolower($act->prioridad->nombre) == 'alta' ? 'danger' : (strtolower($act->prioridad->nombre) == 'media' ? 'warning' : 'success') }} ml-2">{{ $act->prioridad->nombre }}</span>@endif
                </div>
                <span class="badge badge-{{ $esCompletada ? 'success' : 'warning' }}">{{ $esCompletada ? 'Completada' : 'Pendiente' }}</span>
            </div>
            <div class="actividad-body">
                <div class="actividad-info">
                    <div class="actividad-info-item"><label><i class="fas fa-map-marker-alt mr-1"></i> Lote</label><span>{{ $act->lote->nombre ?? '-' }}</span></div>
                    <div class="actividad-info-item"><label><i class="fas fa-user mr-1"></i> Responsable</label><span>{{ $act->usuario->nombre ?? '-' }}</span></div>
                    <div class="actividad-info-item"><label><i class="fas fa-calendar mr-1"></i> Inicio</label><span>{{ $act->fechainicio ? \Carbon\Carbon::parse($act->fechainicio)->format('d/m/Y') : '-' }}</span></div>
                    <div class="actividad-info-item"><label><i class="fas fa-calendar-check mr-1"></i> Fin</label><span>{{ $act->fechafin ? \Carbon\Carbon::parse($act->fechafin)->format('d/m/Y') : '-' }}</span></div>
                </div>
                @if($act->descripcion)<p class="mt-3 mb-0 text-muted"><i class="fas fa-comment mr-1"></i> {{ Str::limit($act->descripcion, 100) }}</p>@endif
            </div>
            <div class="actividad-footer">
                <small class="text-muted">@if($act->lote && $act->lote->cultivo)<span class="badge badge-light"><i class="fas fa-seedling mr-1"></i> {{ $act->lote->cultivo->nombre }}</span>@endif</small>
                <div>
                    <a href="{{ route('actividades.show', $act) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('actividades.edit', $act) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('actividades.destroy', $act) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form>
                </div>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center py-5"><i class="fas fa-tasks fa-4x text-muted mb-3"></i><h4>No hay actividades</h4><a href="{{ route('actividades.create') }}" class="btn btn-success"><i class="fas fa-plus mr-1"></i> Nueva Actividad</a></div></div>
    @endforelse
</div>

<div id="tableView" style="display: none;">
    <div class="card"><div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="bg-light"><tr><th>Tipo</th><th>Lote</th><th>Responsable</th><th>Descripción</th><th>Inicio</th><th>Estado</th><th style="width: 130px;">Acciones</th></tr></thead>
            <tbody>
                @forelse($actividades as $act)
                    @php $esCompletada = $act->fechafin !== null; @endphp
                    <tr>
                        <td><span class="badge badge-info">{{ $act->tipoActividad->nombre ?? '-' }}</span></td>
                        <td>{{ $act->lote->nombre ?? '-' }}</td>
                        <td>{{ $act->usuario->nombre ?? '-' }}</td>
                        <td>{{ Str::limit($act->descripcion ?? '-', 40) }}</td>
                        <td>{{ $act->fechainicio ? \Carbon\Carbon::parse($act->fechainicio)->format('d/m/Y') : '-' }}</td>
                        <td><span class="badge badge-{{ $esCompletada ? 'success' : 'warning' }}">{{ $esCompletada ? 'Completada' : 'Pendiente' }}</span></td>
                        <td><a href="{{ route('actividades.show', $act) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a><a href="{{ route('actividades.edit', $act) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a><form action="{{ route('actividades.destroy', $act) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">No hay actividades</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>

@if($actividades->hasPages())<div class="card mt-4"><div class="card-body d-flex justify-content-center">{{ $actividades->links() }}</div></div>@endif
@endsection

@push('scripts')
<script>
$(function() {
    $('#btnCardView').on('click', function() { $(this).addClass('active').siblings().removeClass('active'); $('#cardView').show(); $('#tableView').hide(); });
    $('#btnTableView').on('click', function() { $(this).addClass('active').siblings().removeClass('active'); $('#tableView').show(); $('#cardView').hide(); });
});
</script>
@endpush