<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AI\GeminiController;
use App\Http\Controllers\Export\ReportExportController;
use App\Http\Controllers\Company\ReportController;

/*
|--------------------------------------------------------------------------
| REPORT ROUTES
|--------------------------------------------------------------------------
|
| Modul Report
|
| Berisi:
| - Dashboard Analitik
| - Generate Report
| - AI Business Advisor
| - Export Excel / PDF
|
*/


/*
|--------------------------------------------------------------------------
| ADMIN REPORT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('company/{company}')
    ->name('admin.company.')
    ->group(function () {

        Route::prefix('report')
            ->name('report.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Report
                |--------------------------------------------------------------------------
                */

                Route::controller(ReportController::class)->group(function () {

                    Route::get(
                        '/',
                        'dashboard'
                    )->name('index');

                    Route::get(
                        '/generate',
                        'generateForm'
                    )->name('generate.form');

                    Route::post(
                        '/generate',
                        'generate'
                    )->name('generate');

                    Route::get(
                        '/today',
                        'today'
                    )->name('today');

                });

                /*
                |--------------------------------------------------------------------------
                | AI Business Advisor
                |--------------------------------------------------------------------------
                */

                Route::controller(GeminiController::class)->group(function () {

                    Route::get(
                        '/ai',
                        'businessAnalysis'
                    )->name('ai');

                });

                /*
                |--------------------------------------------------------------------------
                | Export
                |--------------------------------------------------------------------------
                */

                Route::controller(ReportExportController::class)->group(function () {

                    Route::get(
                        '/excel',
                        'excel'
                    )->name('excel');

                    Route::get(
                        '/pdf',
                        'pdf'
                    )->name('pdf');

                });

            });

    });


/*
|--------------------------------------------------------------------------
| CUSTOMER REPORT
|--------------------------------------------------------------------------
*/

Route::prefix('c/{token}')
    ->middleware('company.auth')
    ->name('company.')
    ->group(function () {

        Route::prefix('report')
            ->name('report.')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Report
                |--------------------------------------------------------------------------
                */

                Route::controller(ReportController::class)->group(function () {

                    Route::get(
                        '/',
                        'dashboard'
                    )->name('index');

                    Route::get(
                        '/generate',
                        'generateForm'
                    )->name('generate.form');

                    Route::post(
                        '/generate',
                        'generate'
                    )->name('generate');

                    Route::get(
                        '/today',
                        'today'
                    )->name('today');

                });

                /*
                |--------------------------------------------------------------------------
                | AI Business Advisor
                |--------------------------------------------------------------------------
                */

                Route::controller(GeminiController::class)->group(function () {

                    Route::get(
                        '/ai',
                        'businessAnalysis'
                    )->name('ai');

                });

                /*
                |--------------------------------------------------------------------------
                | Export
                |--------------------------------------------------------------------------
                */

                Route::controller(ReportExportController::class)->group(function () {

                    Route::get(
                        '/excel',
                        'excelByToken'
                    )->name('excel');

                    Route::get(
                        '/pdf',
                        'pdfByToken'
                    )->name('pdf');

                });

            });

    });