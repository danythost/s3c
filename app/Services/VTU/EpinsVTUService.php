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
            $response = Http::timeout(30)
                ->withToken($this->bearerToken)
                ->post($this->baseUrl . 'airtime', [
                    'network'    => strtoupper($payload['network']),
                    'phone'      => $payload['phone'],
                    'amount'     => $payload['amount'],
                    'ref'        => $reference,
                ]);

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
     * Fetch all data plans from EPINS
     */
    public function fetchDataPlans(): VTUResponse
    {
        try {
            $response = Http::timeout(30)
                ->withToken($this->bearerToken)
                ->get($this->baseUrl . 'data');

            if (!$response->successful()) {
                return VTUResponse::failure('EPINS API failed to fetch data plans', ['raw' => $response->json() ?? []]);
            }

            return VTUResponse::success('Data plans retrieved', $response->json());

        } catch (\Throwable $e) {
            return VTUResponse::failure('Exception during data plans fetch: ' . $e->getMessage());
        }
    }

    /**
     * Sync data plans to local database
     */
    public function syncDataPlans(): VTUResponse
    {
        $response = $this->fetchDataPlans();
        if (!$response->success) {
            return $response;
        }

        $data = $response->data;
        
        // EPINS usually returns plans in 'description' or directly in the response
        $plans = $data['description'] ?? $data['data'] ?? null;

        if (!is_array($plans)) {
            return VTUResponse::failure('Invalid data plan format received from EPINS', $data);
        }

        $count = 0;
        foreach ($plans as $plan) {
            if (!isset($plan['plancode'])) continue;

            \App\Models\DataPlan::updateOrCreate(
                ['provider' => 'epins', 'code' => $plan['plancode']],
                [
                    'network'        => $this->reverseMapNetwork($plan['network'] ?? ''),
                    'name'           => $plan['plan_name'] ?? ($plan['name'] ?? 'Unknown Plan'),
                    'provider_price' => $plan['amount'] ?? 0,
                    // We only set selling_price if it's a new record to avoid overriding admin markups
                    'selling_price'  => \App\Models\DataPlan::where('provider', 'epins')
                        ->where('code', $plan['plancode'])
                        ->exists() 
                        ? \DB::raw('selling_price') 
                        : ($plan['amount'] ?? 0),
                    'is_active'      => true,
                ]
            );
            $count++;
        }

        return VTUResponse::success("Successfully synced $count data plans");
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
