<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\DashboardSecurityService;

class CompanyPinController extends Controller
{
    /**
     * Tampilkan halaman pembuatan PIN.
     */
    public function create(string $token)
    {
        $company = DashboardSecurityService::findByToken($token);

        abort_if(!$company, 404);

        // Jika sudah punya PIN langsung ke login
        if (DashboardSecurityService::hasPin($company)) {

            return redirect()->route(
                'company.pin.login',
                $token
            );
        }

        return view('company.create-pin', [
            'company' => $company,
        ]);
    }

    /**
     * Simpan PIN pertama.
     */
    public function store(
        Request $request,
        string $token
    ) {
        $company = DashboardSecurityService::findByToken($token);

        abort_if(!$company, 404);

        if (DashboardSecurityService::hasPin($company)) {

            return redirect()->route(
                'company.pin.login',
                $token
            );
        }

        $request->validate([

            'pin' => [
                'required',
                'digits:6',
                'confirmed',
            ],

        ], [

            'pin.required' => 'PIN wajib diisi.',

            'pin.digits' => 'PIN harus terdiri dari 6 digit.',

            'pin.confirmed' => 'Konfirmasi PIN tidak sama.',

        ]);

        DashboardSecurityService::createPin(
            $company,
            $request->pin
        );

        return redirect()
            ->route(
                'company.pin.login',
                $token
            )
            ->with(
                'success',
                'PIN berhasil dibuat. Silakan login.'
            );
    }
}