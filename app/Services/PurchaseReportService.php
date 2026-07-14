<?php

namespace App\Services;

use App\Models\PurchaseHeader;
use Illuminate\Database\Eloquent\Collection;

class PurchaseReportService
{
    /**
     * Ambil seluruh pembelian.
     */
    public function all(): Collection
    {
        return PurchaseHeader::with([
            'supplier',
            'details.item',
        ])
        ->latest('invoice_date')
        ->latest('id')
        ->get();
    }

    /**
     * Ambil satu pembelian berdasarkan ID.
     */
    public function find(int $id): PurchaseHeader
    {
        return PurchaseHeader::with([
            'supplier',
            'details.item',
        ])->findOrFail($id);
    }

    /**
     * Laporan harian.
     */
    public function daily(string $date): Collection
    {
        return PurchaseHeader::with([
            'supplier',
            'details.item',
        ])
        ->whereDate('invoice_date', $date)
        ->orderBy('invoice_date')
        ->get();
    }

    /**
     * Laporan bulanan.
     */
    public function monthly(int $month, int $year): Collection
    {
        return PurchaseHeader::with([
            'supplier',
            'details.item',
        ])
        ->whereMonth('invoice_date', $month)
        ->whereYear('invoice_date', $year)
        ->orderBy('invoice_date')
        ->get();
    }

    /**
     * Berdasarkan supplier.
     */
    public function supplier(int $supplierId): Collection
    {
        return PurchaseHeader::with([
            'supplier',
            'details.item',
        ])
        ->where('supplier_id', $supplierId)
        ->latest('invoice_date')
        ->get();
    }
}