<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Flutterwave\FlutterwaveService;
use App\Models\VirtualAccount;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index(Request $request, FlutterwaveService $flwService)
    {
        $user = $request->user();
        $virtualAccount = $user->virtualAccount;

        // On-demand generation if missing
        if (!$virtualAccount) {
            $result = $flwService->createVirtualAccount([
                'user_id'   => $user->id,
                'email'     => $user->email,
                'firstname' => explode(' ', $user->name)[0] ?? 'User',
                'lastname'  => explode(' ', $user->name)[1] ?? $user->id,
                'phone'     => $user->phone ?? null,
                'bvn'       => config('services.flutterwave.test_bvn'),
            ]);

            if ($result['success']) {
                $virtualAccount = VirtualAccount::create([
                    'user_id'           => $user->id,
                    'account_number'    => $result['account_number'],
                    'bank_name'         => $result['bank_name'],
                    'account_reference' => $result['account_reference'],
                    'provider'          => 'flutterwave',
                ]);
            } else {
                Log::error("Manual VA creation failed for user {$user->id}: " . ($result['message'] ?? 'Unknown error'));
            }
        }

        $wallet = $user->wallet;
        $transactions = $user->transactions()->latest()->take(10)->get();

        return view('wallet.index', compact('wallet', 'transactions', 'virtualAccount'));
    }

    public function refresh(Request $request, FlutterwaveService $flwService)
    {
        $user = $request->user();
        $results = $flwService->getTransactions($user->email);

        if (($results['status'] ?? '') !== 'success') {
            return back()->with('error', 'Could not sync transactions. Please try again later.');
        }

        $newCredits = 0;
        $transactions = $results['data'] ?? [];

        foreach ($transactions as $tx) {
            // Only process successful, credited transactions of type 'deposit' or 'account'
            if ($tx['status'] === 'successful' && $tx['amount'] > 0) {
                // Check if already processed
                $exists = WalletTransaction::where('reference', (string)$tx['id'])->exists();
                
                if (!$exists) {
                    DB::transaction(function () use ($user, $tx, &$newCredits) {
                        $wallet = $user->wallet ?: $user->wallet()->create(['balance' => 0]);
                        $wallet->increment('balance', $tx['amount']);
                        
                        WalletTransaction::create([
                            'user_id'   => $user->id,
                            'reference' => (string)$tx['id'],
                            'amount'    => $tx['amount'],
                            'type'      => 'credit',
                            'status'    => 'success',
                            'source'    => 'wallet_funding',
                            'meta'      => $tx,
                        ]);
                        
                        $newCredits += $tx['amount'];
                    });
                }
            }
        }

        if ($newCredits > 0) {
            return back()->with('success', "Wallet refreshed! Credited ₦ " . number_format($newCredits, 2));
        }

        return back()->with('info', 'No new successful deposits found.');
    }
}
