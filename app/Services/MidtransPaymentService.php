<?php

namespace App\Services;

use App\Models\MembershipOrder;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransPaymentService implements PaymentServiceInterface
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');

        Config::$isProduction = config('midtrans.is_production');

        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * --------------------------------------------------------------------------
     * Membuat Transaksi Midtrans Snap
     * --------------------------------------------------------------------------
     */
    public function createPayment(
        MembershipOrder $order
    ): Payment {

        /*
        |--------------------------------------------------------------------------
        | URL setelah selesai dari Midtrans
        |--------------------------------------------------------------------------
        |
        | Seluruh status (finish / pending / error)
        | diarahkan kembali ke halaman Membership Customer.
        |
        */

        $finishUrl = route(
            'company.membership',
            [
                'token' => $order->company->access_token,
            ]
        );

        $params = [

            'transaction_details' => [

                'order_id'     => $order->invoice_number,

                'gross_amount' => (int) $order->amount,

            ],

            'customer_details' => [

                'first_name' => $order->company->name,

                'phone'      => $order->company->phone,

            ],

            'item_details' => [

                [

                    'id'       => $order->package,

                    'price'    => (int) $order->amount,

                    'quantity' => 1,

                    'name'     => 'Membership ' . ucfirst($order->package),

                ]

            ],

            'expiry' => [

                'unit'     => 'day',

                'duration' => 2,

            ],

            'language' => 'id',

            /*
            |--------------------------------------------------------------------------
            | Redirect URL
            |--------------------------------------------------------------------------
            */

            'callbacks' => [

                'finish'  => $finishUrl,

                'pending' => $finishUrl,

                'error'   => $finishUrl,

            ],

        ];

        /*
        |--------------------------------------------------------------------------
        | Request Snap
        |--------------------------------------------------------------------------
        */

        $snap = Snap::createTransaction($params);

        /*
        |--------------------------------------------------------------------------
        | Simpan URL Pembayaran
        |--------------------------------------------------------------------------
        */

        $order->update([

            'payment_gateway' => 'MIDTRANS',

            'payment_url'     => $snap->redirect_url,

            'external_id'     => $snap->token,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Simpan Payment Log
        |--------------------------------------------------------------------------
        */

        return Payment::create([

            'membership_order_id' => $order->id,

            'gateway' => 'MIDTRANS',

            'external_id' => $snap->token,

            'reference' => $order->invoice_number,

            'amount' => $order->amount,

            'currency' => 'IDR',

            'status' => 'PENDING',

            'payload' => json_encode($snap),

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Ambil URL Pembayaran
     * --------------------------------------------------------------------------
     */
    public function getPaymentUrl(
        MembershipOrder $order
    ): ?string {

        return $order->payment_url;

    }

    /**
     * --------------------------------------------------------------------------
     * Cancel
     * --------------------------------------------------------------------------
     */
    public function cancel(
        MembershipOrder $order
    ): bool {

        return false;

    }

    /**
     * --------------------------------------------------------------------------
     * Refund
     * --------------------------------------------------------------------------
     */
    public function refund(
        MembershipOrder $order
    ): bool {

        return false;

    }
}