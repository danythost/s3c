<?php

namespace App\Services\VTU;

use App\Contracts\VTU\VTUProviderInterface;
use App\Domains\VTU\VTUResponse;
use Illuminate\Support\Facades\Http;
use App\Services\Logger\ApiLogger;

class EpinsVTUService implements VTUProviderInterface
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $username;
    protected string $bearerToken;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('vtu.epins.base_url', ''), '/') . '/';
        $this->apiKey = config('vtu.epins.api_key', '');
        $this->username = config('vtu.epins.username', '');
        $this->bearerToken = (string) config('vtu.epins.bearer_token', '');
    }

    /**
     * Purchase data plan
     */
    public function purchaseData(array $payload): VTUResponse
    {
        $reference = $payload['reference'] ?? 'D' . dechex(time()) . bin2hex(random_bytes(4));
        $networkId = $this->mapNetwork($payload['network'] ?? '');
        
        try {
            $startTime = microtime(true);
            
            $requestBody = [
                'networkId'    => (string) $networkId,
                'MobileNumber' => (string) $payload['phone'],
                'DataPlan'     => (string) ($payload['plan_code'] ?? $payload['plan_id']),
                'ref'          => $reference,
            ];

            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->timeout(40)
                ->connectTimeout(15)
                ->withOptions([
                    'curl' => [
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    ],
                ])
                ->post(rtrim($this->baseUrl, '/') . '/data/', $requestBody);

            $duration = (int) ((microtime(true) - $startTime) * 1000);
            $responseData = $response->json() ?? [];

            // Log BEFORE returning
            try {
                ApiLogger::log('epins', 'POST', rtrim($this->baseUrl, '/') . '/data/', $requestBody, $responseData, $response->status(), $duration);
            } catch (\Throwable $logError) {
                // Ignore logging errors to not break the transaction
            }

            if (!$response->successful()) {
                $errorMessage = $responseData['description'] ?? ($responseData['message'] ?? 'EPINS API returned error ' . $response->status());
                return VTUResponse::failure($errorMessage, [
                    'raw' => $responseData ?? [],
                    'reference' => $reference
                ]);
            }

            // Success is either boolean true or "success" string or code 101
            if (($responseData['status'] ?? false) !== true && strtolower($responseData['status'] ?? '') !== 'success' && ($responseData['code'] ?? '') != '101') {
                $errorMessage = $responseData['description'] ?? ($responseData['message'] ?? 'Data purchase failed');
                return VTUResponse::failure($errorMessage, $responseData);
            }

            return VTUResponse::success('Data purchase successful', $responseData, $reference);

        } catch (\Throwable $e) {
            return VTUResponse::failure('VTU service exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * Purchase airtime
     */
    public function purchaseAirtime(array $payload): VTUResponse
    {
        $reference = $payload['reference'] ?? 'A' . dechex(time()) . bin2hex(random_bytes(4));
        $networkId = $this->mapNetwork($payload['network'] ?? '');
        
        try {
            $startTime = microtime(true);
            
            $requestBody = [
                'network' => (string) $networkId,
                'phone'   => (string) $payload['phone'],
                'amount'  => (int) $payload['amount'],
                'ref'     => $reference,
            ];

            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->timeout(40)
                ->connectTimeout(15)
                ->withOptions([
                    'curl' => [
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    ],
                ])
                ->post(rtrim($this->baseUrl, '/') . '/airtime/', $requestBody);

            $duration = (int) ((microtime(true) - $startTime) * 1000);
            $responseData = $response->json() ?? [];

            try {
                ApiLogger::log('epins', 'POST', rtrim($this->baseUrl, '/') . '/airtime/', $requestBody, $responseData, $response->status(), $duration);
            } catch (\Throwable $logError) {
            }

            if (!$response->successful()) {
                $errorMessage = $responseData['description'] ?? ($responseData['message'] ?? 'EPINS API request failed');
                return VTUResponse::failure($errorMessage, ['raw' => $responseData ?? []]);
            }

            // Success is either boolean true or "success" string or code 101
            if (($responseData['status'] ?? false) !== true && strtolower($responseData['status'] ?? '') !== 'success' && ($responseData['code'] ?? '') != '101') {
                $errorMessage = $responseData['description'] ?? ($responseData['message'] ?? 'Airtime purchase failed');
                return VTUResponse::failure($errorMessage, $responseData);
            }

            $message = $responseData['description'] ?? ($responseData['message'] ?? 'Airtime purchase successful');
            if (is_array($message)) {
                $message = 'Airtime purchase successful';
            }
            
            return VTUResponse::success($message, $responseData, $reference);

        } catch (\Throwable $e) {
            return VTUResponse::failure('VTU service unreachable: ' . $e->getMessage());
        }
    }

    /**
     * Check provider wallet balance
     */
    public function getBalance(): VTUResponse
    {
        try {
            $startTime = microtime(true);
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->get($this->baseUrl . 'account/');
            $duration = (int) ((microtime(true) - $startTime) * 1000);

            ApiLogger::log('epins', 'GET', $this->baseUrl . 'account/', [], $response->json(), $response->status(), $duration);

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS Balance check failed: HTTP ' . $response->status());
            }

            $data = $response->json();

            // Based on typical EPINS/Laravel response structures
            if (isset($data['balance']) || isset($data['wallet_balance']) || ($data['code'] ?? null) == 101) {
                return VTUResponse::success('Balance retrieved', $data);
            }

            return VTUResponse::failure('Could not parse balance from response', $data);

        } catch (\Throwable $e) {
            return VTUResponse::failure('Exception during balance check: ' . $e->getMessage());
        }
    }



    protected function mapNetworkName(string $network): string
    {
        return match (strtoupper($network)) {
            'MTN'     => 'mtn',
            'GLO'     => 'glo',
            '9MOBILE' => 'etisalat',
            'AIRTEL'  => 'airtel',
            default    => strtolower($network),
        };
    }

    /**
     * Map network names to EPINS codes
     */
    protected function mapNetwork(string $network): string
    {
        return match (strtoupper($network)) {
            'MTN'     => '01',
            'GLO'     => '02',
            '9MOBILE' => '03',
            'AIRTEL'  => '04',
            default    => $network,
        };
    }

    /**
     * Map EPINS codes to network names
     */
    protected function reverseMapNetwork(string $code): string
    {
        return match ($code) {
            '01' => 'MTN',
            '02' => 'GLO',
            '03' => '9MOBILE',
            '04' => 'AIRTEL',
            default => $code,
        };
    }
}
