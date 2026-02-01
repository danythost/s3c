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
        $reference = $payload['reference'] ?? uniqid('data_');
        $networkId = $this->mapNetwork($payload['network'] ?? '');
        
        try {
            $startTime = microtime(true);
            
            $requestBody = [
                'networkId'    => $networkId,
                'MobileNumber' => $payload['phone'],
                'DataPlan'     => (int) ($payload['plan_code'] ?? $payload['plan_id']),
                'ref'          => $reference,
            ];

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->withBody(json_encode($requestBody), 'application/json')
                ->post($this->baseUrl . 'data/');

            $duration = (int) ((microtime(true) - $startTime) * 1000);

            ApiLogger::log('epins', 'POST', $this->baseUrl . 'data/', $requestBody, $response->json(), $response->status(), $duration);

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS API request failed', ['raw' => $response->json() ?? []]);
            }

            $data = $response->json();

            if (($data['status'] ?? false) !== true && strtolower($data['status'] ?? '') !== 'success' && ($data['code'] ?? '') != '101') {
                return VTUResponse::failure($data['message'] ?? ($data['description'] ?? 'VTU purchase failed'), $data);
            }

            return VTUResponse::success('Data purchase successful', $data, $reference);

        } catch (\Throwable $e) {
            return VTUResponse::failure('VTU service unreachable: ' . $e->getMessage());
        }
    }

    /**
     * Purchase airtime
     */
    public function purchaseAirtime(array $payload): VTUResponse
    {
        $reference = $payload['reference'] ?? 'AIR' . time() . rand(100, 999);
        $networkId = $this->mapNetwork($payload['network'] ?? '');
        
        try {
            $startTime = microtime(true);
            
            $requestBody = [
                'network' => $this->mapNetworkName($payload['network'] ?? ''),
                'phone'   => $payload['phone'],
                'amount'  => (int) $payload['amount'],
                'ref'     => $reference,
            ];

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->withBody(json_encode($requestBody), 'application/json')
                ->post($this->baseUrl . 'airtime/');

            $duration = (int) ((microtime(true) - $startTime) * 1000);

            ApiLogger::log('epins', 'POST', $this->baseUrl . 'airtime/', $requestBody, $response->json(), $response->status(), $duration);

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS API request failed', ['raw' => $response->json() ?? []]);
            }

            $data = $response->json();

            // Success is either boolean true or "success" string or code 101
            if (($data['status'] ?? false) !== true && strtolower($data['status'] ?? '') !== 'success' && ($data['code'] ?? '') != '101') {
                return VTUResponse::failure($data['description'] ?? ($data['message'] ?? 'Airtime purchase failed'), $data);
            }


            $message = $data['description'] ?? $data['message'] ?? 'Airtime purchase successful';
            if (is_array($message)) {
                $message = 'Airtime purchase successful';
            }
            
            return VTUResponse::success($message, $data, $reference);

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
