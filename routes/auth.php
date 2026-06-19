<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/inscription', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisteredUserController::class, 'store']);

    Route::get('/connexion', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/connexion', [AuthenticatedSessionController::class, 'store']);

    Route::get('/mot-de-passe/oublie', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/mot-de-passe/oublie', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/mot-de-passe/reinitialiser/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/mot-de-passe/reinitialiser', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
