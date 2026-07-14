<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\GeminiController;

/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION
|--------------------------------------------------------------------------
*/

$dynamicLoginSlug = 'login-nk' . date('mY');

/*
|--------------------------------------------------------------------------
| Login & Logout (Public / Guest Session)
|--------------------------------------------------------------------------
*/

Route::get('/' . $dynamicLoginSlug, [LoginController::class, 'index'])->name('login');
Route::post('/' . $dynamicLoginSlug, [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN CENTER (Authenticated Only)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard & Gemini Monitor
        |--------------------------------------------------------------------------
        */
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('dashboard');
        });
            
        Route::get('/gemini', [GeminiController::class, 'index'])->name('gemini.index');

        /*
        |--------------------------------------------------------------------------
        | Company Manager
        |--------------------------------------------------------------------------
        */
        Route::prefix('company')
            ->name('company.')
            ->controller(CompanyController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{company}', 'dashboard')->name('dashboard');
            });

        /*
        |--------------------------------------------------------------------------
        | Membership Center
        |--------------------------------------------------------------------------
        */
        Route::prefix('membership')
            ->name('membership.')
            ->controller(MembershipController::class)
            ->group(function () {
                
                // Dashboard
                Route::get('/', 'index')->name('index');

                // Maintenance
                Route::post('/reset-expired', 'resetExpired')->name('resetExpired');
                Route::post('/delete-expired-invoices', 'deleteExpiredInvoices')->name('deleteExpiredInvoices');
                Route::post('/delete-cancelled-invoices', 'deleteCancelledInvoices')->name('deleteCancelledInvoices');
                
                // Factory Reset (Development Only)
                Route::post('/factory-reset', 'factoryReset')->name('factoryReset');
            });

        /*
        |--------------------------------------------------------------------------
        | Billing Center
        |--------------------------------------------------------------------------
        */
        Route::prefix('billing')
            ->name('billing.')
            ->controller(BillingController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{order}', 'show')->name('show');
            });

    });