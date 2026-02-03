<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

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
        ]);
    }
}
