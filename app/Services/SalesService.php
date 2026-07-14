<?php

namespace App\Services;

use App\Models\Company;
use App\Models\SalesDetail;
use App\Models\SalesHeader;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SalesService
{
    /**
     * ==========================================================
     * Seluruh Detail Penjualan
     * ==========================================================
     */
    public function all(
        Company $company
    ): Collection {

        return SalesDetail::query()

            ->where(
                'sales_details.company_id',
                $company->id
            )

            ->with([
                'header.customer',
            ])

            ->orderBy('sales_header_id')
            ->orderBy('id')

            ->get();
    }

    /**
     * ==========================================================
     * Detail Penjualan Berdasarkan Periode
     * ==========================================================
     */
    public function period(
        Company $company,
        string|Carbon $from,
        string|Carbon $to
    ): Collection {

        $from = Carbon::parse($from)->startOfDay();
        $to   = Carbon::parse($to)->endOfDay();

        return SalesDetail::query()

            ->join(
                'sales_headers',
                'sales_headers.id',
                '=',
                'sales_details.sales_header_id'
            )

            ->where(
                'sales_details.company_id',
                $company->id
            )

            ->whereBetween(
                'sales_headers.invoice_date',
                [$from, $to]
            )

            ->with([
                'header.customer',
            ])

            ->select('sales_details.*')

            ->orderBy('sales_headers.invoice_date')
            ->orderBy('sales_headers.invoice_number')
            ->orderBy('sales_details.id')

            ->get();
    }

    /**
     * ==========================================================
     * Penjualan Hari Ini
     * ==========================================================
     */
    public function today(
        Company $company
    ): Collection {

        return $this->period(
            $company,
            now(),
            now()
        );
    }

    /**
     * ==========================================================
     * Ringkasan Penjualan
     * ==========================================================
     */
    public function summary(
        Collection $rows
    ): array {

        $headers = $rows
            ->pluck('header')
            ->filter()
            ->unique('id');

        return [

            /*
            |--------------------------------------------------------------------------
            | Jumlah Transaksi
            |--------------------------------------------------------------------------
            */
            'transactions' => $headers->count(),

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */
            'customers' => $headers
                ->pluck('customer_id')
                ->filter()
                ->unique()
                ->count(),

            /*
            |--------------------------------------------------------------------------
            | Jumlah Baris Item
            |--------------------------------------------------------------------------
            */
            'items' => $rows->count(),

            /*
            |--------------------------------------------------------------------------
            | Total Qty
            |--------------------------------------------------------------------------
            */
            'qty' => (float) $rows->sum('qty'),

            /*
            |--------------------------------------------------------------------------
            | Subtotal Barang
            |--------------------------------------------------------------------------
            */
            'subtotal' => (float) $rows->sum('total_price'),

            /*
            |--------------------------------------------------------------------------
            | Grand Total Penjualan
            |--------------------------------------------------------------------------
            */
            'sales' => (float) $headers->sum('grand_total'),

            /*
            |--------------------------------------------------------------------------
            | Total Piutang
            |--------------------------------------------------------------------------
            */
            'receivable' => (float) $headers->sum('balance_due'),

            /*
            |--------------------------------------------------------------------------
            | Total Pembayaran
            |--------------------------------------------------------------------------
            */
            'paid' => (float) $headers->sum('paid_amount'),

        ];
    }
        /**
     * ==========================================================
     * Simpan Penjualan
     * ==========================================================
     */
    public function store(
        Company $company,
        array $data
    ): SalesHeader {

        return DB::transaction(function () use ($company, $data) {

            /*
            |--------------------------------------------------------------------------
            | Header
            |--------------------------------------------------------------------------
            */

            $header = SalesHeader::create([

                'company_id'      => $company->id,

                'customer_id'     => $data['customer_id'] ?? null,

                'invoice_number'  => $data['invoice_number']
                    ?? $this->generateInvoiceNumber($company),

                'invoice_date'    => $data['invoice_date'],

                'due_date'        => $data['due_date'] ?? null,

                'subtotal'        => 0,

                'discount'        => (float) ($data['discount'] ?? 0),
                'tax'             => (float) ($data['tax'] ?? 0),
                'transport'       => (float) ($data['transport'] ?? 0),
                'other_cost'      => (float) ($data['other_cost'] ?? 0),

                'grand_total'     => 0,

                'payment_term'    => $data['payment_term'] ?? 'cash',

                'payment_method'  => $data['payment_method'] ?? null,

                'down_payment'    => (float) ($data['down_payment'] ?? 0),

                'paid_amount'     => 0,

                'balance_due'     => 0,

                'payment_status'  => 'unpaid',

                'notes'           => $data['notes'] ?? null,

                'created_by'      => auth()->id(),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Detail
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            foreach ($data['items'] ?? [] as $item) {

                $qty        = (float) ($item['qty'] ?? 0);

                $unitPrice  = (float) ($item['unit_price'] ?? 0);

                $costPrice  = (float) ($item['cost_price'] ?? 0);

                $discount   = (float) ($item['discount'] ?? 0);

                $tax        = (float) ($item['tax'] ?? 0);

                /*
                |--------------------------------------------------------------------------
                | Jumlah = Qty × Harga
                |--------------------------------------------------------------------------
                */

                $totalPrice = $this->calculateItemTotal(
    $qty,
    $unitPrice
);

                SalesDetail::create([

                    'company_id'      => $company->id,

                    'sales_header_id' => $header->id,

                    'item_id'         => $item['item_id'] ?? null,

                    'item_name'       => trim($item['item_name'] ?? ''),

                    'unit'            => trim($item['unit'] ?? ''),

                    'qty'             => $qty,

                    'unit_price'      => $unitPrice,

                    'cost_price'      => $costPrice,

                    'discount'        => $discount,

                    'tax'             => $tax,

                    'total_price'     => $totalPrice,

                    'notes'           => $item['notes'] ?? null,

                ]);

                /*
                |--------------------------------------------------------------------------
                | Subtotal Barang
                |--------------------------------------------------------------------------
                */

                $subtotal += $totalPrice;
            }

            /*
            |--------------------------------------------------------------------------
            | Grand Total
            |--------------------------------------------------------------------------
            */
$grandTotal = $this->calculateGrandTotal(
    $subtotal,
    $header->discount,
    $header->tax,
    $header->transport,
    $header->other_cost
);

            /*
            |--------------------------------------------------------------------------
            | Pembayaran
            |--------------------------------------------------------------------------
            */

            $paidAmount =
                (float) $header->down_payment;

$balanceDue = $this->calculateBalanceDue(
    $grandTotal,
    $paidAmount
);

            /*
            |--------------------------------------------------------------------------
            | Status Pembayaran
            |--------------------------------------------------------------------------
            */

$paymentStatus = $this->calculatePaymentStatus(
    $grandTotal,
    $paidAmount
);

            /*
            |--------------------------------------------------------------------------
            | Update Header
            |--------------------------------------------------------------------------
            */

            $header->update([

                'subtotal'       => $subtotal,

                'grand_total'    => $grandTotal,

                'paid_amount'    => $paidAmount,

                'balance_due'    => $balanceDue,

                'payment_status' => $paymentStatus,

            ]);

            return $header->fresh();

        });
    }
        /**
     * ==========================================================
     * Update Penjualan
     * ==========================================================
     */
    public function update(
        SalesHeader $header,
        array $data
    ): SalesHeader {

        return DB::transaction(function () use ($header, $data) {

            /*
            |--------------------------------------------------------------------------
            | Update Header
            |--------------------------------------------------------------------------
            */

            $header->update([

                'customer_id'     => $data['customer_id'] ?? null,

                'invoice_date'    => $data['invoice_date'],

                'due_date'        => $data['due_date'] ?? null,

                'discount'        => (float) ($data['discount'] ?? 0),

                'tax'             => (float) ($data['tax'] ?? 0),

                'transport'       => (float) ($data['transport'] ?? 0),

                'other_cost'      => (float) ($data['other_cost'] ?? 0),

                'payment_term'    => $data['payment_term'] ?? 'cash',

                'payment_method'  => $data['payment_method'] ?? null,

                'notes'           => $data['notes'] ?? null,

                'updated_by'      => auth()->id(),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Hitung Ulang Subtotal
            |--------------------------------------------------------------------------
            */

       $subtotal = $header
    ->details
    ->sum('total_price');

            /*
            |--------------------------------------------------------------------------
            | Grand Total
            |--------------------------------------------------------------------------
            */

           $grandTotal = $this->calculateGrandTotal(
    $subtotal,
    $header->discount,
    $header->tax,
    $header->transport,
    $header->other_cost
);

            /*
            |--------------------------------------------------------------------------
            | Paid Amount
            |--------------------------------------------------------------------------
            */

            $paidAmount = (float) $header->paid_amount;

            /*
            |--------------------------------------------------------------------------
            | Balance Due
            |--------------------------------------------------------------------------
            */

$balanceDue = $this->calculateBalanceDue(
    $grandTotal,
    $paidAmount
);

            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

$paymentStatus = $this->calculatePaymentStatus(
    $grandTotal,
    $paidAmount
);

            /*
            |--------------------------------------------------------------------------
            | Update Total
            |--------------------------------------------------------------------------
            */

            $header->update([

                'subtotal'       => $subtotal,

                'grand_total'    => $grandTotal,

                'balance_due'    => $balanceDue,

                'payment_status' => $paymentStatus,

            ]);

            return $header->fresh([
                'customer',
                'details',
            ]);

        });
    }

    /**
     * ==========================================================
     * Hitung Grand Total
     * ==========================================================
     */
    private function calculateGrandTotal(
        float $subtotal,
        float $discount = 0,
        float $tax = 0,
        float $transport = 0,
        float $otherCost = 0
    ): float {

        return
            $subtotal
            + $tax
            + $transport
            + $otherCost
            - $discount;
    }

    /**
     * ==========================================================
     * Hitung Sisa Tagihan
     * ==========================================================
     */
    private function calculateBalanceDue(
        float $grandTotal,
        float $paidAmount
    ): float {

        return max(
            0,
            $grandTotal - $paidAmount
        );
    }

    /**
     * ==========================================================
     * Hitung Status Pembayaran
     * ==========================================================
     */
    private function calculatePaymentStatus(
        float $grandTotal,
        float $paidAmount
    ): string {

        if ($paidAmount <= 0) {
            return 'unpaid';
        }

        if ($paidAmount < $grandTotal) {
            return 'partial';
        }

        return 'paid';
    }
    
    
    /**
 * ==========================================================
 * Hitung Jumlah per Item
 * ==========================================================
 */
private function calculateItemTotal(
    float $qty,
    float $unitPrice
): float {

    return $qty * $unitPrice;
}


    /**
     * ==========================================================
     * Generate Nomor Invoice
     * ==========================================================
     */
    private function generateInvoiceNumber(
        Company $company
    ): string {

        $prefix = 'SLS-' . now()->format('Ymd');

        $last = SalesHeader::query()

            ->where(
                'company_id',
                $company->id
            )

            ->whereDate(
                'invoice_date',
                today()
            )

            ->latest('id')

            ->first();

        $next = 1;

        if (
            $last &&
            preg_match('/(\d+)$/', $last->invoice_number, $match)
        ) {
            $next = ((int) $match[1]) + 1;
        }

        return sprintf(
            '%s-%04d',
            $prefix,
            $next
        );
    }
}