<?php

namespace App\Services\Logger;

use App\Models\ApiLog;

class ApiLogger
{
    public static function log($provider, $method, $url, $request, $response, $statusCode, $duration)
    {
        return ApiLog::create([
            'provider' => $provider,
            'method' => $method,
            'url' => $url,
            'request_payload' => $request,
            'response_payload' => $response,
            'status_code' => $statusCode,
            'duration_ms' => $duration,
        ]);
    }
}
