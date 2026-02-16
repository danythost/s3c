<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Stats calculations from WalletTransactions
        $totalActivityTotal = $user->transactions()->count();

        // Monthly stats
        $monthStart = now()->startOfMonth();
        
        $monthlySuccessfulSales = $user->transactions()
            ->whereIn('status', ['success', 'completed'])
            ->whereIn('source', ['data', 'airtime'])
            ->where('created_at', '>=', $monthStart)
            ->count();

        $monthlyTotalSales = $user->transactions()
            ->whereIn('source', ['data', 'airtime'])
            ->where('created_at', '>=', $monthStart)
            ->count();
        
        $successRate = $monthlyTotalSales > 0 ? ($monthlySuccessfulSales / $monthlyTotalSales) * 100 : 100;
        
        $monthlyVolume = $user->transactions()
            ->whereIn('status', ['success', 'completed'])
            ->where('type', 'debit') // Spending volume
            ->where('created_at', '>=', $monthStart)
            ->sum('amount');

        $activities = $user->transactions()
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.index', [
            'user' => $user,
            'activities' => $activities,
            'stats' => [
                'total_activity' => $totalActivityTotal,
                'success_rate' => $successRate,
                'monthly_volume' => $monthlyVolume,
            ]
        ]);
    }
}
