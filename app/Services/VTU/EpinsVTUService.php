<?php

namespace App\Services\VTU;

use App\Contracts\VTU\VTUProviderInterface;
use App\Domains\VTU\VTUResponse;
use Illuminate\Support\Facades\Http;

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
            $response = Http::timeout(30)
                ->withToken($this->bearerToken)
                ->post($this->baseUrl . 'data', [
                    'network'    => $networkCode,
                    'phone'      => $payload['phone'],
                    'plan_code'  => $payload['plan_code'] ?? $payload['plan_id'],
                    'reference'  => $reference,
                ]);

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS API request failed', ['raw' => $response->json() ?? []]);
            }

            $data = $response->json();

            if (($data['status'] ?? '') !== 'success') {
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
            $response = Http::timeout(30)
                ->withToken($this->bearerToken)
                ->post($this->baseUrl . 'airtime', [
                    'network'    => $networkCode,
                    'phone'      => $payload['phone'],
                    'amount'     => $payload['amount'],
                    'reference'  => $reference,
                ]);

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS API request failed', ['raw' => $response->json() ?? []]);
            }

            $data = $response->json();

            if (($data['status'] ?? '') !== 'success') {
                return VTUResponse::failure($data['message'] ?? 'Airtime purchase failed', $data);
            }

            return VTUResponse::success('Airtime purchase successful', $data, $reference);

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
            $response = Http::timeout(30)
                ->withToken($this->bearerToken)
                ->get($this->baseUrl . 'account');

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
}
