<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Actions\Wallet\ReverseWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EpinsWebhookController extends Controller
{
    public function __construct(
        protected ReverseWallet $reverseWallet
    ) {}

    /**
     * Handle the incoming Epins webhook.
     */
    public function handle(Request $request)
    {
        // 1. Log the payload for debugging (File & Database)
        Log::channel('daily')->info('Epins Webhook Received:', $request->all());

        try {
            \App\Services\Logger\ApiLogger::log(
                'epins-webhook', 
                'POST', 
                $request->fullUrl(), 
                $request->all(), 
                ['status' => 'processing'], 
                200, 
                0
            );
        } catch (\Throwable $e) {
            // Ignore log failure
        }

        // 2. Identify the Reference
        // Epins usually sends 'ref', 'reference', or 'client_reference'
        $reference = $request->input('ref') 
                  ?? $request->input('reference') 
                  ?? $request->input('client_reference')
                  ?? $request->input('trans_ref');

        if (!$reference) {
            return response()->json(['message' => 'Reference not found'], 400);
        }

        // 3. Find the Transaction
        $txn = WalletTransaction::where('reference', $reference)->first();

        if (!$txn) {
            Log::warning("Epins Webhook: Transaction not found for ref: {$reference}");
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // 4. Determine Status from Payload
        // Looking for common status indicators
        $statusStr = strtolower($request->input('status', ''));
        $respDescription = strtolower($request->input('description', ''));
        
        // Check for success indicators
        $isSuccess = $statusStr === 'success' 
                  || $statusStr === 'successful' 
                  || $statusStr === 'delivered'
                  || $request->input('code') == '101'
                  || str_contains($respDescription, 'successful');

        // Check for failure indicators
        $isFailed = $statusStr === 'failed' 
                 || $statusStr === 'reversed' 
                 || $statusStr === 'refunded'
                 || str_contains($respDescription, 'failed');

        // 5. Process Update
        return DB::transaction(function () use ($txn, $request, $isSuccess, $isFailed) {
            
            // Case A: Webhook says SUCCESS
            if ($isSuccess) {
                if ($txn->status !== 'success') {
                    $txn->update([
                        'status' => 'success',
                        'meta' => array_merge($txn->meta ?? [], ['webhook' => $request->all()])
                    ]);
                    Log::info("Epins Webhook: Transaction {$txn->reference} updated to SUCCESS.");
                }
                return response()->json(['status' => 'success', 'message' => 'Transaction updated']);
            }

            // Case B: Webhook says FAILED
            if ($isFailed) {
                // If it was previously marked as success/pending, we need to REFUND
                if ($txn->status !== 'failed') {
                    
                    // Refund logic
                    if ($txn->type === 'debit') {
                        $this->reverseWallet->execute($txn->user_id, $txn->amount);
                    }

                    $txn->update([
                        'status' => 'failed',
                        'meta' => array_merge($txn->meta ?? [], ['webhook' => $request->all()])
                    ]);
                    Log::info("Epins Webhook: Transaction {$txn->reference} marked as FAILED and Refunded.");
                }
                return response()->json(['status' => 'success', 'message' => 'Transaction marked failed']);
            }

            // Case C: Status Unknown/Processing
            Log::info("Epins Webhook: Status '{$request->input('status')}' not definitive for Ref {$txn->reference}");
            return response()->json(['status' => 'ignored', 'message' => 'Status not actionable']);
        });
    }
}
