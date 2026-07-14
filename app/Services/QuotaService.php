<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Log;

class QuotaService
{
    /**
     * --------------------------------------------------------------------------
     * Mengurangi (menggunakan) 1 kuota upload
     * --------------------------------------------------------------------------
     *
     * Dipanggil setelah nota berhasil disimpan.
     */
    public function consume(Company $company): void
    {
        $membership = app(MembershipService::class)->get($company);

        /*
        |--------------------------------------------------------------------------
        | Paket Unlimited
        |--------------------------------------------------------------------------
        */
        if ($membership['quota'] == -1) {

            return;

        }

        /*
        |--------------------------------------------------------------------------
        | Tambah penggunaan kuota
        |--------------------------------------------------------------------------
        */

        $company->increment('used_quota');

        Log::info('Kuota digunakan', [

            'company_id' => $company->id,

            'used_quota' => $company->fresh()->used_quota,

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Reset kuota bulanan
     * --------------------------------------------------------------------------
     */
    public function reset(Company $company): void
    {
        $company->update([

            'used_quota' => 0,

        ]);

        Log::info('Kuota berhasil direset', [

            'company_id' => $company->id,

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Menambah bonus kuota
     * --------------------------------------------------------------------------
     */
    public function add(Company $company, int $qty): void
    {
        if ($qty <= 0) {
            return;
        }

        $company->increment('used_quota', -$qty);

        if ($company->fresh()->used_quota < 0) {

            $company->update([
                'used_quota' => 0,
            ]);

        }

        Log::info('Bonus kuota diberikan', [

            'company_id' => $company->id,

            'bonus'      => $qty,

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Apakah kuota masih tersedia?
     * --------------------------------------------------------------------------
     */
    public function hasQuota(Company $company): bool
    {
        $membership = app(MembershipService::class)->get($company);

        if ($membership['quota'] == -1) {
            return true;
        }

        return $membership['remaining'] > 0;
    }
}