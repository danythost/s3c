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
            ['slug' => 'peyflex'],
            [
                'name' => 'Peyflex VTU',
                'config' => [
                    'api_key' => config('vtu.peyflex.api_key'),
                    'base_url' => config('vtu.peyflex.base_url'),
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

        // Peyflex Profit Margins
        \App\Models\Setting::updateOrCreate(['key' => 'peyflex_airtime_mtn'], ['value' => '1', 'group' => 'pricing']);
        \App\Models\Setting::updateOrCreate(['key' => 'peyflex_airtime_airtel'], ['value' => '1.4', 'group' => 'pricing']);
        \App\Models\Setting::updateOrCreate(['key' => 'peyflex_airtime_glo'], ['value' => '2', 'group' => 'pricing']);
        \App\Models\Setting::updateOrCreate(['key' => 'peyflex_airtime_9mobile'], ['value' => '2', 'group' => 'pricing']);
        \App\Models\Setting::updateOrCreate(['key' => 'peyflex_data_shared_cg'], ['value' => '5', 'group' => 'pricing']);
        \App\Models\Setting::updateOrCreate(['key' => 'peyflex_data_gifting'], ['value' => '1', 'group' => 'pricing']);
    }
}
