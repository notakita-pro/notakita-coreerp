<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Webhook\WhatsAppWebhookController;
use App\Http\Controllers\Webhook\MidtransWebhookController;
/*
|--------------------------------------------------------------------------
| EXTERNAL WEBHOOKS
|--------------------------------------------------------------------------
|
| Endpoint untuk layanan pihak ketiga.
| Route ini tidak memerlukan autentikasi login.
| CSRF dikecualikan melalui middleware Laravel.
|
*/


/*
|--------------------------------------------------------------------------
| WhatsApp Webhook
|--------------------------------------------------------------------------
*/

Route::prefix('webhook')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Verification (GET)
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/',
        [WhatsAppWebhookController::class, 'verify']
    )->name('webhook.whatsapp.verify');


    /*
    |--------------------------------------------------------------------------
    | Incoming Message (POST)
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/',
        [WhatsAppWebhookController::class, 'handle']
    )->name('webhook.whatsapp.handle');


    /*
    |--------------------------------------------------------------------------
    | Midtrans Notification
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/midtrans',
        [MidtransWebhookController::class, 'handle']
    )->name('webhook.midtrans');

});