<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidatureController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/offres',         [OffreController::class, 'index']);
Route::get('/offres/{offre}', [OffreController::class, 'show']);

// Authenticated routes
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me',       [AuthController::class, 'me']);

    // Profil
    Route::post('/profil', [ProfilController::class, 'store']);
    Route::get('/profil',  [ProfilController::class, 'show']);
    Route::put('/profil',  [ProfilController::class, 'update']);
    Route::post('/profil/competences',                  [ProfilController::class, 'addCompetence']);
    Route::delete('/profil/competences/{competenceId}', [ProfilController::class, 'removeCompetence']);

    // Candidatures
    Route::post('/offres/{offre}/candidater',          [CandidatureController::class, 'postuler']);
    Route::get('/mes-candidatures',                    [CandidatureController::class, 'mesCandidatures']);
    Route::get('/offres/{offre}/candidatures',         [CandidatureController::class, 'candidaturesRecues']);
    Route::patch('/candidatures/{candidature}/statut', [CandidatureController::class, 'changerStatut']);

    // Recruteur only
    Route::middleware('role:recruteur')->group(function () {
        Route::post('/offres',           [OffreController::class, 'store']);
        Route::put('/offres/{offre}',    [OffreController::class, 'update']);
        Route::delete('/offres/{offre}', [OffreController::class, 'destroy']);
    });

    // Admin only
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/users',            [AdminController::class, 'listUsers']);
        Route::delete('/users/{user}',  [AdminController::class, 'deleteUser']);
        Route::patch('/offres/{offre}', [AdminController::class, 'toggleOffre']);
    });
});
