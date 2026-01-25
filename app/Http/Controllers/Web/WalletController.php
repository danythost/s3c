<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $wallet = $request->user()?->wallet;
        $transactions = $request->user()?->transactions()->latest()->take(10)->get();

        return view('wallet.index', compact('wallet', 'transactions'));
    }
}
