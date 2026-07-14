<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\PurchaseController;

/*
|--------------------------------------------------------------------------
| PURCHASE ROUTES (REFUGEE / ROUTE REFACTORING)
|--------------------------------------------------------------------------
|
| Modul penanganan invoice pembelian yang melayani dua ranah:
| 1. ADMIN INTERNAL (Menggunakan Auth Session Dashboard)
| 2. CUSTOMER PUBLIC (Menggunakan Access Token URL)
|
*/

/*
|--------------------------------------------------------------------------
| 1. ADMIN PURCHASE PANEL
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('company/{company}')
    ->name('admin.company.')
    ->controller(PurchaseController::class)
    ->group(function () {

        Route::get('/purchase', 'index')->name('purchase');
        Route::get('/purchase/{purchase}', 'show')->name('purchase.show');
        Route::get('/purchase/{purchase}/edit', 'edit')->name('purchase.edit');
        
        // 🟢 TAMBAHKAN BARIS INI UNTUK UPDATE ADMIN
        Route::put('/purchase/{purchase}', 'update')->name('purchase.update'); 

        Route::delete('/purchase/{purchase}', 'destroy')->name('purchase.destroy');
        Route::get('/purchase/{purchase}/receipt', 'viewReceipt')->name('purchase.receipt.image');
        Route::get('/purchase/{purchase}/export', 'export')->name('purchase.export');
    });


/*
|--------------------------------------------------------------------------
| 2. CUSTOMER PURCHASE PUBLIC AREA
|--------------------------------------------------------------------------
*/
Route::prefix('c/{token}')
    ->middleware('company.auth')
    ->name('company.')
    ->controller(PurchaseController::class)
    ->group(function () {

        Route::get('/purchase', 'indexByToken')->name('purchase');
        Route::get('/purchase/{purchase}', 'showByToken')->name('purchase.show');
        Route::get('/purchase/{purchase}/edit', 'editByToken')->name('purchase.edit');

        // 🟢 TAMBAHKAN BARIS INI UNTUK UPDATE CUSTOMER
        Route::put('/purchase/{purchase}', 'updateByToken')->name('purchase.update');

        Route::delete('/purchase/{purchase}', 'destroyByToken')->name('purchase.destroy');
        Route::get('/purchase/{purchase}/receipt', 'viewReceiptByToken')->name('purchase.receipt.image');
        Route::get('/purchase/{purchase}/export', 'exportByToken')->name('purchase.export');
    });