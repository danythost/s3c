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
        $this->bearerToken = config('vtu.epins.bearer_token', '');
    }

    /**
     * Purchase data plan
     */
    public function purchaseData(array $payload): VTUResponse
    {
        $reference = $payload['reference'] ?? uniqid('data_');
        $networkCode = $this->mapNetwork($payload['network'] ?? '');

        try {
            $startTime = microtime(true);
            $response = Http::timeout(30)
                ->withToken($this->apiKey)
                ->post($this->baseUrl . 'data', $requestBody = [
                    'network'    => $networkCode,
                    'phone'      => $payload['phone'],
                    'plan_code'  => $payload['plan_code'] ?? $payload['plan_id'],
                    'reference'  => $reference,
                ]);
            $duration = (int) ((microtime(true) - $startTime) * 1000);

            ApiLogger::log('epins', 'POST', $this->baseUrl . 'data', $requestBody, $response->json(), $response->status(), $duration);

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS API request failed', ['raw' => $response->json() ?? []]);
            }

            $data = $response->json();

            if (($data['status'] ?? false) !== true && ($data['status'] ?? '') !== 'success') {
                return VTUResponse::failure($data['message'] ?? 'VTU purchase failed', $data);
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
        $reference = $payload['reference'] ?? uniqid('airtime_');
        $networkCode = $this->mapNetwork($payload['network'] ?? '');

        try {
            $startTime = microtime(true);
            $response = Http::timeout(30)
                ->withToken($this->apiKey)
                ->post($this->baseUrl . 'airtime', $requestBody = [
                    'network'    => strtoupper($payload['network']),
                    'phone'      => $payload['phone'],
                    'amount'     => $payload['amount'],
                    'ref'        => $reference,
                ]);
            $duration = (int) ((microtime(true) - $startTime) * 1000);

            ApiLogger::log('epins', 'POST', $this->baseUrl . 'airtime', $requestBody, $response->json(), $response->status(), $duration);

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS API request failed', ['raw' => $response->json() ?? []]);
            }

            $data = $response->json();

            if (($data['status'] ?? false) !== true) {
                return VTUResponse::failure($data['description'] ?? ($data['message'] ?? 'Airtime purchase failed'), $data);
            }

            return VTUResponse::success($data['description'] ?? 'Airtime purchase successful', $data, $reference);

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
                ->withToken($this->apiKey)
                ->get($this->baseUrl . 'account');
            $duration = (int) ((microtime(true) - $startTime) * 1000);

            ApiLogger::log('epins', 'GET', $this->baseUrl . 'account', [], $response->json(), $response->status(), $duration);

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS Balance check failed: HTTP ' . $response->status());
            }

            $data = $response->json();

            // Based on typical EPINS/Laravel response structures
            if (isset($data['balance']) || isset($data['wallet_balance'])) {
                return VTUResponse::success('Balance retrieved', $data);
            }

            return VTUResponse::failure('Could not parse balance from response', $data);

        } catch (\Throwable $e) {
            return VTUResponse::failure('Exception during balance check: ' . $e->getMessage());
        }
    }



    /**
     * Map network names to EPINS codes
     */
    protected function mapNetwork(string $network): string
    {
        return match (strtoupper($network)) {
            'MTN'     => '01',
            'AIRTEL'  => '04',
            '9MOBILE' => '03',
            'GLO'     => '02',
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
