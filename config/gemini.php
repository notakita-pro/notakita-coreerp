<?php

return [

    'base_url' => env(
        'GEMINI_BASE_URL',
        'https://generativelanguage.googleapis.com'
    ),

    'api_version' => env(
        'GEMINI_API_VERSION',
        'v1'
    ),

    'keys' => [

    [
        'name' => 'Free',

        'key' => env('GEMINI_PRIMARY_API_KEY'),

    ],

    [
        'name' => 'Paid',

        'key' => env('GEMINI_SECONDARY_API_KEY'),

    ],

],

    'model' => env(
        'GEMINI_MODEL',
        'gemini-3.1-flash-lite'
    ),

    'timeout' => env(
        'GEMINI_TIMEOUT',
        60
    ),

    'retry' => [

        'max_attempts' => env(
            'GEMINI_MAX_ATTEMPTS',
            2
        ),

    ],

];