@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Unidades de Medida</h3>
        <a href="{{ route('unidades-medida.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Unidad
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unidades as $u)
                    <tr>
                        <td>{{ $u->unidadmedidaid }}</td>
                        <td>{{ $u->nombre }}</td>

                        <td>
                            <a href="{{ route('unidades-medida.show', $u) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('unidades-medida.edit', $u) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('unidades-medida.destroy', $u) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar unidad de medida?')">
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
                        <td colspan="3" class="text-center py-3">No hay unidades registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $unidades->links() }}
    </div>

</div>
@endsection