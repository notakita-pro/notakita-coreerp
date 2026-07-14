<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome_elbeje');
});

Route::view('/privacy-and-policy.php', 'privacy');

Route::get('/terms-of-service.php', function () {
    return view('termsservice');
});


/*
|--------------------------------------------------------------------------
| LOAD MODULE ROUTES
|--------------------------------------------------------------------------
|
| Urutan pemanggilan sengaja disusun berdasarkan domain aplikasi.
|
*/

require __DIR__ . '/web/admin.php';

require __DIR__ . '/web/company.php';

require __DIR__ . '/web/purchase.php';
require __DIR__ . '/web/sales.php';
require __DIR__ . '/web/transaction.php';

require __DIR__ . '/web/report.php';
require __DIR__ . '/web/export.php';

require __DIR__ . '/web/webhook.php';
require __DIR__ . '/web/scheduler.php';

require __DIR__ . '/web/development.php';