@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Almacenamiento de Producciones</h3>
        <a href="{{ route('producciones_almacenamiento.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Registro
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producción</th>
                    <th>Almacén</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Temp (°C)</th>
                    <th>Humedad (%)</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros as $r)
                    <tr>
                        <td>{{ $r->produccionalmacenamientoid }}</td>
                        <td>
                            #{{ $r->produccionid }}
                            @if($r->produccion && $r->produccion->lote)
                                (Lote: {{ $r->produccion->lote->nombre }})
                            @endif
                        </td>
                        <td>{{ $r->almacen->nombre ?? '-' }}</td>
                        <td>{{ $r->cantidad }}</td>
                        <td>{{ $r->unidadMedida->nombre ?? '-' }}</td>
                        <td>{{ $r->temperatura }}</td>
                        <td>{{ $r->humedad }}</td>
                        <td>
                            <a href="{{ route('producciones_almacenamiento.show', $r) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('producciones_almacenamiento.edit', $r) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('producciones_almacenamiento.destroy', $r) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar registro de almacenamiento?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-3">No hay registros de almacenamiento.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $registros->links() }}
    </div>

</div>
@endsection