<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * ==========================================================
     * ADMIN
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
     * Dashboard salah satu perusahaan.
     */
    public function show(Company $company)
    {
        abort_unless(
            auth()->check() &&
            auth()->user()->role == 1,
            403
        );

        return redirect()->route(
            'admin.company.dashboard',
            $company
        );
    }

    /**
     * ==========================================================
     * CUSTOMER
     * ==========================================================
     */

    /**
     * Form Edit Profil Perusahaan
     */
    public function editByToken(
        Request $request,
        string $token
    ) {
        $company = $request->attributes->get('company');

        return view(
            'company.edit',
            [
                'company' => $company,
                'token'   => $token,
            ]
        );
    }

    /**
     * Update Profil
     */
    public function updateByToken(
        Request $request,
        string $token
    ) {
        $company = $request->attributes->get('company');

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $company->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route(
                'company.dashboard',
                $token
            )
            ->with(
                'success',
                'Profil berhasil diperbarui.'
            );
    }
}