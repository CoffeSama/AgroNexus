@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Cultivos</h3>
        <a href="{{ route('cultivos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Cultivo
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
                @forelse ($cultivos as $c)
                    <tr>
                        <td>{{ $c->cultivoid }}</td>
                        <td>{{ $c->nombre }}</td>

                        <td>
                            <a href="{{ route('cultivos.show', $c) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('cultivos.edit', $c) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('cultivos.destroy', $c) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar cultivo?')">
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
                        <td colspan="3" class="text-center py-3">No hay cultivos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $cultivos->links() }}
    </div>

</div>
@endsection