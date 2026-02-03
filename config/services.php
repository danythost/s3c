<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'epins' => [
        'base_url' => env('EPINS_MODE') === 'live' 
            ? env('EPINS_LIVE_BASE_URL', 'https://api.epins.com.ng/v3/autho')
            : env('EPINS_SANDBOX_BASE_URL', 'https://api.epins.com.ng/v1'),
        'api_key'  => env('EPINS_API_KEY'),
        'keys' => [
            'data' => env('EPINS_API_KEY'),
        ],
    ],

    'flutterwave' => [
        'public_key'     => env('FLW_PUBLIC_KEY'),
        'secret_key'     => env('FLW_SECRET_KEY'),
        'encryption_key' => env('FLW_ENCRYPTION_KEY'),
        'secret_hash'    => env('FLW_SECRET_HASH'),
        'test_bvn'       => env('FLW_TEST_BVN'),
    ],
    'vtuafrica' => [
        'key'         => env('VTUAFRICA_API_KEY'),
        'base_url'    => env('VTUAFRICA_BASE_URL', 'https://vtuafrica.com.ng/portal'),
        'sandbox_url' => env('VTUAFRICA_SANDBOX_URL', 'https://vtuafrica.com.ng/portal'),
    ],

];
