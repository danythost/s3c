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
            $vaUsername = $userData['va_username'] ?? ($userData['lastname'] ?? $userData['user_id']);
            $fullName = config('app.name') . ' ' . $vaUsername;

            $startTime = microtime(true);
            $payload = [
                'email'        => $userData['email'],
                'is_permanent' => true,
                'bvn'          => $userData['bvn'] ?? null,
                'tx_ref'       => 'VA-' . $userData['user_id'] . '-' . time(),
                'phonenumber'  => $userData['phone'] ?? null,
                'firstname'    => config('app.name'),
                'lastname'     => $vaUsername,
                'narration'    => $fullName,
                'account_name' => $fullName,
            ];

            $response = Http::withToken($this->secretKey)
                ->post($this->baseUrl . '/virtual-account-numbers', $payload);

            $duration = (int) ((microtime(true) - $startTime) * 1000);

            try {
                \App\Services\Logger\ApiLogger::log(
                    'flutterwave-va',
                    'POST',
                    $this->baseUrl . '/virtual-account-numbers',
                    $payload,
                    $response->json(),
                    $response->status(),
                    $duration
                );
            } catch (\Throwable $e) {
                Log::error('Failed to log FLW VA request: ' . $e->getMessage());
            }

            Log::info('Flutterwave VA Request Payload: ' . json_encode([
                'email'        => $userData['email'],
                'is_permanent' => true,
                'bvn'          => $userData['bvn'] ?? null,
                'firstname'    => config('app.name'),
                'lastname'     => $vaUsername,
                'narration'    => $fullName,
            ]));

            if (!$response->successful()) {
                $errorBody = $response->body();
                Log::error('Flutterwave VA Creation Failed: ' . $errorBody . ' | Payload: ' . json_encode($userData));
                return ['success' => false, 'message' => 'Flutterwave error: ' . ($response->json()['message'] ?? $errorBody)];
            }

            $data = $response->json();
            Log::info('Flutterwave VA Success Response: ' . json_encode($data));

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
        $startTime = microtime(true);
        $url = $this->baseUrl . "/transactions/{$transactionId}/verify";
        $response = Http::withToken($this->secretKey)->get($url);
        $duration = (int) ((microtime(true) - $startTime) * 1000);

        try {
            \App\Services\Logger\ApiLogger::log(
                'flutterwave-verify',
                'GET',
                $url,
                [],
                $response->json(),
                $response->status(),
                $duration
            );
        } catch (\Throwable $e) {
            Log::error('Failed to log FLW Verify request: ' . $e->getMessage());
        }

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

        $startTime = microtime(true);
        $url = $this->baseUrl . "/transactions";
        $response = Http::withToken($this->secretKey)->get($url, $params);
        $duration = (int) ((microtime(true) - $startTime) * 1000);

        try {
            \App\Services\Logger\ApiLogger::log(
                'flutterwave-transactions',
                'GET',
                $url,
                $params,
                $response->json(),
                $response->status(),
                $duration
            );
        } catch (\Throwable $e) {
            Log::error('Failed to log FLW Transactions request: ' . $e->getMessage());
        }

        return $response->json();
    }
}
