<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Meta Graph API
    |--------------------------------------------------------------------------
    */

    'base_url' => env(
        'WHATSAPP_GRAPH_BASE_URL',
        'https://graph.facebook.com'
    ),

    'graph_version' => env(
        'WHATSAPP_GRAPH_VERSION',
        'v25.0'
    ),

    'timeout' => env(
        'WHATSAPP_TIMEOUT',
        60
    ),

    'download_retry' => env(
        'WHATSAPP_DOWNLOAD_RETRY',
        2
    ),

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    */

    'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),

    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),

    'waba_id' => env('WHATSAPP_WABA_ID'),

    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),

];