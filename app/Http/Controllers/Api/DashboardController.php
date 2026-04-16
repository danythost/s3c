<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Stats calculations
        $totalActivityTotal = $user->transactions()->count();

        // Monthly stats
        $monthStart = now()->startOfMonth();
        
        $monthlySuccessfulSales = $user->transactions()
            ->whereIn('status', ['success', 'completed'])
            ->whereIn('source', ['data', 'airtime', 'exam_pin'])
            ->where('created_at', '>=', $monthStart)
            ->count();

        $monthlyTotalSales = $user->transactions()
            ->whereIn('source', ['data', 'airtime', 'exam_pin'])
            ->where('created_at', '>=', $monthStart)
            ->count();
        
        $successRate = $monthlyTotalSales > 0 ? ($monthlySuccessfulSales / $monthlyTotalSales) * 100 : 100;
        
        $monthlyVolume = $user->transactions()
            ->whereIn('status', ['success', 'completed'])
            ->where('type', 'debit')
            ->where('created_at', '>=', $monthStart)
            ->sum('amount');

        $activities = $user->transactions()
            ->latest()
            ->take(6)
            ->get();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'tier' => $user->tier,
                'wallet_balance' => $user->wallet ? $user->wallet->balance : 0,
                'virtual_account' => $user->virtualAccount,
            ],
            'activities' => $activities,
            'stats' => [
                'total_activity' => $totalActivityTotal,
                'success_rate' => round($successRate, 1),
                'monthly_volume' => $monthlyVolume,
            ]
        ]);
    }
}
