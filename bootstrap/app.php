<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\CompanyDashboardAuth;
use App\Http\Middleware\SuperAdmin;

use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([

            'company.auth' => CompanyDashboardAuth::class,

            'superadmin'   => SuperAdmin::class,

        ]);

        $middleware->validateCsrfTokens(except: [

            'webhook',
            'webhook/*',

            'midtrans/webhook',
            'midtrans/webhook/*',

        ]);

    })

    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (HttpException $e, $request) {

            if ($e->getStatusCode() == 403) {
                return response()->view('errors.403', [], 403);
            }

        });

    })

    ->create();