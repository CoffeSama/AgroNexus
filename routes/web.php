<?php

use Illuminate\Support\Facades\Route;

// CONTROLADORES WEB
use App\Http\Controllers\Web\ActividadController;
use App\Http\Controllers\Web\ClimaController;
use App\Http\Controllers\Web\CultivoController;
use App\Http\Controllers\Web\EstadoLoteController;
use App\Http\Controllers\Web\EstadoLoteInsumoController;
use App\Http\Controllers\Web\EstadoLoteTipoController;
use App\Http\Controllers\Web\HistorialEstadoLoteController;
use App\Http\Controllers\Web\InsumoController;
use App\Http\Controllers\Web\LoteController;
use App\Http\Controllers\Web\LoteInsumoController;
use App\Http\Controllers\Web\PrioridadController;
use App\Http\Controllers\Web\ProduccionController;
use App\Http\Controllers\Web\TipoActividadController;
use App\Http\Controllers\Web\TipoInsumoController;
use App\Http\Controllers\Web\UnidadMedidaController;
use App\Http\Controllers\Web\VentaController;
use App\Http\Controllers\Web\GestionUsuariosController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\PedidoController;
use App\Http\Controllers\Web\UserProfileController;

// 🔹 nuevos controladores web de almacenamiento
use App\Http\Controllers\Web\TipoAlmacenController;
use App\Http\Controllers\Web\AlmacenController;
use App\Http\Controllers\Web\ProduccionAlmacenamientoController;



// 🔹 Dashboard Controller
use App\Http\Controllers\Web\DashboardController;

// 🔹 Reportes Controller
use App\Http\Controllers\Web\ReporteController;

// 🔹 Catálogos Controller
use App\Http\Controllers\Web\CatalogoController;

// 🔹 External API Proxy Controller
use App\Http\Controllers\Web\ExternalApiProxyController;

// ======================================================
// RUTAS PÚBLICAS (SIN LOGIN)


// Página inicial -> redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Formularios auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// RUTAS PROTEGIDAS (REQUIEREN ESTAR LOGUEADO)

Route::middleware('auth')->group(function () {

    // Perfil de Usuario
    Route::get('/perfil', [UserProfileController::class, 'show'])->name('profile.show');
    Route::put('/perfil', [UserProfileController::class, 'update'])->name('profile.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Catálogos centralizados
    Route::get('/catalogos', [CatalogoController::class, 'index'])->name('catalogos.index');

    // API endpoints para clima (OpenWeather)
    Route::get('/api/clima', [DashboardController::class, 'getClima'])->name('api.clima');
    Route::get('/api/pronostico', [DashboardController::class, 'getPronostico'])->name('api.pronostico');

    Route::get('actividades/calendario', [ActividadController::class, 'calendario'])->name('actividades.calendario');
    Route::post('actividades/{actividad}/marcar-realizada', [ActividadController::class, 'marcarRealizada'])->name('actividades.marcar-realizada');
    Route::resource('actividades', ActividadController::class)
        ->parameters(['actividades' => 'actividad']);
    Route::get('climas', [ClimaController::class, 'index'])->name('climas.index');
    Route::resource('cultivos', CultivoController::class);
    Route::resource('estadolotes', EstadoLoteController::class);
    Route::resource('estado-lote-insumos', EstadoLoteInsumoController::class);
    Route::resource('estado-lote-tipos', EstadoLoteTipoController::class);
    Route::resource('historial-estados-lote', HistorialEstadoLoteController::class);
    Route::resource('insumos', InsumoController::class);
    Route::get('lotes/mapa', [LoteController::class, 'mapa'])->name('lotes.mapa');
    Route::resource('lotes', LoteController::class);
    Route::resource('lote-insumos', LoteInsumoController::class);
    Route::resource('prioridades', PrioridadController::class)
        ->parameters(['prioridades' => 'prioridad']);
    Route::resource('producciones', ProduccionController::class)
        ->parameters(['producciones' => 'produccion']);
    Route::resource('tipo-actividad', TipoActividadController::class);
    Route::resource('tipo-insumos', TipoInsumoController::class);
    Route::resource('unidades-medida', UnidadMedidaController::class)
        ->parameters(['unidades-medida' => 'unidad']);
    Route::resource('ventas', VentaController::class);
    Route::resource('tipoalmacenes', TipoAlmacenController::class)
        ->parameters(['tipoalmacenes' => 'tipoalmacen']);
    Route::resource('almacenes', AlmacenController::class)
        ->parameters(['almacenes' => 'almacen']);
    Route::resource('producciones_almacenamiento', ProduccionAlmacenamientoController::class);
    // ==============================
    // PEDIDOS (CLIENTES EXTERNOS)
    // ==============================
    Route::resource('pedidos', PedidoController::class);


    // GESTIÓN UNIFICADA DE USUARIOS

    Route::get('/gestion-usuarios', [GestionUsuariosController::class, 'index'])
        ->name('gestion.index');

    // CRUD Usuarios
    Route::post('/gestion-usuarios/usuario', [GestionUsuariosController::class, 'storeUsuario'])
        ->name('gestion.usuario.store');

    Route::put('/gestion-usuarios/usuario/{usuario}', [GestionUsuariosController::class, 'updateUsuario'])
        ->name('gestion.usuario.update');

    Route::delete('/gestion-usuarios/usuario/{usuario}', [GestionUsuariosController::class, 'destroyUsuario'])
        ->name('gestion.usuario.destroy');

    // CRUD Roles
    Route::post('/gestion-usuarios/rol', [GestionUsuariosController::class, 'storeRol'])
        ->name('gestion.rol.store');

    Route::put('/gestion-usuarios/rol/{role}', [GestionUsuariosController::class, 'updateRol'])
        ->name('gestion.rol.update');

    Route::delete('/gestion-usuarios/rol/{role}', [GestionUsuariosController::class, 'destroyRol'])
        ->name('gestion.rol.destroy');


    // REPORTES

    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('/ventas', [ReporteController::class, 'ventas'])->name('ventas');
        Route::get('/inventario', [ReporteController::class, 'inventario'])->name('inventario');
        Route::get('/produccion', [ReporteController::class, 'produccion'])->name('produccion');
        Route::get('/climatico', [ReporteController::class, 'climatico'])->name('climatico');
        Route::get('/actividades', [ReporteController::class, 'actividades'])->name('actividades');
        Route::get('/exportar/{tipo}', [ReporteController::class, 'exportar'])->name('exportar');
    });


    // ENVÍOS

    Route::prefix('envios')->name('envios.')->group(function () {
        Route::get('/mandar', fn() => view('envios.mandar-envio'))->name('mandar');
        Route::get('/seguimiento', fn() => view('envios.seguimiento'))->name('seguimiento');
        Route::get('/{id}', fn($id) => view('envios.detalle', ['id' => $id]))->name('detalle')->where('id', '[0-9]+');

        // ==============================
        // PROXY API EXTERNA (evita CORS)
        // ==============================
        Route::prefix('api')->name('api.')->group(function () {
            // Catálogos
            Route::get('/catalogo-categorias', [ExternalApiProxyController::class, 'getCategorias'])->name('categorias');
            Route::get('/catalogo-productos', [ExternalApiProxyController::class, 'getProductos'])->name('productos');
            Route::get('/catalogo-tipos-empaque', [ExternalApiProxyController::class, 'getTiposEmpaque'])->name('tipos-empaque');
            Route::get('/catalogo-tamano-conteo', [ExternalApiProxyController::class, 'getTamanoConteo'])->name('tamano-conteo');
            Route::get('/tipo-transporte', [ExternalApiProxyController::class, 'getTiposTransporte'])->name('tipos-transporte');

            // Envíos
            Route::post('/direccion', [ExternalApiProxyController::class, 'crearDireccion'])->name('direccion');
            Route::post('/crear-envio', [ExternalApiProxyController::class, 'crearEnvioProductor'])->name('crear-envio');
            Route::get('/envios', [ExternalApiProxyController::class, 'getEnvios'])->name('envios');
            Route::get('/envios/{id}', [ExternalApiProxyController::class, 'getEnvioDetalle'])->name('envio-detalle');
        });
    });

    // ==============================
    // TRANSACCIONES AGRÍCOLAS - ELIMINADO
    // ==============================
});