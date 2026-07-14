<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipOrder;
use App\Services\BillingService;

class BillingController extends Controller
{
    /**
     * ==========================================================
     * BILLING CENTER (ADMIN)
     * ==========================================================
     *
     * Digunakan untuk monitoring seluruh transaksi Membership.
     *
     * Tanggung Jawab:
     * - Dashboard Billing
     * - Monitoring Invoice
     * - Monitoring Pembayaran
     * - Revenue
     * - Subscription
     * - Aktivasi Manual (Future)
     *
     */

    /**
     * Billing Service.
     */
    public function __construct(
        protected BillingService $billingService
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Dashboard Billing
     * --------------------------------------------------------------------------
     */
    public function index()
    {
        $orders = MembershipOrder::query()
            ->with('company')
            ->latest()
            ->paginate(20);

        $summary = [

            'pending' => MembershipOrder::where('status', 'PENDING')->count(),

            'paid' => MembershipOrder::where('status', 'PAID')->count(),

            'expired' => MembershipOrder::where('status', 'EXPIRED')->count(),

            'cancelled' => MembershipOrder::where('status', 'CANCELLED')->count(),

            'revenue' => MembershipOrder::where('status', 'PAID')
                ->sum('amount'),

        ];

        return view(
            'admin.billing.index',
            compact(
                'orders',
                'summary'
            )
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Detail Invoice
     * --------------------------------------------------------------------------
     *
     * Akan digunakan untuk melihat detail pembayaran,
     * histori callback Midtrans dan informasi invoice.
     */
    public function show(MembershipOrder $order)
    {
        return view(
            'admin.billing.show',
            compact('order')
        );
    }
}