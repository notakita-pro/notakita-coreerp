<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * ==========================================================
     * COMPANY MANAGEMENT (ADMIN)
     * ==========================================================
     */

    /**
     * Daftar seluruh perusahaan.
     */
    public function index()
    {
        abort_unless(
            auth()->check() &&
            auth()->user()->role == 1,
            403
        );

        $companies = Company::query()
            ->orderBy('name')
            ->paginate(20);

        return view(
            'admin.company.index',
            compact('companies')
        );
    }

    /**
     * Masuk ke Dashboard Company sebagai Administrator.
     *
     * Saat ini langsung diarahkan ke dashboard company
     * menggunakan access token.
     *
     * Ke depan method ini akan menjadi tempat
     * implementasi "Support Mode / Impersonate".
     */
    public function dashboard(Company $company)
    {
        abort_unless(
            auth()->check() &&
            auth()->user()->role == 1,
            403
        );

        return redirect()->route(
            'company.dashboard',
            [
                'token' => $company->access_token,
            ]
        );
    }
}