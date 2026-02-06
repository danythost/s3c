<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        // Load relationships needed for the profile view
        $user->load(['wallet', 'transactions' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('profile.show', compact('user'));
    }
}
