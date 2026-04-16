<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [\App\Http\Controllers\Api\Auth\RegisterController::class, 'register'])->name('api.register');

// Public Content Routes
Route::prefix('public')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Api\PublicContentController::class, 'products']);
    Route::get('/pages/{slug}', [\App\Http\Controllers\Api\PublicContentController::class, 'page']);
    Route::get('/settings', [\App\Http\Controllers\Api\PublicContentController::class, 'settings']);
    Route::get('/announcements', [\App\Http\Controllers\Api\PublicContentController::class, 'announcements']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->loadMissing('virtualAccount');
    });
    
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index'])->name('api.dashboard');
    
    // VTU Data
    Route::get('/vtu/data/plans', [\App\Http\Controllers\Api\DataController::class, 'index'])->name('api.vtu.data.plans');
    Route::post('/vtu/data/purchase', [\App\Http\Controllers\Api\DataController::class, 'purchase'])->name('api.vtu.data.purchase');
    
    // VTU Airtime
    Route::post('/vtu/airtime/purchase', [\App\Http\Controllers\Api\AirtimeController::class, 'purchase'])->name('api.vtu.airtime.purchase');
    
    // Wallet
    Route::get('/wallet/history', [\App\Http\Controllers\Api\WalletController::class, 'history'])->name('api.wallet.history');
    Route::post('/wallet/refresh', [\App\Http\Controllers\Api\WalletController::class, 'refresh'])->name('api.wallet.refresh');
    
    // Profile & Security
    Route::post('/profile/update', [\App\Http\Controllers\Api\ProfileController::class, 'update'])->name('api.profile.update');
    Route::post('/password/update', [\App\Http\Controllers\Api\ProfileController::class, 'updatePassword'])->name('api.password.update');
    
    // Support
    Route::post('/support/report', [\App\Http\Controllers\Api\SupportController::class, 'report'])->name('api.support.report');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});
