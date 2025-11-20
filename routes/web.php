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

// ======================================================
// HOME
// ======================================================
Route::get('/', function () {
    return view('home');
});

// ======================================================
// RUTAS RESOURCE WEB (CRUD COMPLETO)
// ======================================================

Route::resource('actividades', ActividadController::class);
Route::resource('climas', ClimaController::class);
Route::resource('cultivos', CultivoController::class);
Route::resource('estadolotes', EstadoLoteController::class);
Route::resource('estado-lote-insumos', EstadoLoteInsumoController::class);
Route::resource('estado-lote-tipos', EstadoLoteTipoController::class);
Route::resource('historial-estados-lote', HistorialEstadoLoteController::class);
Route::resource('insumos', InsumoController::class);
Route::resource('lotes', LoteController::class);
Route::resource('lote-insumos', LoteInsumoController::class);
Route::resource('prioridades', PrioridadController::class);
Route::resource('producciones', ProduccionController::class);
Route::resource('tipo-actividad', TipoActividadController::class);
Route::resource('tipo-insumos', TipoInsumoController::class);
Route::resource('unidades-medida', UnidadMedidaController::class);
Route::resource('ventas', VentaController::class);

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