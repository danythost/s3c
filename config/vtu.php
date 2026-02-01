<?php

return [

    'epins' => [
        'mode' => env('EPINS_MODE', 'sandbox'),
        
        'base_url' => env('EPINS_MODE') === 'live'
            ? env('EPINS_LIVE_BASE_URL', 'https://api.epins.com.ng/v3/autho')
            : env('EPINS_SANDBOX_BASE_URL', 'https://api.epins.com.ng/v1'),

        'api_key'  => env('EPINS_API_KEY'),
        'username' => env('EPINS_USERNAME'),
        'bearer_token' => env('EPINS_BEARER_TOKEN'),
        'use_real_service' => env('USE_REAL_VT_SERVICE', false),
    ],

];
