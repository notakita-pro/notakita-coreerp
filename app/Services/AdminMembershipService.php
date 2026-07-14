<?php

namespace App\Services;

use App\Models\Company;
use App\Models\MembershipOrder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class AdminMembershipService
{
    /**
     * ==========================================================================
     * Ringkasan Membership
     * ==========================================================================
     */
    public function summary(): array
    {
        return [

            'total' => Company::count(),

            'free' => Company::query()
                ->where(function ($query) {

                    $query->whereNull('membership_type')
                        ->orWhere('membership_type', 'free');

                })
                ->count(),

            'silver' => Company::where(
                'membership_type',
                'silver'
            )->count(),

            'gold' => Company::where(
                'membership_type',
                'gold'
            )->count(),

            'active' => Company::query()
                ->whereNotNull('membership_expires_at')
                ->where('membership_expires_at', '>', now())
                ->count(),

            'expired' => Company::query()
                ->whereNotNull('membership_expires_at')
                ->where('membership_expires_at', '<=', now())
                ->count(),

        ];
    }

    /**
     * ==========================================================================
     * Daftar Company
     * ==========================================================================
     */
    public function companies(int $perPage = 20)
    {
        return Company::query()
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * ==========================================================================
     * Reset Membership Expired
     * ==========================================================================
     */
    public function resetExpiredMembership(): int
    {
        return DB::transaction(function () {

            $companies = Company::query()
                ->whereNotNull('membership_expires_at')
                ->where('membership_expires_at', '<=', now())
                ->get();

            foreach ($companies as $company) {

                $company->update([

                    'membership_type'       => 'free',

                    'membership_expires_at' => null,

                    'used_quota'            => 0,

                ]);

            }

            return $companies->count();

        });
    }

    /**
     * ==========================================================================
     * Hapus Invoice EXPIRED
     * ==========================================================================
     */
    public function deleteExpiredInvoices(): int
    {
        return MembershipOrder::query()
            ->where('status', 'EXPIRED')
            ->delete();
    }

    /**
     * ==========================================================================
     * Hapus Invoice CANCELLED
     * ==========================================================================
     */
    public function deleteCancelledInvoices(): int
    {
        return MembershipOrder::query()
            ->where('status', 'CANCELLED')
            ->delete();
    }

    /**
     * ==========================================================================
     * Reset Semua Membership
     * ==========================================================================
     */
    public function resetAllMembership(): int
    {
        return DB::transaction(function () {

            $total = Company::count();

            Company::query()->update([

                'membership_type'       => 'free',

                'membership_expires_at' => null,

                'used_quota'            => 0,

            ]);

            return $total;

        });
    }

    /**
     * ==========================================================================
     * Hapus Seluruh Invoice Membership
     * ==========================================================================
     */
    public function clearAllInvoices(): int
    {
        return DB::transaction(function () {

            $total = MembershipOrder::count();

            MembershipOrder::query()->delete();

            return $total;

        });
    }

    /**
     * ==========================================================================
     * Hapus Seluruh Payment Membership
     * ==========================================================================
     */
    public function clearAllPayments(): int
    {
        return DB::transaction(function () {

            $total = Payment::count();

            Payment::query()->delete();

            return $total;

        });
    }

    /**
     * ==========================================================================
     * Factory Reset
     * ==========================================================================
     *
     * Development Only.
     *
     * - Semua Company kembali FREE
     * - Quota direset
     * - Seluruh Invoice dihapus
     * - Seluruh Payment dihapus
     *
     */
    public function factoryReset(): array
    {
        return DB::transaction(function () {

            $company = $this->resetAllMembership();

            $payment = $this->clearAllPayments();

            $invoice = $this->clearAllInvoices();

            return [

                'company' => $company,

                'invoice' => $invoice,

                'payment' => $payment,

            ];

        });
    }
}