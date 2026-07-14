<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\MembershipOrder;
use App\Services\BillingService;
use App\Services\PaymentStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;

class MidtransWebhookController extends Controller
{
    protected BillingService $billing;
    protected PaymentStatusService $paymentStatus;

    public function __construct(
        BillingService $billing,
        PaymentStatusService $paymentStatus
    ) {
        $this->billing = $billing;
        $this->paymentStatus = $paymentStatus;

        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * --------------------------------------------------------------------------
     * Midtrans Webhook
     * --------------------------------------------------------------------------
     */
    public function handle(Request $request)
    {
        Log::info('MIDTRANS WEBHOOK MASUK', [
            'payload' => $request->all(),
        ]);

        try {

            $payload = $request->all();

            /*
            |--------------------------------------------------------------------------
            | Validasi Field Wajib
            |--------------------------------------------------------------------------
            */

            foreach ([
                'order_id',
                'status_code',
                'gross_amount',
                'signature_key',
                'transaction_status',
            ] as $field) {

                if (!isset($payload[$field])) {

                    Log::warning('Payload Midtrans tidak lengkap.', [
                        'missing' => $field,
                    ]);

                    return response()->json([
                        'success' => false,
                    ], 400);

                }

            }

            /*
            |--------------------------------------------------------------------------
            | Signature Verification
            |--------------------------------------------------------------------------
            */

            $signature = hash(
                'sha512',
                $payload['order_id']
                . $payload['status_code']
                . $payload['gross_amount']
                . config('midtrans.server_key')
            );

            if ($signature !== $payload['signature_key']) {

                Log::warning('Signature Midtrans tidak valid.', [

                    'invoice' => $payload['order_id'],

                ]);

                return response()->json([
                    'success' => false,
                ], 403);

            }

            /*
            |--------------------------------------------------------------------------
            | Cari Invoice
            |--------------------------------------------------------------------------
            */

            $order = MembershipOrder::where(
                'invoice_number',
                $payload['order_id']
            )->first();

            if (!$order) {

                Log::warning('Membership Order tidak ditemukan.', [

                    'invoice' => $payload['order_id'],

                ]);

                return response()->json([
                    'success' => false,
                ], 404);

            }

            /*
            |--------------------------------------------------------------------------
            | Sudah selesai?
            |--------------------------------------------------------------------------
            */

            if ($order->status === 'PAID') {

                Log::info('Webhook diabaikan. Invoice sudah PAID.', [

                    'invoice' => $order->invoice_number,

                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Already processed',
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Capture + Fraud
            |--------------------------------------------------------------------------
            */

            $transactionStatus = strtolower(
                $payload['transaction_status']
            );

            $fraudStatus = strtolower(
                $payload['fraud_status'] ?? 'accept'
            );

            if (
                $transactionStatus === 'capture'
                &&
                $fraudStatus !== 'accept'
            ) {

                Log::info('Capture menunggu Fraud Approval.', [

                    'invoice' => $order->invoice_number,

                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Waiting Fraud Approval',
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Update Status Pembayaran
            |--------------------------------------------------------------------------
            */

            $result = $this->paymentStatus->process(

                $order,
                $transactionStatus,
                $payload

            );

            /*
            |--------------------------------------------------------------------------
            | Refresh Order
            |--------------------------------------------------------------------------
            */

            $order->refresh();

            /*
            |--------------------------------------------------------------------------
            | Aktivasi Membership
            |--------------------------------------------------------------------------
            */

            if ($result['activate_membership']) {

                $this->billing->activateMembership($order);

                Log::info('Membership berhasil diaktifkan.', [

                    'invoice' => $order->invoice_number,
                    'company' => $order->company_id,
                    'package' => $order->package,

                ]);

            }

            Log::info('Webhook selesai diproses.', [

                'invoice' => $order->invoice_number,
                'status' => $order->status,

            ]);

            return response()->json([
                'success' => true,
            ]);

        } catch (\Throwable $e) {

            Log::error('MIDTRANS WEBHOOK ERROR', [

                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),

            ]);

            return response()->json([
                'success' => false,
            ], 500);

        }
    }
}