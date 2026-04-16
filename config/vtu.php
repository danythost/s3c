<?php

return [

    'peyflex' => [
        'mode' => env('PEYFLEX_MODE', 'production'),
        'base_url' => env('PEYFLEX_BASE_URL', 'https://client.peyflex.com.ng/api'),
        'api_key' => env('PEYFLEX_API_KEY'),
        
        // Airtime Commission Rates (Discount we get from provider)
        'airtime_rates' => [
            'mtn'     => 0.010, // 1%
            'airtel'  => 0.014, // 1.4%
            'glo'     => 0.020, // 2%
            '9mobile' => 0.020, // 2%
        ],
        'data_rates' => [
            'shared_cg' => 0.05, // 5% profit
            'gifting' => 0.01,   // 1% profit
        ],
    ],

];
