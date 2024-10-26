<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest') -> name('loginApi');
    Route::post('/send-sms-code', [VerificationController::class, 'sendSmsCode']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware(App\Http\Middleware\TokenAuthenticationMiddleware::class);
    Route::get('/telegram/login', [AuthController::class, 'loginWithTelegram'])->name('loginWithTelegram');
    Route::post('/phone/login', [AuthController::class, 'loginWithPhone'])->name('loginWithPhone');
});