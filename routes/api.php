<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//Route::apiResource('clients', ClientController::class);
// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Routes accessibles aux enquêteurs et administrateurs
    Route::middleware('role:ENQUETEUR,ADMINISTRATEUR')->group(function () {
        Route::apiResource('clients', ClientController::class);
    });
    // Routes réservées aux administrateurs
    Route::middleware('role:ADMINISTRATEUR')->group(function () {
        Route::get('/users', [AuthController::class, 'users']);
    });
});