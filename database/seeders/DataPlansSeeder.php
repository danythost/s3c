<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataPlansSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $plans = [
            [
                'provider' => 'epins',
                'network' => 'MTN',
                'code' => '36',
                'name' => '1GB (CG_LITE)',
                'provider_price' => 880,
                'selling_price' => 880,
                'validity' => 30,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'provider' => 'epins',
                'network' => 'MTN',
                'code' => '37',
                'name' => '2GB (CG_LITE)',
                'provider_price' => 1700,
                'selling_price' => 1700,
                'validity' => 30,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'provider' => 'epins',
                'network' => 'GLO',
                'code' => '95',
                'name' => '1GB (CG)',
                'provider_price' => 430,
                'selling_price' => 430,
                'validity' => 30,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'provider' => 'epins',
                'network' => '9Mobile',
                'code' => '122',
                'name' => '1GB (CG)',
                'provider_price' => 285,
                'selling_price' => 285,
                'validity' => 30,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'provider' => 'epins',
                'network' => 'SMILE',
                'code' => '847',
                'name' => 'Buy Airtime',
                'provider_price' => 0,
                'selling_price' => 0,
                'validity' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('data_plans')->updateOrInsert(
                ['provider' => $plan['provider'], 'code' => $plan['code']],
                $plan
            );
        }
    }
}
