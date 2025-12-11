@extends('reportes.pdf.layout')

@section('content')
    <div class="info">
        <h3>Reporte de Inventario Actual</h3>
        <p><strong>Fecha de Generación:</strong> {{ now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Precio Unit.</th>
                <th>Valor Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $insumo)
                <tr>
                    <td>{{ $insumo->nombre }}</td>
                    <td>{{ $insumo->tipo->nombre ?? '-' }}</td>
                    <td style="{{ $insumo->stock <= ($insumo->stockminimo ?? 0) ? 'color: red; font-weight: bold;' : '' }}">
                        {{ $insumo->stock }} {{ $insumo->unidadMedida->abreviatura ?? '' }}
                    </td>
                    <td>{{ $insumo->stockminimo ?? '-' }}</td>
                    <td>{{ number_format($insumo->preciounitario, 2) }}</td>
                    <td>{{ number_format($insumo->stock * ($insumo->preciounitario ?? 0), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Total Items: {{ $datos->count() }}</p>
        <p>Valor Total Inventario: {{ number_format($datos->sum(fn($i) => $i->stock * ($i->preciounitario ?? 0)), 2) }}</p>
    </div>
@endsection