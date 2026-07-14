<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStatusController;

/*
|--------------------------------------------------------------------------
| PAYMENT MODULE
|--------------------------------------------------------------------------
|
| Seluruh route pembayaran CoreERP.
|
| Digunakan oleh:
| - Membership
| - Billing
| - Midtrans Redirect
| - Payment Status API
|
*/

/*
|--------------------------------------------------------------------------
| Redirect dari Payment Gateway
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment/finish',
    [PaymentController::class, 'finish']
)->name('payment.finish');


/*
|--------------------------------------------------------------------------
| Detail Invoice
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment/invoice/{order}',
    [PaymentController::class, 'invoice']
)->name('payment.invoice');


/*
|--------------------------------------------------------------------------
| Retry Payment
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment/retry/{order}',
    [PaymentController::class, 'retry']
)->name('payment.retry');


/*
|--------------------------------------------------------------------------
| Cancel Invoice
|--------------------------------------------------------------------------
*/

Route::post(
    '/payment/cancel/{order}',
    [PaymentController::class, 'cancel']
)->name('payment.cancel');


/*
|--------------------------------------------------------------------------
| Payment Status (AJAX)
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment/status/{order}',
    [PaymentStatusController::class, 'show']
)->name('payment.status');