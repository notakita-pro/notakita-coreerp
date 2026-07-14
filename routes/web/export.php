<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Export\ExportController;

/*
|--------------------------------------------------------------------------
| EXPORT ROUTES
|--------------------------------------------------------------------------
|
| Seluruh fitur Export Publik.
|
| Route pada file ini TIDAK memerlukan login.
|
| Digunakan untuk:
|
| - Share Receipt
| - PDF Receipt
| - QR Code (future)
| - Barcode (future)
| - Invoice (future)
|
*/


Route::prefix('export')
    ->name('export.')
    ->controller(ExportController::class)
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Receipt Image
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/receipt/{purchase}',
            'receipt'
        )->name('receipt');


        /*
        |--------------------------------------------------------------------------
        | Receipt PDF
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/receipt/{purchase}/pdf',
            'pdf'
        )->name('receipt.pdf');


        /*
        |--------------------------------------------------------------------------
        | Reserved Routes
        |--------------------------------------------------------------------------
        |
        | Future:
        |
        | export/invoice/{id}
        | export/label/{id}
        | export/barcode/{id}
        | export/qrcode/{id}
        |
        */

    });