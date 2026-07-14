<?php

namespace App\Exports;

use App\Models\PurchaseHeader;
use Maatwebsite\Excel\Concerns\FromArray;

class ReceiptExport implements FromArray
{
    protected PurchaseHeader $purchase;

    public function __construct(PurchaseHeader $purchase)
    {
        $this->purchase = $purchase->load([
            'supplier',
            'details.item'
        ]);
    }

    public function array(): array
    {
        $rows = [];

        /*
        |--------------------------------------------------------------------------
        | Judul
        |--------------------------------------------------------------------------
        */

        $rows[] = ['LAPORAN PEMBELIAN'];
        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | Header Nota
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'Supplier',
            $this->purchase->supplier?->name ?? '-'
        ];

        $rows[] = [
            'Invoice',
            $this->purchase->invoice_number ?? '-'
        ];

        $rows[] = [
            'Tanggal',
            optional($this->purchase->invoice_date)->format('d-m-Y')
        ];

        $rows[] = [
            'Jumlah Item',
            $this->purchase->details->count()
        ];

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | Detail Barang
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'No',
            'Nama Barang',
            'Qty',
            'Unit',
            'Harga Satuan',
            'Total'
        ];

        $no = 1;

        foreach ($this->purchase->details as $detail) {

            $rows[] = [

                $no++,

                $detail->item?->name,

                $detail->qty,

                $detail->item?->unit,

                $detail->unit_price,

                $detail->total_price,

            ];
        }

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | Ringkasan
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            '',
            '',
            '',
            '',
            'Subtotal',
            $this->purchase->subtotal
        ];

        $rows[] = [
            '',
            '',
            '',
            '',
            'PPN',
            $this->purchase->tax
        ];

        $rows[] = [
            '',
            '',
            '',
            '',
            'TOTAL',
            $this->purchase->total
        ];

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | Informasi OCR
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'File Nota',
            $this->purchase->image_file
        ];

        $rows[] = [
            'Sumber',
            $this->purchase->source
        ];

        return $rows;
    }
}