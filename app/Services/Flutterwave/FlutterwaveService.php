<?php

namespace App\Services\Flutterwave;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected string $baseUrl = 'https://api.flutterwave.com/v3';
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key', '');
    }

    /**
     * Create a virtual account for a user
     */
    public function createVirtualAccount(array $userData)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post($this->baseUrl . '/virtual-account-numbers', [
                    'email'       => $userData['email'],
                    'is_permanent' => true,
                    'bvn'         => $userData['bvn'] ?? null,
                    'tx_ref'      => 'VA-' . $userData['user_id'] . '-' . time(),
                    'phonenumber' => $userData['phone'] ?? null,
                    'firstname'   => $userData['firstname'] ?? 'User',
                    'lastname'    => $userData['lastname'] ?? $userData['user_id'],
                ]);

            if (!$response->successful()) {
                $errorBody = $response->body();
                Log::error('Flutterwave VA Creation Failed: ' . $errorBody);
                return ['success' => false, 'message' => 'Flutterwave error: ' . $errorBody];
            }

            $data = $response->json();

            if (($data['status'] ?? '') !== 'success') {
                return ['success' => false, 'message' => $data['message'] ?? 'Unknown error'];
            }

            return [
                'success'           => true,
                'account_number'    => $data['data']['account_number'],
                'bank_name'         => $data['data']['bank_name'],
                'account_reference' => $data['data']['flw_ref'],
                'order_ref'         => $data['data']['order_ref'],
            ];

        } catch (\Throwable $e) {
            Log::error('Flutterwave VA Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'System exception'];
        }
    }

    /**
     * Verify a transaction by ID
     */
    public function verifyTransaction(string $transactionId)
    {
        $response = Http::withToken($this->secretKey)
            ->get($this->baseUrl . "/transactions/{$transactionId}/verify");

        return $response->json();
    }
}
