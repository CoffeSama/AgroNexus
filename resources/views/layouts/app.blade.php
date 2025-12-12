<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'AgroNexus | Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- FONT AWESOME (CDN) --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- ADMINLTE (CDN) --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    {{-- PALETA EXACTA Y ESTILO DE HEADER / SIDEBAR / FOOTER DE LA MAQUETA --}}
    <style>
        :root {
            --primary-color: #2c5530;
            --secondary-color: #4a7c59;
            --accent-color: #e8f5e8;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --text-dark: #1a252f;
            --text-light: #6c757d;
            --border-color: #dee2e6;
        }

        .main-header {
            background: var(--primary-color) !important;
            border-bottom: 3px solid var(--secondary-color);
        }

        .main-header .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .main-header .navbar-nav .nav-link:hover {
            color: #ffffff !important;
        }

        .main-sidebar {
            background: #2d3748 !important;
        }

        .brand-link {
            background: var(--primary-color) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
        }

        .brand-link .brand-image {
            color: #ffffff;
        }

        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background: var(--primary-color) !important;
            color: #ffffff;
        }

        .nav-sidebar .nav-item > .nav-link .right {
            margin-left: auto;
        }

        .content-wrapper {
            background: #f8f9fc;
        }

        body {
            background: #f8f9fc;
        }

        .user-panel img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        .main-footer {
            background: #ffffff;
            border-top: 1px solid #dee2e6;
            color: var(--text-light);
        }

        .main-footer a {
            color: var(--primary-color);
        }

        .role-badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 10px;
            text-transform: uppercase;
        }
        .role-badge.admin {
            background: #dc3545;
            color: white;
        }
        .role-badge.agricultor {
            background: #28a745;
            color: white;
        }
    </style>

    @stack('styles')
</head>

@php
    $authUser = auth()->user();
    $userFullName = $authUser
        ? trim(($authUser->nombre ?? '') . ' ' . ($authUser->apellido ?? ''))
        : 'Usuario';

    if ($authUser && $userFullName === '') {
        $userFullName = $authUser->nombreusuario ?? 'Usuario';
    }

    $userImagePath = $authUser && $authUser->imagenurl
        ? $authUser->imagenurl
        : 'images/user.png';

    $userImageUrl = asset($userImagePath);

    // Obtener rol del usuario
    $userRole = $authUser ? ($authUser->getRoleNames()->first() ?? 'sin rol') : 'invitado';
    $isAdmin = $authUser && $authUser->hasRole('admin');
@endphp

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- NAVBAR SUPERIOR --}}
    <nav class="main-header navbar navbar-expand navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 28px; margin-right: 8px;">
                    <span class="font-weight-bold">AgroNexus</span>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">5</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">5 Notificaciones</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-seedling mr-2"></i> Nuevas actividades programadas
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">Ver todas</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ $userImageUrl }}"
                         class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline">
                        @auth
                            {{ $userFullName }}
                        @else
                            Invitado
                        @endauth
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <li class="user-header bg-primary">
                        <img src="{{ $userImageUrl }}"
                             class="img-circle elevation-2" alt="User Image">
                        <p>
                            @auth
                                {{ $userFullName }}
                                <small class="role-badge {{ $userRole }}">{{ ucfirst($userRole) }}</small>
                            @else
                                Invitado
                            @endauth
                            <small>AgroNexus · Panel Administrativo</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">Perfil</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline float-right">
                            @csrf
                            <button type="submit" class="btn btn-default btn-flat">Salir</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    {{-- SIDEBAR --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('images/logo.png') }}"
                 alt="AgroNexus Logo"
                 class="brand-image img-circle elevation-3"
                 style="opacity:.9">
            <span class="brand-text font-weight-light">AgroNexus</span>
        </a>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ $userImageUrl }}"
                         class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        @auth
                            {{ $userFullName }}
                        @else
                            Invitado
                        @endauth
                    </a>
                    <span class="role-badge {{ $userRole }}">{{ ucfirst($userRole) }}</span>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false">

                    {{-- DASHBOARD (todos) --}}
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    {{-- GESTIÓN DE LOTES (todos) --}}
                    <li class="nav-item {{ request()->routeIs('lotes.*','actividades.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('lotes.*','actividades.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-map-marked-alt"></i>
                            <p>
                                Gestión de Lotes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('lotes.index') }}"
                                   class="nav-link {{ request()->routeIs('lotes.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Lista de Lotes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('lotes.mapa') }}"
                                   class="nav-link {{ request()->routeIs('lotes.mapa') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mapa de Lotes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('actividades.index') }}"
                                   class="nav-link {{ request()->routeIs('actividades.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Lista Actividades</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('actividades.calendario') }}"
                                   class="nav-link {{ request()->routeIs('actividades.calendario') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Calendario</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- PRODUCCIÓN (todos) --}}
                    <li class="nav-item {{ request()->routeIs('producciones.*','climas.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('producciones.*','climas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-seedling"></i>
                            <p>
                                Producción
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('producciones.index') }}"
                                   class="nav-link {{ request()->routeIs('producciones.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Producción</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('climas.index') }}"
                                   class="nav-link {{ request()->routeIs('climas.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Clima</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- INVENTARIO (todos pueden ver, solo admin edita) --}}
                    <li class="nav-item {{ request()->routeIs('insumos.*','lote-insumos.*','almacenes.*','producciones_almacenamiento.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('insumos.*','lote-insumos.*','almacenes.*','producciones_almacenamiento.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>
                                Inventario
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('insumos.index') }}"
                                class="nav-link {{ request()->routeIs('insumos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Insumos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('lote-insumos.index') }}"
                                class="nav-link {{ request()->routeIs('lote-insumos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Aplicación de Insumos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('almacenes.index') }}"
                                class="nav-link {{ request()->routeIs('almacenes.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Almacenes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('producciones_almacenamiento.index') }}"
                                class="nav-link {{ request()->routeIs('producciones_almacenamiento.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Almacenamiento</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- ENVÍOS (todos) --}}
                    <li class="nav-item {{ request()->routeIs('envios.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('envios.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i>
                            <p>
                                Envíos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('envios.mandar') }}"
                                   class="nav-link {{ request()->routeIs('envios.mandar') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mandar Envío</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('envios.seguimiento') }}"
                                   class="nav-link {{ request()->routeIs('envios.seguimiento') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Seguimiento Envío</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('pedidos.*') ? 'menu-open' : '' }}">
                                <a href="{{ route('pedidos.index') }}"
                                class="nav-link {{ request()->routeIs('pedidos.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>Pedidos</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- ============================================ --}}
                    {{-- SECCIONES SOLO PARA ADMIN --}}
                    {{-- ============================================ --}}
                    @if($isAdmin)

                    {{-- VENTAS (solo admin) --}}
                    <li class="nav-item {{ request()->routeIs('ventas.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-dollar-sign"></i>
                            <p>
                                Ventas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('ventas.index') }}"
                                   class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado de Ventas</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- REPORTES (solo admin) --}}
                    <li class="nav-item {{ request()->routeIs('reportes.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Reportes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('reportes.index') }}" 
                                   class="nav-link {{ request()->routeIs('reportes.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Centro de Reportes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reportes.ventas') }}" 
                                   class="nav-link {{ request()->routeIs('reportes.ventas') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ventas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reportes.inventario') }}" 
                                   class="nav-link {{ request()->routeIs('reportes.inventario') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Inventario</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reportes.produccion') }}" 
                                   class="nav-link {{ request()->routeIs('reportes.produccion') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Producción</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reportes.climatico') }}" 
                                   class="nav-link {{ request()->routeIs('reportes.climatico') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Climático</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reportes.actividades') }}" 
                                   class="nav-link {{ request()->routeIs('reportes.actividades') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Actividades</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- CATÁLOGOS (solo admin) --}}
                    <li class="nav-item {{ request()->routeIs('cultivos.*','tipo-actividad.*','tipo-insumos.*','unidades-medida.*','estado-lote-tipos.*','estado-lote-insumos.*','historial-estados-lote.*','prioridades.*','tipoalmacenes.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('cultivos.*','tipo-actividad.*','tipo-insumos.*','unidades-medida.*','estado-lote-tipos.*','estado-lote-insumos.*','historial-estados-lote.*','prioridades.*','tipoalmacenes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>
                                Catálogos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('cultivos.index') }}"
                                   class="nav-link {{ request()->routeIs('cultivos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cultivos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tipo-actividad.index') }}"
                                   class="nav-link {{ request()->routeIs('tipo-actividad.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tipo Actividad</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tipo-insumos.index') }}"
                                   class="nav-link {{ request()->routeIs('tipo-insumos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tipo Insumo</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('unidades-medida.index') }}"
                                   class="nav-link {{ request()->routeIs('unidades-medida.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Unidad de Medida</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tipoalmacenes.index') }}"
                                class="nav-link {{ request()->routeIs('tipoalmacenes.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tipo de Almacén</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('estado-lote-tipos.index') }}"
                                   class="nav-link {{ request()->routeIs('estado-lote-tipos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tipos Estado Lote</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('estado-lote-insumos.index') }}"
                                   class="nav-link {{ request()->routeIs('estado-lote-insumos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Estado Insumo</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('historial-estados-lote.index') }}"
                                   class="nav-link {{ request()->routeIs('historial-estados-lote.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Historial Estado Lote</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('prioridades.index') }}"
                                   class="nav-link {{ request()->routeIs('prioridades.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Prioridades</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- GESTIÓN DE USUARIOS (solo admin) --}}
                    <li class="nav-item {{ request()->routeIs('gestion.*') ? 'menu-open' : '' }}">
                        <a href="{{ route('gestion.index') }}"
                           class="nav-link {{ request()->routeIs('gestion.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Gestión de Usuarios</p>
                        </a>
                    </li>

                    @endif
                    {{-- FIN SECCIONES SOLO ADMIN --}}

                </ul>
            </nav>
        </div>
    </aside>

    {{-- CONTENT WRAPPER --}}
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page_title', 'Dashboard Principal')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumbs')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    {{-- FOOTER --}}
    <footer class="main-footer">
        <strong>
            &copy; {{ date('Y') }}
            <a href="#">AgroNexus</a>.
        </strong>
        Sistema de Gestión de Producción Agrícola.
        <div class="float-right d-none d-sm-inline-block">
            <b>Versión</b> 1.0.0
        </div>
    </footer>

</div>

{{-- JS CDN --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>