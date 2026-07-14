<?php

namespace App\Support;

class WhatsAppMessages
{
    /**
     * ==========================================================================
     * Welcome
     * ==========================================================================
     */

    public static function welcome(): string
    {
        return
            "Halo 👋\n\n" .
            "Silakan kirim foto nota.\n" .
            "Saya akan membantu membaca isi nota menggunakan AI.";
    }

    /**
     * ==========================================================================
     * Receipt
     * ==========================================================================
     */

    public static function receiptReceived(): string
    {
        return
            "📸 Foto berhasil diterima.\n\n" .
            "⏳ Mohon tunggu beberapa detik...";
    }

    /**
     * OCR Berhasil
     */
    public static function receiptSuccess(
        string $supplier,
        string $date,
        string $total,
        int $items
    ): string {

        return
            "✅ *Nota berhasil diproses*\n\n" .

            "🏪 Supplier : {$supplier}\n" .
            "📅 Tanggal  : {$date}\n" .
            "💰 Total    : {$total}\n" .
            "📦 Item     : {$items}";
            
            
    }

    /**
     * OCR Gagal
     */
    public static function receiptFailed(
        ?string $reason = null
    ): string {

        $message =
            "❌ Maaf, nota tidak berhasil diproses.";

        if (!empty($reason)) {
            $message .= "\n\n{$reason}";
        }

        return $message;
    }

    /**
     * ==========================================================================
     * Membership
     * ==========================================================================
     */

    public static function quotaExceeded(
        array $membership,
        ?string $nextPackage = null
    ): string {

        $message =
            "⚠️ Kuota upload nota bulan ini telah habis.\n\n" .

            "📦 Paket Anda\n" .
            "{$membership['name']}\n\n" .

            "📊 Penggunaan\n" .
            number_format($membership['used']) .
            " / " .
            number_format($membership['quota']) .
            " nota";

        if ($nextPackage) {

            $message .=

                "\n\n━━━━━━━━━━━━━━━━━━━━\n\n" .

                "Upgrade ke {$nextPackage} dan nikmati:\n\n" .

                "✅ Kuota upload lebih besar\n" .
                "✅ Export PDF\n" .
                "✅ Business AI";
        }

        return $message;
    }

    /**
     * ==========================================================================
     * CTA - Membership
     * ==========================================================================
     */

    public static function membershipButton(): string
    {
        return "🚀 Upgrade";
    }

    public static function membershipHeader(): string
    {
        return "Upgrade Membership";
    }

    public static function membershipFooter(): string
    {
        return "Notakita";
    }

    /**
     * ==========================================================================
     * CTA - Future
     * ==========================================================================
     */

    public static function salesButton(): string
    {
        return "🧾 Upload Nota Penjualan";
    }

    public static function advisorButton(): string
    {
        return "🤖 AI Business Advisor";
    }

    public static function reportButton(): string
    {
        return "📊 Lihat Laporan";
    }

    public static function paymentButton(): string
    {
        return "💳 Lanjutkan Pembayaran";
    }

    /**
     * ==========================================================================
     * Payment
     * ==========================================================================
     */

    public static function paymentSuccess(
        string $package
    ): string {

        return
            "🎉 Pembayaran berhasil.\n\n" .
            "Membership {$package} telah aktif.\n\n" .
            "Selamat menggunakan Notakita.";
    }

    public static function paymentPending(): string
    {
        return
            "⏳ Pembayaran Anda masih menunggu konfirmasi.";
    }

    public static function paymentExpired(): string
    {
        return
            "⌛ Invoice telah kedaluwarsa.\n\n" .
            "Silakan lakukan pemesanan kembali.";
    }

    /**
     * ==========================================================================
     * General
     * ==========================================================================
     */

    public static function processing(): string
    {
        return
            "⏳ Permintaan sedang diproses...";
    }

    public static function maintenance(): string
    {
        return
            "🛠️ Sistem sedang dalam pemeliharaan.\n\n" .
            "Silakan coba kembali beberapa saat lagi.";
    }

    public static function previousReceiptProcessing(): string
    {
        return
            "⏳ Nota sebelumnya masih sedang diproses.\n\n" .
            "Mohon tunggu hingga proses selesai.";
    }
}