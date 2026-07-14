<?php

namespace App\Services;

use App\Exports\PurchasesExport;
use App\Exports\ReceiptExport;
use App\Models\Company;
use App\Models\PurchaseHeader;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReceiptExportService
{
    /**
     * ==========================================================
     * Export 1 Purchase
     * ==========================================================
     */
    public function exportReceipt(
        Company $company,
        PurchaseHeader $purchase
    ): BinaryFileResponse {

        abort_if(
            $purchase->company_id !== $company->id,
            404
        );

        $purchase->load([
            'supplier',
            'details.item',
            'company',
        ]);

        return Excel::download(
            new ReceiptExport($purchase),
            'purchase-' . $purchase->id . '.xlsx'
        );
    }

    /**
     * ==========================================================
     * Export Semua Purchase Perusahaan
     * ==========================================================
     */
    public function exportPurchases(
        Company $company
    ): BinaryFileResponse {

        return Excel::download(
            new PurchasesExport($company),
            'purchase-' .
            $company->id .
            '.xlsx'
        );
    }
}