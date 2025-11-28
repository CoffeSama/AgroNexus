@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Producciones</h3>
        <a href="{{ route('producciones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Producción
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lote</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Fecha</th>
                    <th>Destino</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($producciones as $p)
                    <tr>
                        <td>{{ $p->produccionid }}</td>
                        <td>{{ $p->lote->nombre ?? '-' }}</td>
                        <td>{{ $p->cantidadkg }}</td>
                        <td>{{ $p->unidadMedida->nombre ?? '-' }}</td>
                        <td>{{ $p->fechacosecha }}</td>
                        <td>{{ $p->destino->nombre ?? '-' }}</td>

                        <td>
                            <a href="{{ route('producciones.show', $p) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('producciones.edit', $p) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('producciones.destroy', $p) }}" 
                                  method="POST" class="d-inline" 
                                  onsubmit="return confirm('¿Eliminar registro de producción?')">
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
                        <td colspan="7" class="text-center py-3">No hay producciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $producciones->links() }}
    </div>

</div>
@endsection