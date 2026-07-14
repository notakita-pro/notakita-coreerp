<?php

namespace App\Services;

use App\Models\Company;
use App\Models\PurchaseDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * ==========================================================
     * Seluruh Detail Purchase
     * ==========================================================
     */
    public function all(
        Company $company
    ): Collection {

        return PurchaseDetail::query()
            ->where(
                'purchase_details.company_id',
                $company->id
            )
            ->with([
                'purchase.supplier',
                'item',
            ])
            ->orderBy('purchase_header_id')
            ->orderBy('id')
            ->get();
    }

    /**
     * ==========================================================
     * Detail Purchase Berdasarkan Periode
     * ==========================================================
     */
    public function period(
        Company $company,
        string|Carbon $from,
        string|Carbon $to
    ): Collection {

        $from = Carbon::parse($from)
            ->startOfDay();

        $to = Carbon::parse($to)
            ->endOfDay();

        return PurchaseDetail::query()

            ->join(
                'purchase_headers',
                'purchase_headers.id',
                '=',
                'purchase_details.purchase_header_id'
            )

            ->where(
                'purchase_details.company_id',
                $company->id
            )

            ->whereBetween(
                'purchase_headers.invoice_date',
                [$from, $to]
            )

            ->with([
                'purchase.supplier',
                'item',
            ])

            ->select('purchase_details.*')

            ->orderBy('purchase_headers.invoice_date')
            ->orderBy('purchase_headers.invoice_number')
            ->orderBy('purchase_details.id')

            ->get();
    }

    /**
     * ==========================================================
     * Hari Ini
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
     * Summary
     * ==========================================================
     */
    public function summary(
        Collection $rows
    ): array {

        return [

            'transactions' => $rows
                ->pluck('purchase.id')
                ->filter()
                ->unique()
                ->count(),

            'suppliers' => $rows
                ->pluck('purchase.supplier_id')
                ->filter()
                ->unique()
                ->count(),

            'items' => $rows->count(),

            'total' => (float) $rows
                ->sum('total_price'),

        ];
    }
}