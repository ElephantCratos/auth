<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest') -> name('loginApi');
    Route::post('logout', [AuthController::class, 'logout'])->middleware(App\Http\Middleware\TokenAuthenticationMiddleware::class);
    Route::get('/telegram/callback', [AuthController::class, 'telegramAuthCallback'])->name('telegramAuthCallback');
});