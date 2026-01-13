<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/data', function () {
    return view('data');
})->name('data');

Route::middleware('guest')->controller(AuthController::class)->prefix('login')->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/', 'login');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
