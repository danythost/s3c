<?php

namespace App\Actions\VTU;

use App\Models\DataPlan;
use App\Services\VTU\EpinsService;
use Illuminate\Support\Facades\DB;

class SyncEpinsDataPlans
{
    public function execute()
    {
        $plans = app(EpinsService::class)->fetchDataPlans();

        if (empty($plans)) {
            return;
        }

        foreach ($plans as $plan) {
            // Map the API response fields to local database columns
            $code = $plan['id'] ?? ($plan['plancode'] ?? null);
            
            if (!$code) continue;

            DataPlan::updateOrCreate(
                [
                    'provider' => 'epins',
                    'code'     => $code,
                ],
                [
                    'network'          => strtoupper($plan['network'] ?? 'UNKNOWN'),
                    'name'             => $plan['name'] ?? ($plan['plan_name'] ?? 'Unknown Plan'),
                    'provider_price'   => $plan['price'] ?? ($plan['amount'] ?? 0),
                    // Only update selling_price if it's a new plan to preserve manual markups
                    'selling_price'    => DataPlan::where('provider', 'epins')
                        ->where('code', $code)
                        ->exists() 
                        ? DB::raw('selling_price') 
                        : ($plan['price'] ?? ($plan['amount'] ?? 0)),
                    'validity'         => $plan['validity'] ?? null,
                    'is_active'        => true,
                ]
            );
        }
    }
}
