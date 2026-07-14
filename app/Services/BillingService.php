<?php

namespace App\Services;

use App\Models\Company;
use App\Models\MembershipOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BillingService
{
    public function __construct(
        protected PaymentServiceInterface $payment
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Ambil Konfigurasi Paket
     * --------------------------------------------------------------------------
     */
    public function package(string $package): array
    {
        $package = strtolower($package);

        $config = config("membership.$package");

        if (!$config) {
            throw new InvalidArgumentException(
                "Membership package [$package] tidak ditemukan."
            );
        }

        return $config;
    }

    /**
     * --------------------------------------------------------------------------
     * Membuat Membership Order
     * --------------------------------------------------------------------------
     */
    public function createMembershipOrder(
        Company $company,
        string $package,
        ?float $amount = null
    ): MembershipOrder {

        $package = strtolower($package);

        $config = $this->package($package);

        $amount ??= $config['price'];

        return DB::transaction(function () use (
            $company,
            $package,
            $amount
        ) {

            /*
            |--------------------------------------------------------------------------
            | Gunakan invoice pending jika masih aktif
            |--------------------------------------------------------------------------
            */

            $pendingOrder = MembershipOrder::query()

                ->where('company_id', $company->id)

                ->where('package', $package)

                ->where('status', 'PENDING')

                ->where('expires_at', '>', now())

                ->latest()

                ->first();

            if ($pendingOrder) {

                Log::info(
                    'Menggunakan Membership Order yang masih Pending.',
                    [
                        'invoice'    => $pendingOrder->invoice_number,
                        'company_id' => $company->id,
                        'package'    => $package,
                    ]
                );

                return $pendingOrder;
            }

            /*
            |--------------------------------------------------------------------------
            | Membuat Invoice Baru
            |--------------------------------------------------------------------------
            */

            $order = MembershipOrder::create([

                'company_id'     => $company->id,

                'invoice_number' => $this->generateInvoiceNumber(),

                'package'        => $package,

                'amount'         => $amount,

                'currency'       => 'IDR',

                'status'         => 'PENDING',

                'expires_at'     => now()->addDays(2),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Membuat Payment Gateway
            |--------------------------------------------------------------------------
            */

            $this->payment->createPayment($order);

            $order->refresh();

            Log::info(
                'Membership Order berhasil dibuat.',
                [

                    'invoice'    => $order->invoice_number,

                    'company_id' => $company->id,

                    'package'    => $package,

                    'amount'     => $amount,

                ]
            );

            return $order;

        });

    }

    /**
     * --------------------------------------------------------------------------
     * Generate Nomor Invoice
     * --------------------------------------------------------------------------
     */
    protected function generateInvoiceNumber(): string
    {
        return 'INV-'
            . now()->format('YmdHis')
            . '-'
            . strtoupper(Str::random(5));
    }

    /**
     * --------------------------------------------------------------------------
     * Aktivasi Membership
     * --------------------------------------------------------------------------
     */
    public function activateMembership(
        MembershipOrder $order
    ): void {

        DB::transaction(function () use ($order) {

            $company = $order->company;

            if (! $company) {
                throw new \RuntimeException(
                    'Company tidak ditemukan.'
                );
            }

            $config = $this->package(
                $order->package
            );

            $company->update([

                'membership_type' => strtolower(
                    $order->package
                ),

                'membership_expires_at' => now()->addDays(
                    $config['duration']
                ),

                'used_quota' => 0,

            ]);

            $order->update([

                'status'  => 'PAID',

                'paid_at' => now(),

            ]);

            Log::info(
                'Membership berhasil diaktifkan.',
                [

                    'company_id' => $company->id,

                    'invoice' => $order->invoice_number,

                    'package' => $order->package,

                ]
            );

        });

    }

    /**
     * --------------------------------------------------------------------------
     * Membership Aktif
     * --------------------------------------------------------------------------
     */
    public function isActive(
        Company $company
    ): bool {

        return

            ! empty($company->membership_type)

            &&

            $company->membership_expires_at

            &&

            $company->membership_expires_at->isFuture();

    }

    /**
     * --------------------------------------------------------------------------
     * Paket Aktif
     * --------------------------------------------------------------------------
     */
    public function currentPackage(
        Company $company
    ): string {

        if (! $this->isActive($company)) {
            return 'free';
        }

        return strtolower(
            $company->membership_type
        );

    }

    /**
     * --------------------------------------------------------------------------
     * Cek Hak Akses Fitur
     * --------------------------------------------------------------------------
     */
    public function hasFeature(
        Company $company,
        string $feature
    ): bool {

        $package = $this->currentPackage(
            $company
        );

        return (bool) config(
            "membership.$package.$feature",
            false
        );

    }

    /**
     * --------------------------------------------------------------------------
     * Ambil URL Pembayaran
     * --------------------------------------------------------------------------
     */
    public function paymentUrl(
        MembershipOrder $order
    ): ?string {

        return $order->payment_url;

    }

    /**
     * --------------------------------------------------------------------------
     * Invoice Pending Terakhir
     * --------------------------------------------------------------------------
     */
    public function pendingOrder(
        Company $company
    ): ?MembershipOrder {

        return MembershipOrder::query()

            ->where('company_id', $company->id)

            ->where('status', 'PENDING')

            ->where('expires_at', '>', now())

            ->latest()

            ->first();

    }
}