<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TokenAuthenticationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware(TokenAuthenticationMiddleware::class);

Route::middleware([TokenAuthenticationMiddleware::class, RedirectIfAuthenticated::class])->group(function () {
    Route::get('login', function () {
        return view('login');
    })->name('login');

    Route::get('phone-login', function(){
        return view('phone-login');
    })->name('phone-login');
});


Route::get('user/about', function () {
    $user = Auth::guard('api')->user();
    return view('userinfo', compact('user'));
})->name('userInfo')
    ->middleware(TokenAuthenticationMiddleware::class);
