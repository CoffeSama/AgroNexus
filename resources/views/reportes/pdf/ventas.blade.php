@extends('reportes.pdf.layout')

@section('content')
    <div class="info">
        <h3>Reporte de Ventas</h3>
        <p><strong>Fecha Desde:</strong> {{ $fechaDesde }}</p>
        <p><strong>Fecha Hasta:</strong> {{ $fechaHasta }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Cultivo</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $venta)
                <tr>
                    <td>{{ $venta->fechaventa instanceof \Carbon\Carbon ? $venta->fechaventa->format('d/m/Y') : $venta->fechaventa }}
                    </td>
                    <td>{{ $venta->cliente ?? '-' }}</td>
                    <td>{{ $venta->produccion->lote->cultivo->nombre ?? '-' }}</td>
                    <td>{{ $venta->cantidad }} {{ $venta->unidadMedida->abreviatura ?? '' }}</td>
                    <td>{{ number_format($venta->preciounitario, 2) }}</td>
                    <td>{{ number_format($venta->cantidad * $venta->preciounitario, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Total Ventas: {{ number_format($datos->sum(fn($v) => $v->cantidad * $v->preciounitario), 2) }}</p>
        <p>Total Registros: {{ $datos->count() }}</p>
    </div>
@endsection