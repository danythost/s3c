<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\Flutterwave\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FlutterwaveWebhookController extends Controller
{
    public function handle(Request $request, FlutterwaveService $flwService)
    {
        $payload = $request->all();
        $event = $payload['event'] ?? 'unknown';

        // 1. Log the payload for debugging (File & Database)
        Log::info('Flutterwave Webhook Received', [
            'url' => $request->fullUrl(),
            'event' => $event,
            'tx_ref' => $payload['tx_ref'] ?? 'N/A'
        ]);

        try {
            \App\Services\Logger\ApiLogger::log(
                'flutterwave-webhook',
                $request->method(),
                $request->fullUrl(),
                $payload,
                ['status' => 'received', 'event' => $event],
                200,
                0
            );
        } catch (\Throwable $e) {
            Log::error('Failed to log Flutterwave Webhook to database: ' . $e->getMessage());
        }

        // 2. Verify Secret Hash
        $secretHash = config('services.flutterwave.secret_hash');
        $signature = $request->header('verif-hash');

        if ($secretHash && (!$signature || ($signature !== $secretHash))) {
            Log::warning('Flutterwave Webhook: Invalid Secret Hash');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($event === 'charge.completed' && ($payload['status'] ?? '') === 'successful') {
            return $this->processPayment($payload, $flwService);
        }

        return response()->json(['status' => 'ignored']);
    }

    protected function processPayment(array $payload, FlutterwaveService $flwService)
    {
        $transactionId = (string) $payload['id'];
        $amount = $payload['amount'];
        $currency = $payload['currency'];
        $txRef = $payload['tx_ref'];

        // 2. Double check transaction with FLW API
        $verification = $flwService->verifyTransaction($transactionId);

        if (($verification['status'] ?? '') !== 'success' || ($verification['data']['status'] ?? '') !== 'successful') {
            Log::error('Flutterwave Webhook: Verification Failed', ['id' => $transactionId]);
            return response()->json(['message' => 'Verification failed'], 400);
        }

        // 3. Prevent duplicate processing
        if (WalletTransaction::where('reference', $transactionId)->exists()) {
            return response()->json(['status' => 'already processed']);
        }

        // 4. Identify user (either by email or tx_ref if we tagged it)
        $email = $verification['data']['customer']['email'];
        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::error('Flutterwave Webhook: User not found', ['email' => $email]);
            return response()->json(['message' => 'User not found'], 404);
        }

        // 5. Get the funding charge from settings
        $fundingCharge = (float) \App\Models\Setting::getValue('wallet_funding_charge', 30);
        $chargeAmount = min($fundingCharge, $amount);
        $netCredit = $amount - $chargeAmount;

        // 6. Credit Wallet (net amount after charge)
        DB::transaction(function () use ($user, $amount, $netCredit, $chargeAmount, $transactionId, $payload) {
            $wallet = $user->wallet ?: $user->wallet()->create(['balance' => 0]);
            $wallet->increment('balance', $netCredit);

            WalletTransaction::create([
                'user_id'   => $user->id,
                'reference' => $transactionId,
                'amount'    => $amount,
                'type'      => 'credit',
                'status'    => 'success',
                'source'    => 'wallet_funding',
                'meta'      => $payload,
            ]);

            // Record the funding charge
            if ($chargeAmount > 0) {
                WalletTransaction::create([
                    'user_id'   => $user->id,
                    'reference' => 'CHG-' . $transactionId,
                    'amount'    => $chargeAmount,
                    'type'      => 'debit',
                    'status'    => 'success',
                    'source'    => 'funding_charge',
                    'meta'      => ['deposit_ref' => $transactionId, 'charge' => $chargeAmount],
                ]);
            }
        });

        return response()->json(['status' => 'success']);
    }
}
