@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Registros Climáticos</h3>
        <a href="{{ route('climas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Registro
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lote</th>
                    <th>Fecha</th>
                    <th>Temp (°C)</th>
                    <th>Humedad (%)</th>
                    <th>Lluvia (mm)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($climas as $c)
                    <tr>
                        <td>{{ $c->climaid }}</td>
                        <td>{{ $c->lote->nombre ?? '-' }}</td>
                        <td>{{ $c->fecha }}</td>
                        <td>{{ $c->temperatura }}</td>
                        <td>{{ $c->humedad }}</td>
                        <td>{{ $c->lluvia }}</td>

                        <td>
                            <a href="{{ route('climas.show', $c) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('climas.edit', $c) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('climas.destroy', $c) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar registro climático?')">
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
                        <td colspan="7" class="text-center py-3">No hay registros climáticos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $climas->links() }}
    </div>

</div>
@endsection