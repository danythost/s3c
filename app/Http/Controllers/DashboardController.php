<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Stats calculations
        $totalOrders = $user->orders()->count();
        $totalA2C = $user->a2cRequests()->count();
        $totalActivity = $totalOrders + $totalA2C;

        // Monthly stats
        $monthStart = now()->startOfMonth();
        $monthlySuccessfulOrders = $user->orders()
            ->whereIn('status', ['success', 'completed'])
            ->where('created_at', '>=', $monthStart)
            ->count();
        $monthlyOrders = $user->orders()
            ->where('created_at', '>=', $monthStart)
            ->count();
        
        $successRate = $monthlyOrders > 0 ? ($monthlySuccessfulOrders / $monthlyOrders) * 100 : 100;
        
        $monthlyVolume = $user->orders()
            ->whereIn('status', ['success', 'completed'])
            ->where('created_at', '>=', $monthStart)
            ->sum('amount');

        $orders = $user->orders()->latest()->take(10)->get()->map(function($item) {
            $item->activity_type = 'order';
            return $item;
        });

        $a2c = $user->a2cRequests()->latest()->take(10)->get()->map(function($item) {
            $item->activity_type = 'a2c';
            return $item;
        });

        $activities = $orders->concat($a2c)
            ->sortByDesc('created_at')
            ->take(6);

        return view('dashboard.index', [
            'user' => $user,
            'activities' => $activities,
            'stats' => [
                'total_activity' => $totalActivity,
                'success_rate' => $successRate,
                'monthly_volume' => $monthlyVolume,
            ]
        ]);
    }
}
