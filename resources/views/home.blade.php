@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bienvenido a AgroNexus</h3>
    </div>
    <div class="card-body">
        <p class="lead">
            AgroNexus es un sistema integral de gestión agrícola diseñado para optimizar
            la administración de lotes, cultivos, insumos, actividades, producción y ventas.
        </p>
        <hr>
        <h4>¿Qué puedes hacer en este sistema?</h4>
        <ul>
            <li><strong>Gestionar lotes:</strong> registrar superficie, ubicación, cultivo y estados.</li>
            <li><strong>Administrar actividades:</strong> tareas agrícolas asignadas, seguimiento y control.</li>
            <li><strong>Registrar insumos y su uso:</strong> control de inventarios y aplicación por lote.</li>
            <li><strong>Control de climas:</strong> registrar condiciones climáticas relevantes.</li>
            <li><strong>Producción y ventas:</strong> registrar cosechas, destinos y comercialización.</li>
            <li><strong>Administrar usuarios y roles:</strong> control de accesos y permisos.</li>
        </ul>
        <hr>
    </div>
</div>
@endsection