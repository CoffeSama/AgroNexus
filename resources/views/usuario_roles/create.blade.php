@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Asignar Rol a Usuario</h3>
    </div>

    <form action="{{ route('usuario-roles.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Usuario</label>
                <select name="usuarioid" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->usuarioid }}">
                            {{ $u->nombre }} {{ $u->apellido }} ({{ $u->nombreusuario }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Rol</label>
                <select name="rolid" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->rolid }}">{{ $r->nombre }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('usuario-roles.index') }}" class="btn btn-secondary">Cancelar</a>
            <button class="btn btn-primary">Guardar</button>
        </div>

    </form>
</div>
@endsection