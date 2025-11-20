<?php

use Illuminate\Support\Facades\Route;

// ======================================================
//  API DE AGRONEXUS - RUTAS REST COMPLETAS
//  Todas devuelven JSON y usan controladores en Api/
// ======================================================

// IMPORTACIÓN DE CONTROLADORES -------------------------
use App\Http\Controllers\Api\TipoActividadController;
use App\Http\Controllers\Api\PrioridadController;
use App\Http\Controllers\Api\TipoInsumoController;
use App\Http\Controllers\Api\UnidadMedidaController;
use App\Http\Controllers\Api\CultivoController;
use App\Http\Controllers\Api\EstadoLoteTipoController;
use App\Http\Controllers\Api\DestinoProduccionController;
use App\Http\Controllers\Api\EstadoLoteInsumoController;
use App\Http\Controllers\Api\HistorialEstadoLoteController;

use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\UsuarioRolController;

use App\Http\Controllers\Api\LoteController;
use App\Http\Controllers\Api\EstadoLoteController;
use App\Http\Controllers\Api\ProduccionController;

use App\Http\Controllers\Api\InsumoController;
use App\Http\Controllers\Api\LoteInsumoController;
use App\Http\Controllers\Api\ActividadController;

use App\Http\Controllers\Api\ClimaController;
use App\Http\Controllers\Api\VentaController;

use App\Http\Controllers\Api\AuthController;

Route::name('api.')->group(function () {

    // ENDPOINT DE PRUEBA
    Route::get('/test-api', function () {
        return response()->json(['ok' => true]);
    });

    // GRUPO: CATÁLOGOS
    Route::apiResource('tipoactividades', TipoActividadController::class);
    Route::apiResource('prioridades', PrioridadController::class);
    Route::apiResource('tipoinsumos', TipoInsumoController::class);
    Route::apiResource('unidadesmedida', UnidadMedidaController::class);
    Route::apiResource('cultivos', CultivoController::class);
    Route::apiResource('estadolote-tipos', EstadoLoteTipoController::class);
    Route::apiResource('destinoproducciones', DestinoProduccionController::class);
    Route::apiResource('estadolote-insumos', EstadoLoteInsumoController::class);

    // GRUPO: USUARIOS Y ROLES
    Route::apiResource('roles', RolController::class);
    Route::apiResource('usuarios', UsuarioController::class);
    Route::apiResource('usuario-roles', UsuarioRolController::class);

    // GRUPO: LOTES Y PRODUCCIÓN
    Route::apiResource('lotes', LoteController::class);
    Route::apiResource('estadolotes', EstadoLoteController::class);
    Route::apiResource('producciones', ProduccionController::class);
    Route::apiResource('historial-estados-lote', HistorialEstadoLoteController::class);

    // GRUPO: INSUMOS Y APLICACIONES
    Route::apiResource('insumos', InsumoController::class);
    Route::apiResource('lote-insumos', LoteInsumoController::class);

    // ACTIVIDADES
    Route::apiResource('actividades', ActividadController::class);

    // CLIMA
    Route::apiResource('climas', ClimaController::class);

    // VENTAS
    Route::apiResource('ventas', VentaController::class);

    // AUTH
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login',    [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me',     [AuthController::class, 'me'])->name('me');
        Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
    });

});