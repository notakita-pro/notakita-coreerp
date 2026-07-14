<?php

namespace App\Http\Middleware;

use App\Services\DashboardSecurityService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyDashboardAuth
{
    /**
     * Session timeout (615 detik)
     */
    const SESSION_TIMEOUT = 615;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Pengecualian Asset & Stream Receipt
        |--------------------------------------------------------------------------
        */

        $isReceiptRequest = str_contains($request->url(), '/receipt');

        if (
            $request->expectsJson() ||
            str_contains($request->url(), 'favicon.ico')
        ) {
            return $next($request);
        }

        $token = $request->route('token');

        if (! $token) {
            abort(404);
        }

        $company = DashboardSecurityService::findByToken($token);

        if (! $company) {

            $this->clearCompanySession();

            abort(404);

        }

        /*
        |--------------------------------------------------------------------------
        | 2. Belum Login
        |--------------------------------------------------------------------------
        */

        if (! session('company_authenticated')) {

            session([
                'company_intended_url' => $request->fullUrl(),
            ]);

            return redirect()->route(
                'company.pin.login',
                $token
            );

        }

        /*
        |--------------------------------------------------------------------------
        | 3. Cross Company Protection
        |--------------------------------------------------------------------------
        */

        if (session('company_id') != $company->id) {

            session([
                'company_intended_url' => $request->fullUrl(),
            ]);

            $this->clearCompanySession();

            return redirect()

                ->route('company.pin.login', $token)

                ->with(
                    'error',
                    'Akses ditolak.'
                );

        }

        /*
        |--------------------------------------------------------------------------
        | 4. Session Timeout
        |--------------------------------------------------------------------------
        */

        if (
            ! $isReceiptRequest &&
            session('company_authenticated') === true
        ) {

            $lastActivity = session('last_activity');

            $currentTime = time();

            $diff = $lastActivity
                ? ($currentTime - $lastActivity)
                : 0;

            if (
                $lastActivity &&
                $diff > self::SESSION_TIMEOUT
            ) {

                session([
                    'company_intended_url' => $request->fullUrl(),
                ]);

                $this->clearCompanySession();

                return redirect()

                    ->route(
                        'company.pin.login',
                        $token
                    )

                    ->with(
                        'timeout',
                        true
                    )

                    ->with(
                        'error',
                        'Sesi Anda telah berakhir karena tidak ada aktivitas.'
                    );

            }

        }

        /*
        |--------------------------------------------------------------------------
        | 5. Update Last Activity
        |--------------------------------------------------------------------------
        */

        if (! $isReceiptRequest) {

            session([
                'last_activity' => time(),
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | 6. Inject Company
        |--------------------------------------------------------------------------
        */

        $request->attributes->set(
            'company',
            $company
        );

        return $next($request);
    }

    /**
     * --------------------------------------------------------------------------
     * Clear Company Session
     * --------------------------------------------------------------------------
     */
    private function clearCompanySession(): void
    {
        session()->forget([

            'company_authenticated',

            'company_id',

            'last_activity',

            'company_intended_url',

        ]);

        session()->regenerateToken();
    }
}