<?php

namespace App\Services;

use App\Models\MembershipOrder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentStatusService
{
    /**
     * --------------------------------------------------------------------------
     * Proses Status Pembayaran Midtrans
     * --------------------------------------------------------------------------
     */
    public function process(
        MembershipOrder $order,
        string $transactionStatus,
        array $payload = []
    ): array {

        $activateMembership = false;

        DB::transaction(function () use (
            $order,
            $transactionStatus,
            $payload,
            &$activateMembership
        ) {

            $payment = Payment::where(
                'membership_order_id',
                $order->id
            )
            ->latest()
            ->first();

            $status = strtolower($transactionStatus);

            switch ($status) {

                /*
                |--------------------------------------------------------------------------
                | PAID
                |--------------------------------------------------------------------------
                */

                case 'settlement':

                case 'capture':

                    if ($payment) {
                        $payment->markPaid($payload);
                    }

                    $order->markPaid();

                    $activateMembership = true;

                    Log::info('Payment berhasil.', [

                        'invoice' => $order->invoice_number,

                    ]);

                    break;

                /*
                |--------------------------------------------------------------------------
                | Pending
                |--------------------------------------------------------------------------
                */

                case 'pending':

                    if ($payment) {
                        $payment->markPending($payload);
                    }

                    $order->markPending();

                    Log::info('Payment Pending.', [

                        'invoice' => $order->invoice_number,

                    ]);

                    break;

                /*
                |--------------------------------------------------------------------------
                | Expired
                |--------------------------------------------------------------------------
                */

                case 'expire':

                    if ($payment) {
                        $payment->markExpired($payload);
                    }

                    $order->markExpired();

                    Log::info('Payment Expired.', [

                        'invoice' => $order->invoice_number,

                    ]);

                    break;

                /*
                |--------------------------------------------------------------------------
                | Cancel
                |--------------------------------------------------------------------------
                */

                case 'cancel':

                    if ($payment) {
                        $payment->markCancelled($payload);
                    }

                    $order->markCancelled();

                    Log::info('Payment Cancelled.', [

                        'invoice' => $order->invoice_number,

                    ]);

                    break;

                /*
                |--------------------------------------------------------------------------
                | Failed
                |--------------------------------------------------------------------------
                */

                case 'deny':

                case 'failure':

                case 'failed':

                    if ($payment) {
                        $payment->markFailed($payload);
                    }

                    $order->update([

                        'status' => 'FAILED',

                    ]);

                    Log::warning('Payment Failed.', [

                        'invoice' => $order->invoice_number,

                    ]);

                    break;

                /*
                |--------------------------------------------------------------------------
                | Unknown
                |--------------------------------------------------------------------------
                */

                default:

                    Log::warning('Status Midtrans tidak dikenali.', [

                        'invoice' => $order->invoice_number,

                        'status'  => $transactionStatus,

                    ]);

                    break;
            }

        });

        return [

            'status' => $order->fresh()->status,

            'activate_membership' => $activateMembership,

        ];

    }
}