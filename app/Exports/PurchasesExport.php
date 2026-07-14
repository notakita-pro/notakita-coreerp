<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\PurchaseHeader;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesExport implements FromArray, WithHeadings
{
    protected Company $company;

    public function __construct(
        Company $company
    ) {
        $this->company = $company;
    }

    public function headings(): array
    {
        return [

            'ID',

            'Supplier',

            'Invoice',

            'Tanggal',

            'Subtotal',

            'PPN',

            'Total',

            'Jumlah Item',

            'Sumber',

            'Foto',

        ];
    }

    public function array(): array
    {
        $rows = [];

        $purchases = PurchaseHeader::with([
                'supplier',
                'details',
            ])
            ->where(
                'company_id',
                $this->company->id
            )
            ->orderByDesc('invoice_date')
            ->get();

        foreach ($purchases as $purchase) {

            $rows[] = [

                $purchase->id,

                $purchase->supplier?->name,

                $purchase->invoice_number,

                optional(
                    $purchase->invoice_date
                )->format('Y-m-d'),

                $purchase->subtotal,

                $purchase->tax,

                $purchase->total,

                $purchase->details->count(),

                $purchase->source,

                $purchase->image_file,

            ];
        }

        return $rows;
    }
}