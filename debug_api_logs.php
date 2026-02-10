<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ApiLog;

echo "--- FETCHING LAST 5 EPINS API LOGS ---\n\n";

try {
    $logs = ApiLog::where('provider', 'epins')
        ->latest()
        ->take(5)
        ->get();

    if ($logs->isEmpty()) {
        echo "No logs found for provider 'epins'.\n";
    }

    foreach ($logs as $log) {
        echo "ID: " . $log->id . "\n";
        echo "Time: " . $log->created_at . "\n";
        echo "Method: " . $log->method . " " . $log->url . "\n";
        echo "Status: " . $log->status_code . "\n";
        echo "Request: " . json_encode($log->request_payload, JSON_PRETTY_PRINT) . "\n";
        echo "Response: " . json_encode($log->response_payload, JSON_PRETTY_PRINT) . "\n";
        echo "--------------------------------------------------\n\n";
    }

} catch (\Exception $e) {
    echo "Error fetching logs: " . $e->getMessage() . "\n";
}
