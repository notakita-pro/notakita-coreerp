<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\AdminMembershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MembershipController extends Controller
{
    /**
     * ==========================================================================
     * Constructor
     * ==========================================================================
     */
    public function __construct(
        protected AdminMembershipService $membershipService
    ) {
    }

    /**
     * ==========================================================================
     * Dashboard Membership
     * ==========================================================================
     */
    public function index(): View
    {
        $this->authorizeAdmin();

        $companies = Company::query()
            ->orderBy('name')
            ->paginate(20);

        $summary = $this->membershipService->summary();

        return view(
            'admin.membership.index',
            compact(
                'companies',
                'summary'
            )
        );
    }

    /**
     * ==========================================================================
     * Reset Membership Expired
     * ==========================================================================
     *
     * Mengembalikan seluruh membership yang sudah habis
     * menjadi paket FREE.
     *
     */
    public function resetExpired(): RedirectResponse
    {
        $this->authorizeAdmin();

        $total = $this->membershipService
            ->resetExpiredMembership();

        return back()->with(
            'success',
            "{$total} membership berhasil dikembalikan menjadi FREE."
        );
    }

    /**
     * ==========================================================================
     * Hapus Invoice Expired
     * ==========================================================================
     */
    public function deleteExpiredInvoices(): RedirectResponse
    {
        $this->authorizeAdmin();

        $total = $this->membershipService
            ->deleteExpiredInvoices();

        return back()->with(
            'success',
            "{$total} invoice EXPIRED berhasil dihapus."
        );
    }

    /**
     * ==========================================================================
     * Hapus Invoice Cancelled
     * ==========================================================================
     */
    public function deleteCancelledInvoices(): RedirectResponse
    {
        $this->authorizeAdmin();

        $total = $this->membershipService
            ->deleteCancelledInvoices();

        return back()->with(
            'success',
            "{$total} invoice CANCELLED berhasil dihapus."
        );
    }

    /**
     * ==========================================================================
     * Factory Reset
     * ==========================================================================
     *
     * Digunakan hanya pada mode Development / Testing.
     *
     * Yang dilakukan:
     * - Seluruh Company kembali FREE
     * - Reset quota
     * - Hapus seluruh Membership Order
     * - Hapus seluruh Payment
     *
     */
    public function factoryReset(): RedirectResponse
    {
        $this->authorizeAdmin();

        $result = $this->membershipService
            ->factoryReset();

        return back()->with(
            'success',
            "{$result['company']} company berhasil direset, {$result['invoice']} invoice dan {$result['payment']} payment berhasil dihapus."
        );
    }

    /**
     * ==========================================================================
     * Authorize Super Admin
     * ==========================================================================
     */
    protected function authorizeAdmin(): void
    {
        abort_unless(
            auth()->check()
            && auth()->user()->role == 1,
            403
        );
    }
}