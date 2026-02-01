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
                Log::error('Flutterwave VA Creation Failed: ' . $errorBody . ' | Payload: ' . json_encode($userData));
                return ['success' => false, 'message' => 'Flutterwave error: ' . ($response->json()['message'] ?? $errorBody)];
            }

            $data = $response->json();

            if (($data['status'] ?? '') !== 'success') {
                return ['success' => false, 'message' => $data['message'] ?? 'Unknown error'];
            }

            // Handle variations in key naming (e.g. from user provided JSON vs current docs)
            $accountData = $data['data'] ?? [];
            
            return [
                'success'           => true,
                'account_number'    => $accountData['account_number'] ?? null,
                'bank_name'         => $accountData['bank_name'] ?? ($accountData['account_bank_name'] ?? 'Unknown Bank'),
                'account_reference' => $accountData['flw_ref'] ?? ($accountData['reference'] ?? null),
                'order_ref'         => $accountData['order_ref'] ?? null,
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

    /**
     * Get recent transactions for a virtual account
     */
    public function getTransactions(string $email, ?string $from = null, ?string $to = null)
    {
        $params = [
            'customer_email' => $email,
        ];
        
        if ($from) $params['from'] = $from;
        if ($to) $params['to'] = $to;

        $response = Http::withToken($this->secretKey)
            ->get($this->baseUrl . "/transactions", $params);

        return $response->json();
    }
}
