<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BienController;
use App\Http\Controllers\Api\ContratController;
use App\Http\Controllers\Api\LocataireController;
use App\Http\Controllers\Api\ProprietaireController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques — Authentification
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Routes protégées — Nécessite un token Sanctum valide
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Propriétaires
    Route::apiResource('proprietaires', ProprietaireController::class);

    // Biens immobiliers
    Route::apiResource('biens', BienController::class);

    // Locataires
    Route::apiResource('locataires', LocataireController::class);

    // Contrats
    Route::apiResource('contrats', ContratController::class);
});
