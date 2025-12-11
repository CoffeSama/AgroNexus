@extends('reportes.pdf.layout')

@section('content')
    <div class="info">
        <h3>Reporte de Actividades</h3>
        <p><strong>Fecha Desde:</strong> {{ $fechaDesde }}</p>
        <p><strong>Fecha Hasta:</strong> {{ $fechaHasta }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha Inicio</th>
                <th>Estado</th>
                <th>Lote</th>
                <th>Actividad</th>
                <th>Responsable</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $actividad)
                <tr>
                    <td>{{ $actividad->fechainicio instanceof \Carbon\Carbon ? $actividad->fechainicio->format('d/m/Y') : $actividad->fechainicio }}
                    </td>
                    <td>
                        @if($actividad->fechafin)
                            <span style="color: green">Completada
                                ({{ $actividad->fechafin instanceof \Carbon\Carbon ? $actividad->fechafin->format('d/m/Y') : $actividad->fechafin }})</span>
                        @else
                            <span style="color: orange">Pendiente</span>
                        @endif
                    </td>
                    <td>{{ $actividad->lote->nombre ?? '-' }}</td>
                    <td>{{ $actividad->tipoActividad->nombre ?? '-' }}</td>
                    <td>{{ $actividad->usuario->nombre ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Total Actividades: {{ $datos->count() }}</p>
        <p>Pendientes: {{ $datos->whereNull('fechafin')->count() }}</p>
        <p>Completadas: {{ $datos->whereNotNull('fechafin')->count() }}</p>
    </div>
@endsection