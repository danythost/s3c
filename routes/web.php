<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DataController;
use App\Http\Controllers\Web\WalletController;
use App\Http\Controllers\DashboardController; // User created this one
use App\Http\Controllers\Auth\LoginController; // User created this one
use App\Http\Controllers\Auth\RegisterController; // User created this one

Route::get('/', function () {
    return view('home');
})->name('home');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // VTU Routes
    Route::get('/vtu/data', [DataController::class, 'index'])->name('vtu.data.index');
    Route::post('/vtu/data/purchase', [DataController::class, 'purchase'])->name('vtu.data.purchase');

    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');

    // Auth
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
