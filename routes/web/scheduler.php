<?php

use Illuminate\Support\Facades\Route;

use App\Jobs\Membership\ExpireInvoiceJob;
use App\Jobs\Membership\ExpireMembershipJob;
use App\Jobs\Membership\ReminderPaymentJob;
use App\Jobs\Membership\ResetQuotaJob;

/*
|--------------------------------------------------------------------------
| MANUAL SCHEDULER TEST
|--------------------------------------------------------------------------
|
| Digunakan untuk menjalankan Job secara manual.
| Sebaiknya hanya dapat diakses oleh Administrator.
|
*/

Route::prefix('scheduler')
    ->middleware('auth')
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Membership
    |--------------------------------------------------------------------------
    */

    Route::get('/expire-invoice', function () {

        abort_unless(auth()->user()->role == 1, 403);

        (new ExpireInvoiceJob())->handle();

        return response()->json([
            'success' => true,
            'job'     => 'ExpireInvoiceJob',
            'status'  => 'completed',
        ]);

    })->name('scheduler.expire.invoice');


    Route::get('/expire-membership', function () {

        abort_unless(auth()->user()->role == 1, 403);

        (new ExpireMembershipJob())->handle();

        return response()->json([
            'success' => true,
            'job'     => 'ExpireMembershipJob',
            'status'  => 'completed',
        ]);

    })->name('scheduler.expire.membership');


    Route::get('/reset-quota', function () {

        abort_unless(auth()->user()->role == 1, 403);

        (new ResetQuotaJob())->handle();

        return response()->json([
            'success' => true,
            'job'     => 'ResetQuotaJob',
            'status'  => 'completed',
        ]);

    })->name('scheduler.reset.quota');


    Route::get('/payment-reminder', function () {

        abort_unless(auth()->user()->role == 1, 403);

        (new ReminderPaymentJob())->handle();

        return response()->json([
            'success' => true,
            'job'     => 'ReminderPaymentJob',
            'status'  => 'completed',
        ]);

    })->name('scheduler.payment.reminder');

});