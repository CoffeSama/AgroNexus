<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Prueba básica
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});