<?php

namespace App\Services\VTU;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EpinsService
{
    public function fetchDataPlans()
    {
        $apiKey = config('services.epins.api_key');

        if (! $apiKey) {
            throw new \Exception('EPINS API key not loaded');
        }

        $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])
            ->post(config('services.epins.base_url') . '/data/', [
                'ref' => Str::uuid()->toString(),
            ]);

        if (! $response->successful()) {
            throw new \Exception(
                'EPINS error (' . $response->status() . '): ' . $response->body()
            );
        }

        return $response->json()['data'] ?? [];
    }
}
