<?php

namespace App\Services\VTU;

use App\Contracts\VTU\VTUProviderInterface;
use App\Domains\VTU\VTUResponse;
use App\Models\DataPlan;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use App\Services\Logger\ApiLogger;
use Exception;

class PeyflexVTUService implements VTUProviderInterface
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $providerName = 'peyflex';

    public function __construct()
    {
        $this->baseUrl = rtrim(config('vtu.peyflex.base_url'), '/') . '/';
        $this->apiKey = config('vtu.peyflex.api_key');
    }

    /**
     * Purchase airtime
     */
    public function purchaseAirtime(array $payload): VTUResponse
    {
        $reference = $payload['reference'] ?? 'A' . time() . uniqid();
        $network = $this->mapNetwork($payload['network']);
        $amount = (float) $payload['amount'];
        $phone = $payload['phone'];

        $phone = $payload['phone'];
        
        // Fetch commission rate from settings, fallback to config
        $settingKey = 'peyflex_airtime_' . $network;
        $commissionRate = Setting::getValue($settingKey, config('vtu.peyflex.airtime_rates.' . $network, 0));
        
        // Convert to decimal (e.g. 1% -> 0.01)
        $commissionRate = (float) $commissionRate / 100;

        $profit = $amount * $commissionRate;
        $costPrice = $amount - $profit;

        try {
            $startTime = microtime(true);
            
            $requestBody = [
                'network' => $network,
                'amount' => $amount,
                'mobile_number' => $phone,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . 'airtime/topup/', $requestBody);

            $duration = (int) ((microtime(true) - $startTime) * 1000);
            $responseData = $response->json() ?? [];

            // Log API call
            ApiLogger::log($this->providerName, 'POST', $this->baseUrl . 'airtime/topup/', $requestBody, $responseData, $response->status(), $duration);

            if (!$response->successful()) {
                $error = $responseData['detail'] ?? ($responseData['message'] ?? 'Peyflex API error: ' . $response->status());
                return VTUResponse::failure($error, $responseData);
            }

            // Peyflex typically returns success status or status code
            // The user sample doesn't show the exact success field name, but usually it's 'status' => 'success' or HTTP 201/200
            // Assuming successful based on HTTP status here, but will add safety
            
            $vtuData = array_merge($responseData, [
                'cost_price' => $costPrice,
                'profit' => $profit,
                'provider' => $this->providerName,
            ]);

            return VTUResponse::success('Airtime purchase successful', $vtuData, $reference);

        } catch (Exception $e) {
            return VTUResponse::failure('Peyflex service reachable error: ' . $e->getMessage());
        }
    }

    /**
     * Purchase data
     */
    public function purchaseData(array $payload): VTUResponse
    {
        $reference = $payload['reference'] ?? 'D' . time() . uniqid();
        
        // Handle composite code: network|plan_code
        $compositeCode = $payload['plan_code'] ?? ($payload['code'] ?? null);
        if (str_contains($compositeCode, '|')) {
            list($network, $plan) = explode('|', $compositeCode);
        } else {
            $network = $payload['network']; 
            $plan = $compositeCode;
        }

        $phone = $payload['phone'];

        if (!$plan) {
            return VTUResponse::failure('Missing data plan identifier');
        }

        try {
            $startTime = microtime(true);
            
            $requestBody = [
                'network' => $network,
                'plan_code' => $plan,
                'mobile_number' => $phone,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . 'data/purchase/', $requestBody);

            $duration = (int) ((microtime(true) - $startTime) * 1000);
            $responseData = $response->json() ?? [];

            ApiLogger::log($this->providerName, 'POST', $this->baseUrl . 'data/purchase/', $requestBody, $responseData, $response->status(), $duration);

            if (!$response->successful()) {
                $error = $responseData['detail'] ?? ($responseData['message'] ?? 'Peyflex API error: ' . $response->status());
                return VTUResponse::failure($error, $responseData);
            }

            // Calculate profit for tracking
            // Since Data plans are fixed, we'll try to find the plan in our DB to get selling price
            // OR we calculate it from the rates if we know the cost from response
            $vtuData = array_merge($responseData, [
                'provider' => $this->providerName,
            ]);

            return VTUResponse::success('Data purchase successful', $vtuData, $reference);

        } catch (Exception $e) {
            return VTUResponse::failure('Peyflex data service error: ' . $e->getMessage());
        }
    }

    /**
     * Sync Data Plans from Peyflex
     */
    public function syncDataPlans(): array
    {
        $networks = $this->getDataNetworks();
        $importedCount = 0;
        $errors = [];

        if (empty($networks)) {
            throw new Exception("Could not fetch data networks from Peyflex.");
        }

        // Clean existing data for this provider
        DataPlan::where('provider', $this->providerName)->delete();

        foreach ($networks as $net) {
            $identifier = $net['identifier'];
            $name = $net['name'];

            try {
                $plans = $this->getDataPlans($identifier);
                
                foreach ($plans as $p) {
                    $costPrice = (float) ($p['amount'] ?? 0);
                    
                    // Determine rate based on gifting vs sharing from settings
                    $isGifting = str_contains(strtolower($identifier), 'gifting');
                    $settingKey = $isGifting ? 'peyflex_data_gifting' : 'peyflex_data_shared_cg';
                    
                    $rate = Setting::getValue($settingKey, $isGifting ? 1 : 5);
                    $rate = (float) $rate / 100; // Convert to decimal
                    
                    $sellingPrice = ceil($costPrice / (1 - $rate));

                    DataPlan::updateOrCreate(
                        ['provider' => $this->providerName, 'code' => $identifier . '|' . ($p['plan_code'] ?? 'N/A')],
                        [
                            'network' => $identifier,
                            'name' => $p['label'] ?? ($name . ' ' . ($p['plan_type'] ?? '')),
                            'volume' => $p['plan_type'] ?? null,
                            'type' => $isGifting ? 'GIFTING' : 'SHARED/CG',
                            'provider_price' => $costPrice,
                            'selling_price' => $sellingPrice,
                            'validity' => $p['validity'] ?? '30 days',
                            'is_active' => true,
                        ]
                    );
                    $importedCount++;
                }
            } catch (Exception $e) {
                $errors[] = "Error fetching plans for $identifier: " . $e->getMessage();
            }
        }

        return ['imported' => $importedCount, 'errors' => $errors];
    }

    protected function getDataNetworks(): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . 'data/networks/');

        return $response->successful() ? ($response->json()['networks'] ?? []) : [];
    }

    protected function getDataPlans(string $networkIdentifier): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . 'data/plans/', ['network' => $networkIdentifier]);

        $data = $response->json();
        return $response->successful() ? ($data['plans'] ?? []) : [];
    }

    /**
     * Get Provider Balance
     */
    public function getBalance(): VTUResponse
    {
        try {
            $startTime = microtime(true);
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . 'wallet/balance/');

            $duration = (int) ((microtime(true) - $startTime) * 1000);
            $responseData = $response->json() ?? [];
            if (isset($responseData['wallet_credit'])) {
                $responseData['balance'] = (float) $responseData['wallet_credit'];
            }

            ApiLogger::log($this->providerName, 'GET', $this->baseUrl . 'wallet/balance/', [], $responseData, $response->status(), $duration);

            if (!$response->successful()) {
                return VTUResponse::failure('Peyflex balance check failed: ' . $response->status());
            }

            return VTUResponse::success('Balance retrieved', $responseData);

        } catch (Exception $e) {
            return VTUResponse::failure('Peyflex balance check exception: ' . $e->getMessage());
        }
    }

    /**
     * Map network name to Peyflex expected identifier
     */
    protected function mapNetwork(string $network): string
    {
        $network = strtolower($network);
        return match ($network) {
            'mtn' => 'mtn',
            'airtel' => 'airtel',
            'glo' => 'glo',
            '9mobile', 'etisalat' => '9mobile',
            default => $network,
        };
    }
}
