<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\DataPlan;
use App\Services\VTU\EpinsVTUService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\WalletTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count() + WalletTransaction::count(),
            'total_wallets_balance' => Wallet::sum('balance'),
            'total_revenue' => Order::where('status', 'success')->sum('amount') + WalletTransaction::where('status', 'completed')->sum('amount'),
            'active_data_plans' => DataPlan::where('is_active', true)->count(),
            'today_transactions' => Order::whereDate('created_at', today())->count() + WalletTransaction::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->where('status', 'success')->sum('amount') + WalletTransaction::whereDate('created_at', today())->where('status', 'completed')->sum('amount'),
            'successful_orders' => Order::where('status', 'success')->count() + WalletTransaction::where('status', 'completed')->count(),
            'failed_orders' => Order::where('status', 'failed')->count() + WalletTransaction::where('status', 'failed')->count(),
            'provider_balance' => Cache::remember('provider_balance', 300, function () { // Cache for 5 mins
                try {
                    $service = new EpinsVTUService();
                    $response = $service->getBalance();
                    return $response->success ? ($response->data['balance'] ?? 0) : null;
                } catch (\Exception $e) {
                    return null;
                }
            }),
        ];

        $recent_orders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }
}
