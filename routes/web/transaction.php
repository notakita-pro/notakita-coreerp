<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Company\TransactionController;

/*
|--------------------------------------------------------------------------
| transaction ROUTES
|--------------------------------------------------------------------------
|
| Seluruh fitur transaksi (Admin & Customer)
|
*/


/*
|--------------------------------------------------------------------------
| ADMIN transaction
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::prefix('company/{company}')
        ->name('admin.company.')
        ->group(function () {

        // 2. Ubah controller di sini menjadi TransactionController
        Route::controller(TransactionController::class)
            ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | List Transaksi
            |--------------------------------------------------------------------------
            |
            */

            Route::get(
                '/transaction',
                'index'
            )->name('transaction');

        });

    });

});


/*
|--------------------------------------------------------------------------
| CUSTOMER transaction
|--------------------------------------------------------------------------
*/

Route::prefix('c')
    ->name('company.')
    ->middleware('company.auth')
    ->group(function () {

    // 3. Ubah juga controller di sini menjadi TransactionController
    Route::controller(TransactionController::class)
        ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | List Transaksi
        |--------------------------------------------------------------------------
        |
        */

        Route::get(
            '/{token}/transaction',
            'indexByToken'
        )->name('transaction');

    });

});