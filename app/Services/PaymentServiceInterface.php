<?php

namespace App\Services;

use App\Models\MembershipOrder;
use App\Models\Payment;

interface PaymentServiceInterface
{
    /**
     * --------------------------------------------------------------------------
     * Membuat transaksi pembayaran
     * --------------------------------------------------------------------------
     *
     * Mengirim order ke payment gateway kemudian mengembalikan
     * data Payment yang telah disimpan ke database.
     */
    public function createPayment(
        MembershipOrder $order
    ): Payment;

    /**
     * --------------------------------------------------------------------------
     * Mengambil URL pembayaran
     * --------------------------------------------------------------------------
     *
     * Digunakan untuk mengarahkan pelanggan ke halaman pembayaran
     * apabila payment gateway menggunakan metode redirect.
     */
    public function getPaymentUrl(
        MembershipOrder $order
    ): ?string;

    /**
     * --------------------------------------------------------------------------
     * Membatalkan transaksi pembayaran
     * --------------------------------------------------------------------------
     *
     * Mengembalikan TRUE apabila pembatalan berhasil.
     */
    public function cancel(
        MembershipOrder $order
    ): bool;

    /**
     * --------------------------------------------------------------------------
     * Refund pembayaran
     * --------------------------------------------------------------------------
     *
     * Mengembalikan TRUE apabila proses refund berhasil.
     */
    public function refund(
        MembershipOrder $order
    ): bool;
}