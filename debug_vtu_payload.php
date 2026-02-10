<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\VTU\EpinsVTUService;
use Illuminate\Support\Facades\Config;

// Mock Config
Config::set('vtu.epins.base_url', 'https://api.epins.com.ng/v3/autho'); // Using what's in .env
Config::set('vtu.epins.api_key', 'YOUR_API_KEY');
Config::set('vtu.epins.username', 'HENRY');

$service = new EpinsVTUService();

// Test Data Payload (N100 Data)
// Using Plan Code 826 (100MB 1 Day) found in Seeder
$dataPayload = [
    'network' => '9MOBILE', // mapped to '03'
    'phone' => '08012345678',
    'plan_code' => '826',
    'plan_id' => 123,
    'reference' => 'TEST_DATA_' . time()
];

// Test Airtime Payload (N100 Airtime)
$airtimePayload = [
    'network' => 'MTN', // mapped to '01'
    'phone' => '08012345678',
    'amount' => 100,
    'reference' => 'TEST_AIRTIME_' . time()
];

// Reflection to access mapNetwork and build request body without actual HTTP call
$reflection = new ReflectionClass($service);
$mapNetwork = $reflection->getMethod('mapNetwork');
$mapNetwork->setAccessible(true);

echo "--- SIMULATED DATA PURCHASE PAYLOAD ---\n";
echo "Endpoint: " . rtrim(config('vtu.epins.base_url'), '/') . "/data/\n";
echo "Payload: " . json_encode([
    'networkId'    => $mapNetwork->invoke($service, $dataPayload['network']),
    'MobileNumber' => $dataPayload['phone'],
    'DataPlan'     => $dataPayload['plan_code'], // uses plan_code if set
    'ref'          => $dataPayload['reference'],
], JSON_PRETTY_PRINT) . "\n\n";

echo "--- SIMULATED AIRTIME PURCHASE PAYLOAD ---\n";
echo "Endpoint: " . rtrim(config('vtu.epins.base_url'), '/') . "/airtime/\n";
echo "Payload: " . json_encode([
    'network' => $mapNetwork->invoke($service, $airtimePayload['network']), // Note the key is 'network', not 'networkId' for Airtime
    'phone'   => $airtimePayload['phone'],
    'amount'  => (int) $airtimePayload['amount'],
    'ref'     => $airtimePayload['reference'],
], JSON_PRETTY_PRINT) . "\n";
