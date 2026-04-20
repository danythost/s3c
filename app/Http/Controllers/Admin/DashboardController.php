<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\DataPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\WalletTransaction;
use App\Contracts\VTU\VTUProviderInterface;

class DashboardController extends Controller
{
    public function index(VTUProviderInterface $vtuService)
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count() + WalletTransaction::count(),
            'total_wallets_balance' => Wallet::sum('balance'),
            'total_revenue' => Order::whereIn('status', ['success', 'completed'])->sum('amount') + WalletTransaction::whereIn('status', ['success', 'completed'])->sum('amount'),
            'active_data_plans' => DataPlan::where('is_active', true)->count(),
            'today_transactions' => Order::whereDate('created_at', today())->count() + WalletTransaction::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->whereIn('status', ['success', 'completed'])->sum('amount') + WalletTransaction::whereDate('created_at', today())->whereIn('status', ['success', 'completed'])->sum('amount'),
            'successful_orders' => Order::whereIn('status', ['success', 'completed'])->count() + WalletTransaction::whereIn('status', ['success', 'completed'])->count(),
            'failed_orders' => Order::where('status', 'failed')->count() + WalletTransaction::where('status', 'failed')->count(),
            'total_profit' => WalletTransaction::whereIn('status', ['success', 'completed'])->sum('profit'),
            'provider_balance' => Cache::remember('provider_balance', 300, function () use ($vtuService) { // Cache for 5 mins
                try {
                    $response = $vtuService->getBalance();
                    if ($response->success) {
                        return $response->data['balance'] ?? $response->data['wallet_credit'] ?? 0;
                    }
                    return null;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Dashboard Balance Error: " . $e->getMessage());
                    return null;
                }
            }),
        ];

        $recent_transactions = WalletTransaction::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_transactions'));
    }
}
