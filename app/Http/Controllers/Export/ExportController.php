<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\PurchaseHeader;
use App\Services\ReceiptExportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __construct(
        protected ReceiptExportService $exportService
    ) {
    }

    /**
     * ==========================================================
     * Export 1 Purchase -> Excel
     * ==========================================================
     */
    public function receipt(
        PurchaseHeader $purchase
    ): BinaryFileResponse {
        return $this->exportService
            ->exportReceipt($purchase);
    }

    /**
     * ==========================================================
     * Export Seluruh Purchase
     * (Admin Only)
     * ==========================================================
     */
    public function purchases(): BinaryFileResponse
    {
        return $this->exportService
            ->exportAllPurchases();
    }

    /**
     * ==========================================================
     * Export 1 Purchase -> PDF
     * ==========================================================
     */
    public function pdf(
        PurchaseHeader $purchase
    ): BinaryFileResponse {
        return $this->exportService
            ->exportPdf($purchase);
    }
}