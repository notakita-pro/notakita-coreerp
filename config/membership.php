<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paket Gratis
    |--------------------------------------------------------------------------
    */
    'free' => [

        'name'     => 'Gratis',
        'icon'     => '🆓',
        'color'    => '#6b7280',

        'quota'    => 15,
        'price'    => 0,
        'duration' => 30,

        // Hak Akses
        'export_excel' => true,
        'export_pdf'   => false,
        'business_ai'  => false,

    ],

    /*
    |--------------------------------------------------------------------------
    | Paket Silver
    |--------------------------------------------------------------------------
    */
    'silver' => [

        'name'     => 'Silver',
        'icon'     => '🥈',
        'color'    => '#2563eb',

        'quota'    => 150,
        'price'    => 100000,
        'duration' => 30,

        // Hak Akses
        'export_excel' => true,
        'export_pdf'   => true,
        'business_ai'  => false,

    ],

    /*
    |--------------------------------------------------------------------------
    | Paket Gold
    |--------------------------------------------------------------------------
    */
    'gold' => [

        'name'     => 'Gold',
        'icon'     => '🥇',
        'color'    => '#d97706',

        'quota'    => -1,
        'price'    => 500000,
        'duration' => 30,

        // Hak Akses
        'export_excel' => true,
        'export_pdf'   => true,
        'business_ai'  => true,

    ],

];