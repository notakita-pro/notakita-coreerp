<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * ==========================================================
     * COMPANY PROFILE
     * ==========================================================
     */

    /**
     * Tampilkan Form Edit Profil Perusahaan.
     */
    public function edit(
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
     * Update Profil Perusahaan.
     */
    public function update(
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
                [
                    'token' => $token,
                ]
            )
            ->with(
                'success',
                'Profil perusahaan berhasil diperbarui.'
            );
    }
}