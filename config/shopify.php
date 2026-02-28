<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Shopify API Credentials
    |--------------------------------------------------------------------------
    |
    | Configured via Shopify Partners Dashboard.
    |
    */
    'api_key' => env('SHOPIFY_API_KEY', ''),
    'api_secret' => env('SHOPIFY_API_SECRET', ''),
    'api_scopes' => env('SHOPIFY_API_SCOPES', 'write_content,read_content'),
    'api_redirect' => env('SHOPIFY_APP_DESTINATION', '/authenticate'),
];
