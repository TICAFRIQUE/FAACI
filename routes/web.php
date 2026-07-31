<?php

use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'accueil'])->name('accueil');

Route::prefix('a-propos')->name('apropos.')->group(function () {
    Route::get('/mission', [PageController::class, 'mission'])->name('mission');
    Route::get('/vision', [PageController::class, 'vision'])->name('vision');
    Route::get('/valeurs', [PageController::class, 'valeurs'])->name('valeurs');
    Route::get('/equipe', [PageController::class, 'equipe'])->name('equipe');
    Route::get('/histoire', [PageController::class, 'histoire'])->name('histoire');
});

Route::get('/activites', [PageController::class, 'activites'])->name('activites');

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/actualites', [ActualiteController::class, 'index'])->name('actualites.index');
Route::get('/actualites/{article}', [ActualiteController::class, 'show'])->name('actualites.show');

Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements.index');
Route::get('/evenements/{evenement}', [EvenementController::class, 'show'])->name('evenements.show');

Route::middleware('auth')->group(function () {
    Route::get('/compte/statut', [CompteController::class, 'statut'])->name('compte.statut');
});

// Redirection /dashboard → espace membre
Route::get('/dashboard', function () {
    return redirect()->route('membre.dashboard');
})->middleware(['auth', 'role:membre', 'statut:actif'])->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/membre.php';
