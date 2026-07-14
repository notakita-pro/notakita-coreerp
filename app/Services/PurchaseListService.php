<?php

namespace App\Services;

use App\Models\Company;
use App\Models\PurchaseHeader;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PurchaseListService
{
    /**
     * Query dasar Purchase beserta relasi yang selalu dipakai.
     */
    protected function query()
    {
        return PurchaseHeader::with([
            'company',
            'supplier',
            'details.item',
        ]);
    }

    /**
     * Daftar Purchase seluruh perusahaan (Admin).
     */
    public function paginateAll(
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->query()
            ->latest('invoice_date')
            ->paginate($perPage);
    }

    /**
     * Daftar Purchase milik satu perusahaan.
     */
    public function paginateCompany(
    Company $company,
    int $perPage = 20
): LengthAwarePaginator {

    return $this->query()
        ->where('company_id', $company->id)
        ->latest('invoice_date')
        ->paginate($perPage);

}

/**
 * Kompatibilitas dengan controller lama.
 */
public function paginate(
    Company $company,
    int $perPage = 20
): LengthAwarePaginator {

    return $this->paginateCompany(
        $company,
        $perPage
    );

}

    /**
     * Transaksi terbaru.
     */
    public function latest(
        Company $company,
        int $limit = 5
    ): Collection {
        return $this->query()
            ->where(
                'company_id',
                $company->id
            )
            ->latest('invoice_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Mengambil satu Purchase milik perusahaan.
     */
    public function find(
        Company $company,
        int|PurchaseHeader $purchase
    ): PurchaseHeader {

        $id = $purchase instanceof PurchaseHeader
            ? $purchase->id
            : $purchase;

        return $this->query()
            ->where(
                'company_id',
                $company->id
            )
            ->findOrFail($id);
    }

    /**
     * Mengambil Purchase berdasarkan ID (Admin).
     */
    public function findAdmin(
        int|PurchaseHeader $purchase
    ): PurchaseHeader {

        $id = $purchase instanceof PurchaseHeader
            ? $purchase->id
            : $purchase;

        return $this->query()
            ->findOrFail($id);
    }

    /**
     * Total transaksi perusahaan.
     */
    public function count(
        Company $company
    ): int {
        return PurchaseHeader::where(
            'company_id',
            $company->id
        )->count();
    }

    /**
     * Total nilai pembelian perusahaan.
     */
    public function totalAmount(
        Company $company
    ): float {

        return (float) PurchaseHeader::where(
            'company_id',
            $company->id
        )->sum('total');
    }
}