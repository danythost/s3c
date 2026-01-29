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
        // 1. Verify Secret Hash
        $secretHash = config('services.flutterwave.secret_hash');
        $signature = $request->header('verif-hash');

        if (!$signature || ($signature !== $secretHash)) {
            Log::warning('Flutterwave Webhook: Invalid Secret Hash');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? '';

        Log::info('Flutterwave Webhook Received', ['event' => $event, 'tx_ref' => $payload['tx_ref'] ?? 'N/A']);

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

        // 5. Credit Wallet
        DB::transaction(function () use ($user, $amount, $transactionId, $payload) {
            $wallet = $user->wallet ?: $user->wallet()->create(['balance' => 0]);
            $wallet->increment('balance', $amount);

            WalletTransaction::create([
                'user_id'   => $user->id,
                'reference' => $transactionId,
                'amount'    => $amount,
                'type'      => 'credit',
                'status'    => 'success',
                'source'    => 'wallet_funding',
                'meta'      => $payload,
            ]);
        });

        return response()->json(['status' => 'success']);
    }
}
