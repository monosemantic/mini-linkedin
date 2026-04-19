<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidatureController;



// 401 Unauthorized
Route::get('/login', function () {
    return response()->json(['message' => 'Non authentifié'], 401);
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Routes protegees
Route::middleware('auth:api')->group(function () {
    // Auth protected routes
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me',       [AuthController::class, 'me']);
    // Profile routes
    Route::post('/profil',[ProfilController::class , 'store']);
    Route::get('/profil',[ProfilController::class , 'show']);
    Route::put('/profil', [ProfilController::class, 'update']);
    Route::post('/profil/competences', [ProfilController::class, 'addCompetence']);
    Route::delete('/profil/competences/{competenceId}', [ProfilController::class, 'removeCompetence']);
    // Candidatures
    Route::post('/offres/{offre}/candidater', [CandidatureController::class, 'postuler']);
    Route::get('/mes-candidatures', [CandidatureController::class, 'mesCandidatures']);
    Route::get('/offres/{offre}/candidatures', [CandidatureController::class, 'candidaturesRecues']);
    Route::patch('/candidatures/{candidature}/statut', [CandidatureController::class, 'changerStatut']);
});

// tout le monde peut voir les offres
Route::get('/offres', [OffreController::class, 'index']);
Route::get('/offres/{offre}', [OffreController::class, 'show']);

Route::middleware(['auth:api', 'role:recruteur'])->group(function () {
    Route::post('/offres', [OffreController::class, 'store']);
    Route::put('/offres/{offre}', [OffreController::class, 'update']);
    Route::delete('/offres/{offre}', [OffreController::class, 'destroy']);
});

// admin
Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'listUsers']);
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);
    Route::patch('/offres/{offre}', [AdminController::class, 'toggleOffre']);
});
