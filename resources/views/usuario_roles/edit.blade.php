@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Editar Asignación de Rol</h3>
    </div>

    <form action="{{ route('usuario-roles.update', $usuarioRole) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Usuario</label>
                <select name="usuarioid" class="form-control" required>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}"
                            {{ $u->usuarioid == $usuarioRole->usuarioid ? 'selected' : '' }}>
                            {{ $u->nombre }} {{ $u->apellido }} ({{ $u->nombreusuario }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Rol</label>
                <select name="rolid" class="form-control" required>
                    @foreach($roles as $r)
                        <option value="{{ $r->rolid }}"
                            {{ $r->rolid == $usuarioRole->rolid ? 'selected' : '' }}>
                            {{ $r->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('usuario-roles.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Actualizar</button>
        </div>

    </form>
</div>
@endsection