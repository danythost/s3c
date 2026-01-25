<?php

namespace App\Services\VTU;

use App\Contracts\VTU\VTUProviderInterface;
use App\Domains\VTU\VTUResponse;

class MockVTUService implements VTUProviderInterface
{
    public function purchaseData(array $payload): VTUResponse
    {
        return VTUResponse::success('Mock data purchase successful', $payload, $payload['reference'] ?? 'mock-ref');
    }

    public function purchaseAirtime(array $payload): VTUResponse
    {
        return VTUResponse::success('Mock airtime purchase successful', $payload, $payload['reference'] ?? 'mock-ref');
    }

    public function getBalance(): VTUResponse
    {
        return VTUResponse::success('Mock balance retrieved', ['balance' => 50000.00]);
    }
}
