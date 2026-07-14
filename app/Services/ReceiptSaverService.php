<?php

namespace App\Services;

use App\DTO\ReceiptData;
use App\Models\Company;
use App\Models\Item;
use App\Models\OcrLog;
use App\Models\PurchaseDetail;
use App\Models\PurchaseHeader;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class ReceiptSaverService
{
    public function __construct(
        protected QuotaService $quotaService
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Simpan hasil OCR ke Database
     * --------------------------------------------------------------------------
     */
    public function save(
        Company $company,
        ReceiptData $receipt,
        ?string $imageFile = null
    ): PurchaseHeader {

        return DB::transaction(function () use (
            $company,
            $receipt,
            $imageFile
        ) {

            /*
            |--------------------------------------------------------------------------
            | Supplier
            |--------------------------------------------------------------------------
            */

            $supplier = $this->resolveSupplier($receipt);

            /*
            |--------------------------------------------------------------------------
            | Purchase Header
            |--------------------------------------------------------------------------
            */

            $header = $this->createHeader(
                $company,
                $supplier,
                $receipt,
                $imageFile
            );

            /*
            |--------------------------------------------------------------------------
            | Purchase Detail
            |--------------------------------------------------------------------------
            */

            $this->createDetails(
                $company,
                $header,
                $receipt
            );

            /*
            |--------------------------------------------------------------------------
            | OCR Log
            |--------------------------------------------------------------------------
            */

            $this->createOcrLog(
                $company,
                $receipt,
                $imageFile
            );

            /*
            |--------------------------------------------------------------------------
            | Consume Membership Quota
            |--------------------------------------------------------------------------
            */

            $this->quotaService->consume($company);

            return $header;
        });
    }

    /**
     * --------------------------------------------------------------------------
     * Supplier
     * --------------------------------------------------------------------------
     */
    protected function resolveSupplier(
        ReceiptData $receipt
    ): Supplier {

        return Supplier::firstOrCreate([
            'name' => $receipt->supplier ?: 'UNKNOWN',
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Purchase Header
     * --------------------------------------------------------------------------
     */
    protected function createHeader(
        Company $company,
        Supplier $supplier,
        ReceiptData $receipt,
        ?string $imageFile
    ): PurchaseHeader {

        return PurchaseHeader::create([

            'company_id'     => $company->id,

            'supplier_id'    => $supplier->id,

            'invoice_number' => $receipt->invoice_number,

            'invoice_date'   => $receipt->date,

            'subtotal'       => $this->number($receipt->subtotal),

            'tax'            => $this->number($receipt->tax),

            'total'          => $this->number($receipt->total),

            'image_file'     => $this->imageName($imageFile),

            'raw_json'       => $this->receiptJson($receipt),

            'source'         => 'whatsapp',

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Purchase Detail
     * --------------------------------------------------------------------------
     */
    protected function createDetails(
        Company $company,
        PurchaseHeader $header,
        ReceiptData $receipt
    ): void {

        foreach ($receipt->items as $row) {

            if (blank($row['name'] ?? null)) {
                continue;
            }

            $item = Item::firstOrCreate([
                'name' => trim($row['name']),
            ]);

            $qty = $this->number(
                $row['qty'] ?? null
            ) ?? 1;

            $unitPrice = $this->number(
                $row['unit_price'] ?? null
            ) ?? 0;

            $totalPrice = $this->number(
                $row['total'] ?? null
            );

            if ($totalPrice === null) {
                $totalPrice = $qty * $unitPrice;
            }

            PurchaseDetail::create([

                'company_id' => $company->id,

                'purchase_header_id' => $header->id,

                'item_id' => $item->id,

                'qty' => $qty,

                'unit_price' => $unitPrice,

                'total_price' => $totalPrice,

            ]);
        }
    }
        /**
     * --------------------------------------------------------------------------
     * OCR Log
     * --------------------------------------------------------------------------
     */
    protected function createOcrLog(
        Company $company,
        ReceiptData $receipt,
        ?string $imageFile
    ): void {

        OcrLog::create([

            'company_id' => $company->id,

            'image_file' => $this->imageName($imageFile),

            'raw_json' => $this->receiptJson($receipt),

            'status' => 'received',

            'source' => 'whatsapp',

            'error_message' => null,

        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Nama file gambar
     * --------------------------------------------------------------------------
     */
    protected function imageName(?string $imageFile): ?string
    {
        return $imageFile
            ? basename($imageFile)
            : null;
    }

    /**
     * --------------------------------------------------------------------------
     * Receipt JSON
     * --------------------------------------------------------------------------
     */
    protected function receiptJson(
        ReceiptData $receipt
    ): string {

        return json_encode(
            $receipt->toArray(),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Konversi Nilai Numerik
     * --------------------------------------------------------------------------
     */
    protected function number(
        mixed $value
    ): ?float {

        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }
}