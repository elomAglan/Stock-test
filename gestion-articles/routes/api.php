<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuditLogController;

/*
|--------------------------------------------------------------------------
| Routes API
|--------------------------------------------------------------------------
*/

// -------------------
// Routes publiques
// -------------------

// Inscription et connexion (pas tracées)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// -------------------
// Routes protégées (auth:sanctum + audit log)
// -------------------
Route::middleware(['auth:sanctum'])->group(function () {

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);

    // Récupérer utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Historique des actions
    Route::get('/logs', [AuditLogController::class, 'index']);

    // Articles & catégories (CRUD)
    Route::apiResource('articles', ArticleController::class);
    Route::apiResource('categories', CategoryController::class);

});
