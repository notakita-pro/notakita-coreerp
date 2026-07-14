<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\SalesController;

/*
|--------------------------------------------------------------------------
| SALES ROUTES
|--------------------------------------------------------------------------
|
| Modul Penjualan
| - Admin
| - Customer (Access Token)
|
*/


/*
|--------------------------------------------------------------------------
| ADMIN SALES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('company/{company}')
    ->name('admin.company.')
    ->group(function () {

        Route::prefix('sales')
            ->name('sales.')
            ->controller(SalesController::class)
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Dashboard Sales
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    'index'
                )->name('index');

                /*
                |--------------------------------------------------------------------------
                | Tambah Penjualan
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/create',
                    'create'
                )->name('create');

                Route::post(
                    '/',
                    'store'
                )->name('store');

                /*
                |--------------------------------------------------------------------------
                | Edit Penjualan
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/{sale}/edit',
                    'edit'
                )->name('edit');

                Route::put(
                    '/{sale}',
                    'update'
                )->name('update');

            });

    });


/*
|--------------------------------------------------------------------------
| CUSTOMER SALES
|--------------------------------------------------------------------------
*/

Route::prefix('c/{token}')
    ->middleware('company.auth')
    ->name('company.')
    ->group(function () {

        Route::prefix('sales')
            ->name('sales.')
            ->controller(SalesController::class)
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Dashboard Sales
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    'index'
                )->name('index');

                /*
                |--------------------------------------------------------------------------
                | Tambah Penjualan
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/create',
                    'create'
                )->name('create');

                Route::post(
                    '/',
                    'store'
                )->name('store');

                /*
                |--------------------------------------------------------------------------
                | Edit Penjualan
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/{sale}/edit',
                    'edit'
                )->name('edit');

                Route::put(
                    '/{sale}',
                    'update'
                )->name('update');

            });

    });