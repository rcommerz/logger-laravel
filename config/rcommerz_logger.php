<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Service Configuration
    |--------------------------------------------------------------------------
    |
    | These values identify your service in logs.
    |
    */
    'service_name' => env('SERVICE_NAME', config('app.name', 'laravel-app')),
    'service_version' => env('SERVICE_VERSION', '1.0.0'),
    'env' => env('APP_ENV', 'production'),
    'level' => env('LOG_LEVEL', 'INFO'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Middleware Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which paths to exclude from HTTP logging and what to include.
    |
    */
    'exclude_paths' => [
        'health',
        'metrics',
        'api/health',
        'api/metrics',
    ],

    'include_headers' => env('LOG_INCLUDE_HEADERS', false),
    'include_body' => env('LOG_INCLUDE_BODY', false),
];
