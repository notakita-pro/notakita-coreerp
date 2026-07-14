<?php

namespace App\Services;

use App\Models\MembershipOrder;

class XenditPaymentService implements PaymentServiceInterface
{
    /**
     * Membuat Invoice
     */
    public function createInvoice(
        MembershipOrder $order
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Nanti diganti dengan API Xendit
        |--------------------------------------------------------------------------
        */

        return [

            'external_id' => 'COREERP-'
                . $order->invoice_number,

            'invoice_url' => '#',

            'expires_at' => now()->addDay(),

        ];
    }

    /**
     * Status Invoice
     */
    public function getStatus(
        string $externalId
    ): array {

        return [

            'status' => 'PENDING',

        ];
    }

    /**
     * Cancel Invoice
     */
    public function cancelInvoice(
        string $externalId
    ): bool {

        return true;
    }

    /**
     * Webhook
     */
    public function handleWebhook(
        array $payload
    ): bool {

        return true;
    }
}