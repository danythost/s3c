<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $wallet = $user?->wallet;
        $transactions = $user?->transactions()->latest()->take(10)->get();
        $virtualAccount = $user?->virtualAccount;

        return view('wallet.index', compact('wallet', 'transactions', 'virtualAccount'));
    }
}
