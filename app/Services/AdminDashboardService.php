<?php

namespace App\Services;

use App\Models\Company;
use App\Models\MembershipOrder;

class AdminDashboardService
{
    /**
     * ==========================================================
     * ADMIN DASHBOARD SUMMARY
     * ==========================================================
     *
     * Service ini menyediakan ringkasan data untuk
     * CoreERP Admin Center.
     *
     * Tanggung Jawab:
     * - Company Summary
     * - Membership Summary
     * - Billing Summary
     * - AI Summary (placeholder)
     * - System Summary (placeholder)
     *
     * Service ini hanya mengembalikan data ringkasan.
     * Detail data tetap ditangani oleh modul masing-masing.
     * ==========================================================
     */

    /**
     * --------------------------------------------------------------------------
     * Dashboard Summary
     * --------------------------------------------------------------------------
     */
    public function summary(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            'company' => [

                'total' => Company::count(),

            ],

            /*
            |--------------------------------------------------------------------------
            | Membership
            |--------------------------------------------------------------------------
            */

            'membership' => [

                'free' => Company::where('membership_type', 'free')->count(),

                'silver' => Company::where('membership_type', 'silver')->count(),

                'gold' => Company::where('membership_type', 'gold')->count(),

                'active' => Company::whereNotNull('membership_expires_at')
                    ->where('membership_expires_at', '>', now())
                    ->count(),

                'expired' => Company::whereNotNull('membership_expires_at')
                    ->where('membership_expires_at', '<=', now())
                    ->count(),

            ],

            /*
            |--------------------------------------------------------------------------
            | Billing
            |--------------------------------------------------------------------------
            */

            'billing' => [

                'pending' => MembershipOrder::where('status', 'PENDING')->count(),

                'paid' => MembershipOrder::where('status', 'PAID')->count(),

                'expired' => MembershipOrder::where('status', 'EXPIRED')->count(),

                'cancelled' => MembershipOrder::where('status', 'CANCELLED')->count(),

                'revenue' => MembershipOrder::where('status', 'PAID')
                    ->sum('amount'),

            ],

            /*
            |--------------------------------------------------------------------------
            | AI Monitoring
            |--------------------------------------------------------------------------
            |
            | Placeholder.
            | Akan digunakan setelah AI Center selesai dibuat.
            |
            */

            'ai' => [

                'queue' => 0,

                'processing' => 0,

                'success' => 0,

                'failed' => 0,

            ],

            /*
            |--------------------------------------------------------------------------
            | System
            |--------------------------------------------------------------------------
            */

            'system' => [

                'status' => 'Healthy',

                'queue' => 0,

                'webhook' => 0,

                'scheduler' => 'Running',

            ],

        ];
    }
}