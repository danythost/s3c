<?php

namespace App\Domains\VTU;

class DataPlan
{
    public function __construct(
        public string $id,
        public string $name,
        public float $amount,
        public string $validity,
        public string $network
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'validity' => $this->validity,
            'network' => $this->network,
        ];
    }
}
