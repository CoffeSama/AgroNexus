@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Aplicaciones de Insumos en Lotes</h3>
        <a href="{{ route('lote-insumos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Registro
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lote</th>
                    <th>Insumo</th>
                    <th>Usuario</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Fecha uso</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loteInsumos as $li)
                    <tr>
                        <td>{{ $li->loteinsumoid }}</td>
                        <td>{{ $li->lote->nombre ?? '-' }}</td>
                        <td>{{ $li->insumo->nombre ?? '-' }}</td>
                        <td>{{ $li->usuario->nombre ?? '-' }}</td>
                        <td>{{ $li->cantidadusada }}</td>
                        <td>{{ $li->estado->nombre ?? '-' }}</td>
                        <td>{{ $li->fechauo }}</td>

                        <td>
                            <a href="{{ route('lote-insumos.show', $li) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('lote-insumos.edit', $li) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('lote-insumos.destroy', $li) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar registro de insumo aplicado?')">
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
                        <td colspan="8" class="text-center py-3">No hay aplicaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $loteInsumos->links() }}
    </div>

</div>
@endsection