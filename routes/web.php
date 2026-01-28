<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DataController;
use App\Http\Controllers\Web\WalletController;
use App\Http\Controllers\DashboardController; // User created this one
use App\Http\Controllers\Auth\LoginController; // User created this one
use App\Http\Controllers\Auth\RegisterController; // User created this one

Route::get('/', function () {
    $products = \App\Models\Product::where('status', 'active')->latest()->take(8)->get();
    return view('home', compact('products'));
})->name('home');

Route::get('/shop', [\App\Http\Controllers\Web\ShopController::class, 'index'])->name('shop');

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

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // VTU Management
    Route::get('/vtu/plans', [\App\Http\Controllers\Admin\VTUManagementController::class, 'plans'])->name('vtu.plans');
    Route::patch('/vtu/plans/{plan}/price', [\App\Http\Controllers\Admin\VTUManagementController::class, 'updatePrice'])->name('vtu.plans.update-price');
    Route::patch('/vtu/plans/{plan}/toggle', [\App\Http\Controllers\Admin\VTUManagementController::class, 'toggleStatus'])->name('vtu.plans.toggle-status');
    Route::post('/vtu/sync-epins', [\App\Http\Controllers\Admin\VTUManagementController::class, 'syncEpins'])->name('vtu.sync-epins');

    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

// Webhooks (Exclude from CSRF in bootstrap/app.php)
Route::post('/webhooks/flutterwave', [\App\Http\Controllers\Webhooks\FlutterwaveWebhookController::class, 'handle']);
