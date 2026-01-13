<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::middleware('guest')->controller(AuthController::class)->prefix('login')->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/', 'login');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/data', function () {
        return view('data');
    })->name('data');
    
    // File download routes
    Route::get('/download/categories', function () {
        return \App\Services\FilesServices::downloadCategoriesFile();
    })->name('download.categories');
    
    Route::get('/download/products', function () {
        return \App\Services\FilesServices::downloadProductsFile();
    })->name('download.products');
});
