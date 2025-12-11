@extends('reportes.pdf.layout')

@section('content')
    <div class="info">
        <h3>Reporte de Producción</h3>
        <p><strong>Fecha Desde:</strong> {{ $fechaDesde }}</p>
        <p><strong>Fecha Hasta:</strong> {{ $fechaHasta }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha Cosecha</th>
                <th>Lote</th>
                <th>Cultivo</th>
                <th>Cantidad</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $produccion)
                <tr>
                    <td>{{ $produccion->fechacosecha instanceof \Carbon\Carbon ? $produccion->fechacosecha->format('d/m/Y') : $produccion->fechacosecha }}
                    </td>
                    <td>{{ $produccion->lote->nombre ?? '-' }}</td>
                    <td>{{ $produccion->lote->cultivo->nombre ?? '-' }}</td>
                    <td>{{ $produccion->cantidad }} {{ $produccion->unidadMedida->abreviatura ?? '' }}</td>
                    <td>{{ $produccion->observaciones ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Total Cosechado: {{ number_format($datos->sum('cantidad'), 2) }}</p>
        <p>Total Registros: {{ $datos->count() }}</p>
    </div>
@endsection