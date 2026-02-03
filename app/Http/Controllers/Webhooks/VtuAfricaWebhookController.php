<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\A2CRequest;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VtuAfricaWebhookController extends Controller
{
    /**
     * Handle the incoming VTUAfrica webhook.
     */
    public function __invoke(Request $request)
    {
        Log::info('VTUAfrica Webhook Received:', $request->all());

        $ref = $request->input('ref');
        $status = $request->input('status'); // e.g., "Completed"
        $service = $request->input('service');
        
        if ($service !== 'Airtime2Cash') {
            return response()->json(['message' => 'Service not handled'], 200);
        }

        $a2cRequest = A2CRequest::where('reference', $ref)->first();

        if (!$a2cRequest) {
            Log::warning("VTUAfrica Webhook: Request not found for ref: {$ref}");
            return response()->json(['message' => 'Request not found'], 404);
        }

        if ($a2cRequest->status !== 'pending') {
            return response()->json([
                'code' => 101,
                'status' => 'Completed',
                'message' => 'Already processed'
            ]);
        }

        if ($status === 'Completed') {
            DB::transaction(function () use ($a2cRequest, $request) {
                // Update request status
                $a2cRequest->update(['status' => 'completed']);

                // Credit user wallet
                $user = $a2cRequest->user;
                $creditAmount = $request->input('credit', $a2cRequest->payable); // Use credit from webhook if available
                
                $user->wallet->increment('balance', $creditAmount);

                // Create transaction record
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'reference' => $a2cRequest->reference,
                    'type' => 'credit',
                    'amount' => $creditAmount,
                    'status' => 'success',
                    'source' => 'vtuafrica_a2c',
                    'meta' => $request->all(),
                ]);
            });

            return response()->json([
                'code' => 101,
                'status' => 'Completed',
                'message' => 'Wallet credited'
            ]);
        }

        if ($status === 'Failed' || $status === 'Canceled') {
            $a2cRequest->update(['status' => 'failed']);
            return response()->json([
                'code' => 102,
                'status' => 'Failed',
                'message' => 'Request marked as failed'
            ]);
        }

        return response()->json(['message' => 'Status handled'], 200);
    }
}
