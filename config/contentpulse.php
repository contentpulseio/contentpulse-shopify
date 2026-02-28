<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | ContentPulse API Connection
    |--------------------------------------------------------------------------
    |
    | The base URL and API key used by the SDK client to pull content
    | from your ContentPulse instance.
    |
    */
    'api_url' => env('CONTENTPULSE_API_URL', 'https://api.contentpulse.io'),
    'api_key' => env('CONTENTPULSE_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Sync Settings
    |--------------------------------------------------------------------------
    */
    'auto_sync' => env('CONTENTPULSE_AUTO_SYNC', true),
    'sync_interval_minutes' => env('CONTENTPULSE_SYNC_INTERVAL', 15),
    'default_blog_id' => env('CONTENTPULSE_DEFAULT_BLOG_ID', null),
];
