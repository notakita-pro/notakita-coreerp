<?php

namespace App\Http\Controllers;

use App\Models\MembershipOrder;

class PaymentStatusController extends Controller
{
    /**
     * ==========================================================================
     * PAYMENT STATUS API
     * ==========================================================================
     *
     * Endpoint AJAX untuk memonitor status pembayaran.
     *
     * Digunakan oleh:
     * - payment.finish
     * - Mobile App (future)
     * - SPA / Dashboard (future)
     *
     * Route Model Binding menggunakan invoice_number.
     * ==========================================================================
     */
    public function show(
        MembershipOrder $order
    )
    {
        $order->loadMissing('company');

        $company = $order->company;

        return response()->json([

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'success' => true,

            'invoice' => $order->invoice_number,

            'status' => $order->getStatus(),

            'finished' => $order->isFinished(),

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment_url' => $order->payment_url,

            'paid_at' => optional($order->paid_at)
                ?->format('Y-m-d H:i:s'),

            'expires_at' => optional($order->expires_at)
                ?->format('Y-m-d H:i:s'),

            /*
            |--------------------------------------------------------------------------
            | Company
            |--------------------------------------------------------------------------
            */

            'company' => $company?->name,

            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            |
            | Setelah webhook mengubah status menjadi PAID,
            | frontend akan otomatis mengarahkan customer
            | kembali ke Membership Center.
            |
            */

            'redirect' => $company
                ? route(
                    'company.membership',
                    [
                        'token' => $company->access_token,
                    ]
                )
                : null,

        ]);
    }
}