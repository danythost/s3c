<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\DataPlan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_wallets_balance' => Wallet::sum('balance'),
            'total_revenue' => Order::where('status', 'success')->sum('amount'),
            'active_data_plans' => DataPlan::where('is_active', true)->count(),
        ];

        $recent_orders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }
}
