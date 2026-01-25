<?php

namespace App\Contracts\VTU;

use App\Domains\VTU\VTUResponse;

interface VTUProviderInterface
{
    /**
     * Purchase data plan
     * 
     * @param array $payload ['phone', 'network', 'plan_id', 'amount', 'reference']
     * @return VTUResponse
     */
    public function purchaseData(array $payload): VTUResponse;

    /**
     * Purchase airtime
     * 
     * @param array $payload ['phone', 'network', 'amount', 'reference']
     * @return VTUResponse
     */
    public function purchaseAirtime(array $payload): VTUResponse;

    /**
     * Check provider wallet balance
     * 
     * @return VTUResponse
     */
    public function getBalance(): VTUResponse;
}
