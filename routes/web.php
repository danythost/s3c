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
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // VTU Routes
    Route::get('/vtu/data', [DataController::class, 'index'])->name('vtu.data.index');
    Route::post('/vtu/data/purchase', [DataController::class, 'purchase'])->name('vtu.data.purchase');
    
    Route::get('/vtu/airtime', [\App\Http\Controllers\Web\AirtimeController::class, 'index'])->name('vtu.airtime.index');
    Route::post('/vtu/airtime/purchase', [\App\Http\Controllers\Web\AirtimeController::class, 'purchase'])->name('vtu.airtime.purchase');

    // Wallet Routes
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');

    // Auth
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin Routes
// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->middleware('throttle:admin_login');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // VTU Management
        Route::get('/vtu/plans', [\App\Http\Controllers\Admin\VTUManagementController::class, 'plans'])->name('vtu.plans');
        Route::post('/vtu/plans', [\App\Http\Controllers\Admin\VTUManagementController::class, 'storePlan'])->name('vtu.plans.store');
        Route::post('/vtu/plans/import', [\App\Http\Controllers\Admin\VTUManagementController::class, 'importPlans'])->name('vtu.plans.import');
        Route::patch('/vtu/plans/{plan}/price', [\App\Http\Controllers\Admin\VTUManagementController::class, 'updatePrice'])->name('vtu.plans.update-price');
        Route::patch('/vtu/plans/{plan}/toggle', [\App\Http\Controllers\Admin\VTUManagementController::class, 'toggleStatus'])->name('vtu.plans.toggle-status');
        
        Route::get('/vtu/airtime', [\App\Http\Controllers\Admin\VTUManagementController::class, 'airtime'])->name('vtu.airtime');
        Route::patch('/vtu/airtime/{control}', [\App\Http\Controllers\Admin\VTUManagementController::class, 'updateAirtime'])->name('vtu.airtime.update');

        // Product Management
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

        // Order Management
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/retry', [\App\Http\Controllers\Admin\OrderController::class, 'retry'])->name('orders.retry');

        // User Management
        Route::patch('/users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Finance & Wallet
        Route::get('/finance', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
        Route::get('/finance/transactions', [\App\Http\Controllers\Admin\FinanceController::class, 'transactions'])->name('finance.transactions');
        Route::get('/finance/fund', [\App\Http\Controllers\Admin\FinanceController::class, 'fund'])->name('finance.fund');
        Route::post('/finance/fund', [\App\Http\Controllers\Admin\FinanceController::class, 'storeFund'])->name('finance.fund.store');
        Route::get('/finance/provider', [\App\Http\Controllers\Admin\FinanceController::class, 'provider'])->name('finance.provider');

        // Content & Communication
        Route::get('/content', [\App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content.index');
        Route::get('/content/logs', [\App\Http\Controllers\Admin\ContentController::class, 'logs'])->name('content.logs');
        Route::post('/content/announcements', [\App\Http\Controllers\Admin\ContentController::class, 'storeAnnouncement'])->name('content.announcements.store');
        Route::put('/content/announcements/{announcement}', [\App\Http\Controllers\Admin\ContentController::class, 'updateAnnouncement'])->name('content.announcements.update');
        Route::patch('/content/announcements/{announcement}/toggle', [\App\Http\Controllers\Admin\ContentController::class, 'toggleAnnouncement'])->name('content.announcements.toggle');
        Route::delete('/content/announcements/{announcement}', [\App\Http\Controllers\Admin\ContentController::class, 'deleteAnnouncement'])->name('content.announcements.destroy');

        // System Configuration
        Route::get('/settings', [\App\Http\Controllers\Admin\SystemConfigController::class, 'index'])->name('settings.index');
        Route::put('/settings/providers/{provider}', [\App\Http\Controllers\Admin\SystemConfigController::class, 'updateProvider'])->name('settings.providers.update');
        Route::post('/settings/pricing', [\App\Http\Controllers\Admin\SystemConfigController::class, 'updatePricing'])->name('settings.pricing.update');
        Route::get('/settings/audit', [\App\Http\Controllers\Admin\SystemConfigController::class, 'index'])->name('settings.audit'); // Same index with tab
        
        // Maintenance
        Route::post('/settings/maintenance/clear-cache', [\App\Http\Controllers\Admin\SystemConfigController::class, 'clearCache'])->name('settings.maintenance.clear-cache');
        Route::post('/settings/maintenance/failed-jobs', [\App\Http\Controllers\Admin\SystemConfigController::class, 'manageFailedJobs'])->name('settings.maintenance.failed-jobs');

        // Security & Audit
        Route::get('/security', [\App\Http\Controllers\Admin\SecurityController::class, 'index'])->name('security.index');
    });
});

// Webhooks (Exclude from CSRF in bootstrap/app.php)
Route::post('/webhooks/flutterwave', [\App\Http\Controllers\Webhooks\FlutterwaveWebhookController::class, 'handle']);
