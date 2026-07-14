<?php

namespace App\Http\Controllers;

use App\Models\MembershipOrder;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * ==========================================================================
     * PAYMENT CONTROLLER
     * ==========================================================================
     *
     * Seluruh interaksi Customer terhadap pembayaran.
     *
     * CATATAN PENTING
     * --------------------------------------------------------------------------
     * Controller ini TIDAK menentukan status pembayaran.
     *
     * Status pembayaran hanya boleh diubah oleh:
     * - Webhook Midtrans
     * - Billing Service
     *
     * Sehingga tidak ada kemungkinan customer memanipulasi
     * status pembayaran melalui browser.
     * ==========================================================================
     */

    /**
     * --------------------------------------------------------------------------
     * Finish Redirect
     * --------------------------------------------------------------------------
     *
     * Redirect dari Midtrans setelah customer selesai melakukan
     * proses pembayaran.
     *
     * Midtrans akan mengirim parameter:
     * order_id = invoice_number
     *
     * Halaman ini hanya menampilkan proses verifikasi dan
     * melakukan polling ke PaymentStatusController.
     */
    public function finish(Request $request)
    {
        $invoice = $request->string('order_id')->toString();

        abort_if(blank($invoice), 404);

        $order = MembershipOrder::with('company')
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        return view(
            'payment.finish',
            compact('order')
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Detail Invoice
     * --------------------------------------------------------------------------
     *
     * Menampilkan detail invoice kepada customer.
     *
     * Route Model Binding menggunakan invoice_number.
     */
    public function invoice(MembershipOrder $order)
    {
        $order->load([
            'company',
            'payments',
        ]);

        return view(
            'payment.invoice',
            compact('order')
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Retry Payment
     * --------------------------------------------------------------------------
     *
     * Digunakan apabila invoice masih berstatus PENDING.
     */
    public function retry(MembershipOrder $order)
    {
        abort_unless(
            $order->isPending(),
            403,
            'Invoice tidak dapat dibayar ulang.'
        );

        abort_if(
            blank($order->payment_url),
            404,
            'URL pembayaran tidak tersedia.'
        );

        return redirect()->away(
            $order->payment_url
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Cancel Invoice
     * --------------------------------------------------------------------------
     *
     * Saat ini hanya membatalkan invoice di sisi aplikasi.
     *
     * Ke depan method ini dapat diperluas untuk memanggil
     * API Cancel dari Payment Gateway.
     */
    public function cancel(MembershipOrder $order)
    {
        abort_unless(
            $order->isPending(),
            403,
            'Invoice tidak dapat dibatalkan.'
        );

        $order->markCancelled();

        return redirect()
            ->route(
                'payment.invoice',
                $order
            )
            ->with(
                'success',
                'Invoice berhasil dibatalkan.'
            );
    }
}