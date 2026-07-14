<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\MembershipController;
use App\Http\Controllers\Company\ProfileController;

use App\Http\Controllers\Auth\CompanyPinController;
use App\Http\Controllers\Auth\CompanyLoginController;


/*
|--------------------------------------------------------------------------
| COMPANY AREA
|--------------------------------------------------------------------------
|
| Customer Area
|
| URL Prefix :
|     /c
|
| Route Name :
|     company.*
|
| Modul bisnis (Purchase, Report, Transaction, Sales, dll)
| memiliki file route masing-masing.
|
*/

Route::prefix('c')
    ->name('company.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | ENTRY
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/{token}',
            [CompanyLoginController::class, 'entry']
        )->name('entry');

        /*
        |--------------------------------------------------------------------------
        | LOGOUT
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/logout',
            [CompanyLoginController::class, 'logout']
        )->name('logout');

        /*
        |--------------------------------------------------------------------------
        | CREATE PIN
        |--------------------------------------------------------------------------
        */

        Route::controller(CompanyPinController::class)
            ->group(function () {

                Route::get(
                    '/{token}/create-pin',
                    'create'
                )->name('pin.create');

                Route::post(
                    '/{token}/create-pin',
                    'store'
                )->name('pin.store');

            });

        /*
        |--------------------------------------------------------------------------
        | LOGIN PIN
        |--------------------------------------------------------------------------
        */

        Route::controller(CompanyLoginController::class)
            ->group(function () {

                Route::get(
                    '/{token}/login',
                    'create'
                )->name('pin.login');

                Route::post(
                    '/{token}/login',
                    'store'
                )->name('pin.login.store');

            });

        /*
        |--------------------------------------------------------------------------
        | PROTECTED AREA
        |--------------------------------------------------------------------------
        */

        Route::prefix('{token}')
            ->middleware('company.auth')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Dashboard
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/dashboard',
                    [DashboardController::class, 'index']
                )->name('dashboard');

                /*
                |--------------------------------------------------------------------------
                | Membership Center
                |--------------------------------------------------------------------------
                */

                Route::controller(MembershipController::class)
                    ->group(function () {

                        Route::get(
                            '/membership',
                            'index'
                        )->name('membership');

                        Route::post(
                            '/membership/upgrade',
                            'upgrade'
                        )->name('membership.upgrade');

                        Route::get(
                            '/payment/{order}',
                            'payment'
                        )->name('payment');

                    });

                /*
                |--------------------------------------------------------------------------
                | Company Profile
                |--------------------------------------------------------------------------
                */

                Route::controller(ProfileController::class)
                    ->group(function () {

                        Route::get(
                            '/profile',
                            'edit'
                        )->name('profile.edit');

                        Route::post(
                            '/profile',
                            'update'
                        )->name('profile.update');

                    });

                /*
                |--------------------------------------------------------------------------
                | Heartbeat
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/heartbeat',
                    function () {

                        return response()->json([
                            'status' => 'alive',
                        ]);

                    }
                )->name('heartbeat');

            });

    });