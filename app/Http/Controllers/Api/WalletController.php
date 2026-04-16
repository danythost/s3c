<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Flutterwave\FlutterwaveService;
use App\Models\WalletTransaction;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Manually sync transactions from Flutterwave to credit the wallet.
     */
    public function refresh(Request $request, FlutterwaveService $flwService)
    {
        $user = $request->user();
        
        try {
            $results = $flwService->getTransactions($user->email);

            if (($results['status'] ?? '') !== 'success') {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not sync transactions. Please try again later.'
                ], 400);
            }

            $newCredits = 0;
            $totalCharges = 0;
            $processedCount = 0;
            $transactions = $results['data'] ?? [];

            // Get the funding charge from settings
            $fundingCharge = (float) Setting::getValue('wallet_funding_charge', 30);

            foreach ($transactions as $tx) {
                // Only process successful, credited transactions
                if (($tx['status'] ?? '') === 'successful' && $tx['amount'] > 0) {
                    // Check if already processed
                    $exists = WalletTransaction::where('reference', (string)$tx['id'])->exists();
                    
                    if (!$exists) {
                        DB::transaction(function () use ($user, $tx, &$newCredits, &$totalCharges, &$processedCount, $fundingCharge) {
                            $wallet = $user->wallet ?: $user->wallet()->create(['balance' => 0]);
                            
                            $depositAmount = $tx['amount'];
                            $chargeAmount = min($fundingCharge, $depositAmount); // Don't charge more than the deposit
                            $netCredit = $depositAmount - $chargeAmount;
                            
                            // Credit the net amount (deposit minus charge)
                            $wallet->increment('balance', $netCredit);
                            
                            // Record the deposit (full amount for transparency)
                            WalletTransaction::create([
                                'user_id'   => $user->id,
                                'reference' => (string)$tx['id'],
                                'amount'    => $depositAmount,
                                'type'      => 'credit',
                                'status'    => 'success',
                                'source'    => 'wallet_funding',
                                'meta'      => $tx,
                            ]);

                            // Record the funding charge as a separate debit
                            if ($chargeAmount > 0) {
                                WalletTransaction::create([
                                    'user_id'   => $user->id,
                                    'reference' => 'CHG-' . $tx['id'],
                                    'amount'    => $chargeAmount,
                                    'type'      => 'debit',
                                    'status'    => 'success',
                                    'source'    => 'funding_charge',
                                    'meta'      => ['deposit_ref' => (string)$tx['id'], 'charge' => $chargeAmount],
                                ]);
                            }
                            
                            $newCredits += $netCredit;
                            $totalCharges += $chargeAmount;
                            $processedCount++;
                        });
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => $newCredits > 0 
                    ? "Wallet refreshed! Credited ₦" . number_format($newCredits, 2) . " (₦" . number_format($totalCharges, 2) . " service fee applied)"
                    : "No new deposits found.",
                'new_credits' => $newCredits,
                'total_charges' => $totalCharges,
                'processed_count' => $processedCount,
                'balance' => $user->fresh()->wallet_balance
            ]);

        } catch (\Throwable $e) {
            Log::error('Wallet Sync API Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while syncing your wallet.'
            ], 500);
        }
    }
}
