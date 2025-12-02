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

// 🔹 nuevos controladores web de almacenamiento
use App\Http\Controllers\Web\TipoAlmacenController;
use App\Http\Controllers\Web\AlmacenController;
use App\Http\Controllers\Web\ProduccionAlmacenamientoController;

// 🔹 Controlador de Transacciones
use App\Http\Controllers\Web\TransaccionesController;

// 🔹 Dashboard Controller
use App\Http\Controllers\Web\DashboardController;

// 🔹 Reportes Controller
use App\Http\Controllers\Web\ReporteController;

// ======================================================
// RUTAS PÚBLICAS (SIN LOGIN)
// ======================================================

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

// ======================================================
// RUTAS PROTEGIDAS (REQUIEREN ESTAR LOGUEADO)
// ======================================================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // API endpoints para clima (OpenWeather)
    Route::get('/api/clima', [DashboardController::class, 'getClima'])->name('api.clima');
    Route::get('/api/pronostico', [DashboardController::class, 'getPronostico'])->name('api.pronostico');

    Route::get('actividades/calendario', [ActividadController::class, 'calendario'])->name('actividades.calendario');
    Route::resource('actividades', ActividadController::class)
        ->parameters(['actividades' => 'actividad']);
    Route::resource('climas', ClimaController::class);
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
    // GESTIÓN UNIFICADA DE USUARIOS
    // ==============================
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

    Route::put('/gestion-usuarios/rol/{rol}', [GestionUsuariosController::class, 'updateRol'])
        ->name('gestion.rol.update');

    Route::delete('/gestion-usuarios/rol/{rol}', [GestionUsuariosController::class, 'destroyRol'])
        ->name('gestion.rol.destroy');

    // ==============================
    // REPORTES
    // ==============================
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('/ventas', [ReporteController::class, 'ventas'])->name('ventas');
        Route::get('/inventario', [ReporteController::class, 'inventario'])->name('inventario');
        Route::get('/produccion', [ReporteController::class, 'produccion'])->name('produccion');
        Route::get('/climatico', [ReporteController::class, 'climatico'])->name('climatico');
        Route::get('/actividades', [ReporteController::class, 'actividades'])->name('actividades');
        Route::get('/exportar/{tipo}', [ReporteController::class, 'exportar'])->name('exportar');
    });

    // ==============================
    // TRANSACCIONES AGRÍCOLAS
    // ==============================
    Route::prefix('transacciones')->name('transacciones.')->group(function () {
        // Dashboard
        Route::get('/', [TransaccionesController::class, 'index'])->name('index');

        // Siembra
        Route::get('/siembra', [TransaccionesController::class, 'siembraCreate'])->name('siembra.create');
        Route::post('/siembra', [TransaccionesController::class, 'siembraStore'])->name('siembra.store');

        // Fertilización
        Route::get('/fertilizacion', [TransaccionesController::class, 'fertilizacionCreate'])->name('fertilizacion.create');
        Route::post('/fertilizacion', [TransaccionesController::class, 'fertilizacionStore'])->name('fertilizacion.store');

        // Control de Plagas
        Route::get('/control-plagas', [TransaccionesController::class, 'controlPlagasCreate'])->name('control-plagas.create');
        Route::post('/control-plagas', [TransaccionesController::class, 'controlPlagasStore'])->name('control-plagas.store');

        // Riego
        Route::get('/riego', [TransaccionesController::class, 'riegoCreate'])->name('riego.create');
        Route::post('/riego', [TransaccionesController::class, 'riegoStore'])->name('riego.store');

        // Cosecha
        Route::get('/cosecha', [TransaccionesController::class, 'cosechaCreate'])->name('cosecha.create');
        Route::post('/cosecha', [TransaccionesController::class, 'cosechaStore'])->name('cosecha.store');

        // Venta
        Route::get('/venta', [TransaccionesController::class, 'ventaCreate'])->name('venta.create');
        Route::post('/venta', [TransaccionesController::class, 'ventaStore'])->name('venta.store');

        // AJAX endpoints
        Route::get('/api/lote/{id}', [TransaccionesController::class, 'getLoteInfo'])->name('api.lote');
        Route::get('/api/insumo/{id}', [TransaccionesController::class, 'getInsumoInfo'])->name('api.insumo');
    });
});