<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Services\WhatsAppService;


/*
|--------------------------------------------------------------------------
| DEVELOPMENT TOOLS
|--------------------------------------------------------------------------
|
| Khusus Developer.
| Sebaiknya hanya dapat diakses oleh Super Admin.
|
*/

Route::get('/dev/routes/check', function () {

    abort_unless(auth()->check() && auth()->user()->role == 1, 403);
    
    $routes = collect(app('router')->getRoutes())
        ->map(function ($route) {
            return [
                'name'   => $route->getName(),
                'uri'    => $route->uri(),
                'method' => implode('|', $route->methods()),
            ];
        })
        ->sortBy('name')
        ->values();

    return response()->json($routes);

})->name('dev.routes.check');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Clear Cache
    |--------------------------------------------------------------------------
    */

    Route::get('/bersihkan-cache-sekarang', function () {

        abort_unless(auth()->user()->role == 1, 403);

        Artisan::call('optimize:clear');

        return response()->json([
            'success' => true,
            'message' => 'Cache berhasil dibersihkan.'
        ]);

    })->name('dev.cache.clear');


    /*
    |--------------------------------------------------------------------------
    | Run Migration
    |--------------------------------------------------------------------------
    */

    Route::get('/jalankan-migrasi', function () {

        abort_unless(auth()->user()->role == 1, 403);

        Artisan::call('migrate', [
            '--force' => true,
        ]);

        return nl2br(Artisan::output());

    })->name('dev.migrate');


    /*
    |--------------------------------------------------------------------------
    | Storage Link
    |--------------------------------------------------------------------------
    */

    Route::get('/buat-storage-link', function () {

        abort_unless(auth()->user()->role == 1, 403);

        Artisan::call('storage:link');

        return Artisan::output();

    })->name('dev.storage');


    /*
    |--------------------------------------------------------------------------
    | Optimize
    |--------------------------------------------------------------------------
    */

    Route::get('/optimize', function () {

        abort_unless(auth()->user()->role == 1, 403);

        Artisan::call('optimize');

        return Artisan::output();

    })->name('dev.optimize');


    /*
    |--------------------------------------------------------------------------
    | Queue Restart
    |--------------------------------------------------------------------------
    */

    Route::get('/queue-restart', function () {

        abort_unless(auth()->user()->role == 1, 403);

        Artisan::call('queue:restart');

        return Artisan::output();

    })->name('dev.queue.restart');

});