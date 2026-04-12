<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Routes protegees
Route::middleware('auth:api')->group(function () {
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me',       [AuthController::class, 'me']);
});
// Profile Routes
Route::middleware('auth:api')->group(function(){
    Route::post('/profil',[ProfilController::class , 'store']);
    Route::get('/profil',[ProfilController::class , 'show']);
    Route::put('/profil', [ProfilController::class, 'update']);
    Route::post('/profil/competences', [ProfilController::class, 'addCompetence']);
    Route::delete('/profil/competences/{competenceId}', [ProfilController::class, 'removeCompetence']);
});