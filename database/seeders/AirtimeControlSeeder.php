<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirtimeControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $networks = ['MTN', 'AIRTEL', 'GLO', '9MOBILE'];

        foreach ($networks as $network) {
            \App\Models\AirtimeControl::firstOrCreate(
                ['network' => $network],
                [
                    'min_amount' => 50,
                    'max_amount' => 50000,
                    'commission_percentage' => 2.00,
                    'is_active' => true,
                ]
            );
        }
    }
}
