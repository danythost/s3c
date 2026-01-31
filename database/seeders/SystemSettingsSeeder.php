<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Providers
        \App\Models\Provider::updateOrCreate(
            ['slug' => 'epins'],
            [
                'name' => 'Epins VTU',
                'config' => [
                    'api_key' => config('vtu.epins.api_key'),
                    'username' => config('vtu.epins.username'),
                    'bearer_token' => config('vtu.epins.bearer_token'),
                    'base_url' => config('vtu.epins.base_url'),
                ],
                'is_active' => true,
            ]
        );

        // API Key Settings
        \App\Models\Setting::updateOrCreate(['key' => 'flw_public_key'], ['value' => config('services.flutterwave.public_key'), 'group' => 'api_keys']);
        \App\Models\Setting::updateOrCreate(['key' => 'flw_secret_key'], ['value' => config('services.flutterwave.secret_key'), 'group' => 'api_keys']);

        // Pricing Settings
        \App\Models\Setting::updateOrCreate(['key' => 'global_markup'], ['value' => '0', 'group' => 'pricing']);
        \App\Models\Setting::updateOrCreate(['key' => 'reseller_discount'], ['value' => '0', 'group' => 'pricing']);
    }
}
