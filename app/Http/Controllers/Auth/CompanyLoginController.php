<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use App\Services\DashboardSecurityService;
use Illuminate\Http\Request;

class CompanyLoginController extends Controller
{
    /**
     * Entry dari link WhatsApp.
     */
    public function entry(string $token)
    {
        $company = DashboardSecurityService::findByToken($token);

        abort_if(!$company, 404);

        if (!DashboardSecurityService::hasPin($company)) {
            return redirect()->route(
                'company.pin.create',
                [
                    'token' => $token,
                ]
            );
        }

        return redirect()->route(
            'company.pin.login',
            [
                'token' => $token,
            ]
        );
    }

    /**
     * Form Login PIN.
     */
    public function create(string $token)
    {
        $company = DashboardSecurityService::findByToken($token);

        abort_if(!$company, 404);

        if (!DashboardSecurityService::hasPin($company)) {
            return redirect()->route(
                'company.pin.create',
                [
                    'token' => $token,
                ]
            );
        }

        $locked = DashboardSecurityService::isLocked($company);

        // Ambil status timeout dari flash session middleware
        $isTimeout = session()->has('timeout');

        return view('company.login', [
            'company'   => $company,
            'locked'    => $locked,
            'isTimeout' => $isTimeout, // Dikirim ke blade untuk mematikan auto-submit JavaScript
            'remaining' => $locked
                ? DashboardSecurityService::remainingLockMinutes($company)
                : 0,
        ]);
    }

    /**
     * Proses Login PIN.
     */
    public function store(
        Request $request,
        string $token
    ) {
        $company = DashboardSecurityService::findByToken($token);

        abort_if(!$company, 404);

        $request->validate([
            'pin' => [
                'required',
                'digits:6',
            ],
        ]);

        /*
        |--------------------------------------------------
        | Dashboard sedang dikunci
        |--------------------------------------------------
        */
        if (DashboardSecurityService::isLocked($company)) {
            return back()->withErrors([
                'pin' =>
                    'Dashboard dikunci selama '
                    . DashboardSecurityService::remainingLockMinutes($company)
                    . ' menit.',
            ]);
        }

        /*
        |--------------------------------------------------
        | Verifikasi PIN
        |--------------------------------------------------
        */
        if (
            !DashboardSecurityService::verifyPin(
                $company,
                $request->pin
            )
        ) {
            $company->refresh();

            return back()->withErrors([
                'pin' =>
                    'PIN salah. Sisa percobaan : '
                    . (
                        DashboardSecurityService::MAX_ATTEMPTS
                        - $company->failed_attempts
                    ),
            ]);
        }

        /*
        |--------------------------------------------------
        | Login berhasil
        |--------------------------------------------------
        */
        $request->session()->regenerate();

        session([
            'company_authenticated' => true,
            'company_id'            => $company->id,
            'company_token'         => $company->access_token,
            'last_activity'         => time(),
        ]);
        
       /*
        |--------------------------------------------------
        | Redirect ke halaman yang diminta sebelumnya
        |--------------------------------------------------
        */
        
        $intendedUrl = session()->pull(
            'company_intended_url'
        );
        
        if ($intendedUrl) {
        
            return redirect()->to(
                $intendedUrl
            );
        
        }
        
        return redirect()->route(
            'company.dashboard',
            [
                'token' => $token,
            ]
        );
    }

    /**
     * Logout Dashboard.
     */
    
    
public function logout(Request $request)
    {
        $token = $request->session()->get('company_token');

        session()->forget([

    'company_authenticated',

    'company_id',

    'company_token',

    'last_activity',

    'company_intended_url',

]);

        if ($token) {
            return redirect()->route(
                'company.public.dashboard',
                $token
            );
        }

        return redirect('/');
    }
  
}