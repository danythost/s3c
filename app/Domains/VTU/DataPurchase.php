<?php

namespace App\Domains\VTU;

class DataPurchase
{
    public function __construct(
        public string $phone,
        public string $network,
        public string $planId,
        public float $amount,
        public string $reference
    ) {}

    public function toArray(): array
    {
        return [
            'phone' => $this->phone,
            'network' => $this->network,
            'plan_id' => $this->planId,
            'amount' => $this->amount,
            'reference' => $this->reference,
        ];
    }
}
