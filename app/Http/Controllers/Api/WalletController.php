<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Get paginated transaction history for the authenticated user.
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $transactions = $user->transactions()
            ->latest()
            ->paginate($request->get('limit', 20));

        return response()->json([
            'transactions' => $transactions,
            'user' => [
                'name' => $user->name,
                'wallet_balance' => $user->wallet ? $user->wallet->balance : 0,
                'virtual_account' => $user->virtualAccount,
            ]
        ]);
    }
}
