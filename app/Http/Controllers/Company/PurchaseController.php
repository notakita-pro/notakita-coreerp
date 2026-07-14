<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PurchaseHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\PurchaseValidationService;
use App\Services\MembershipService;

class PurchaseController extends Controller
{
    /**
     * Inisialisasi Service Utama
     */
    public function __construct(
        protected PurchaseValidationService $validator,
        protected MembershipService $membershipService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | AREA PENAMPIL GAMBAR NOTA (VIEW RECEIPT)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan gambar nota - Jalur Admin Internal
     */
    public function viewReceipt(Request $request, $company, $purchase)
    {
        $purchaseId = is_object($purchase) ? $purchase->id : $purchase;
        $purchaseData = PurchaseHeader::findOrFail($purchaseId);

        $this->authorizeAdminPurchase($purchaseData);

        return $this->streamReceiptFile($purchaseData);
    }

    /**
     * Menampilkan gambar nota - Jalur Customer Public Token
     */
    public function viewReceiptByToken(Request $request, string $token, $purchase)
    {
        $purchaseId = is_object($purchase) ? $purchase->id : $purchase;
        $purchaseData = PurchaseHeader::findOrFail($purchaseId);

        $company = $request->attributes->get('company');
        $this->authorizeCustomerPurchase($company, $purchaseData);

        return $this->streamReceiptFile($purchaseData);
    }

    /*
    |--------------------------------------------------------------------------
    | AREA PENYIMPANAN / UPDATE DATA NOTA
    |--------------------------------------------------------------------------
    */

    /**
     * Menyimpan Perubahan Data Nota - Jalur Customer Public Token
     */
    public function updateByToken(Request $request, string $token, $purchase)
    {
        $purchaseId = is_object($purchase) ? $purchase->id : $purchase;
        $purchaseData = PurchaseHeader::with(['company', 'supplier'])->findOrFail($purchaseId);

        // Proteksi Akses Token
        $company = $request->attributes->get('company');
        $this->authorizeCustomerPurchase($company, $purchaseData);

        // Eksekusi Update Core Data
        $this->executePurchaseUpdate($request, $purchaseData);

        return redirect()
            ->route('company.purchase.show', [
                'token' => $token,
                'purchase' => $purchaseData->id,
            ])
            ->with('success', 'Data nota berhasil diperbarui.');
    }

    /**
     * Menyimpan Perubahan Data Nota - Jalur Admin Internal
     */
    public function update(Request $request, $company, $purchase)
    {
        $purchaseId = is_object($purchase) ? $purchase->id : $purchase;
        $purchaseData = PurchaseHeader::with(['company', 'supplier'])->findOrFail($purchaseId);

        $this->authorizeAdminPurchase($purchaseData);

        $this->executePurchaseUpdate($request, $purchaseData);

        return redirect()
            ->route('admin.company.purchase.show', [
                'company' => $company,
                'purchase' => $purchaseData->id,
            ])
            ->with('success', 'Data nota berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER / LOGIC INTERNAL CONTROLLER
    |--------------------------------------------------------------------------
    */

    /**
     * Core Logic Pembaruan Data Nota & Relasinya
     */
    protected function executePurchaseUpdate(Request $request, PurchaseHeader $purchase)
    {
        // 1. Update Header Tanggal
        $purchase->update([
            'invoice_date' => $request->invoice_date,
        ]);

        // 2. Update Nama Supplier
        if ($request->filled('supplier') && $purchase->supplier) {
            $purchase->supplier->update([
                'name' => trim($request->supplier),
            ]);
        }

        // 3. Update Detail Item Terkait
        if ($request->filled('items')) {
            foreach ($request->items as $item) {
                $detail = $purchase->details()
                    ->where('id', $item['id'] ?? null)
                    ->first();

                if (!$detail) {
                    continue;
                }

                if ($detail->item) {
                    $detail->item->update([
                        'name' => $item['name'] ?? $detail->item->name,
                    ]);
                }

                // Hitung otomatis subtotal baris item
                $qty = $item['qty'] ?? 0;
                $price = $item['price'] ?? 0;

                $detail->update([
                    'qty'        => $qty,
                    'unit_price' => $price,
                    'total_price'=> $qty * $price, // kalkulasi aman server-side
                ]);
            }
        }

        // 4. Hitung Ulang Total & Subtotal Global Nota
        $purchase->refresh();
        $subtotal = $purchase->details()->sum('total_price');
        $total = $subtotal + ($purchase->tax ?? 0);

        $purchase->update([
            'subtotal' => $subtotal,
            'total'    => $total,
        ]);
    }

    /**
     * Logic Pengambilan Berkas Fisik Nota dari Storage Private
     */
    protected function streamReceiptFile(PurchaseHeader $purchase)
    {
        $directory = storage_path(
            'app/receipts/' . $purchase->created_at->format('Y/m/d')
        );

        $path = $directory . '/' . $purchase->image_file;

        if (!File::exists($path)) {
            abort(404, 'File nota tidak ditemukan.');
        }

        return response()->file($path, [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    /**
     * Guard Keamanan Admin
     */
    private function authorizeAdminPurchase(PurchaseHeader $purchase)
    {
        if (auth()->check() && auth()->user()->role == 1) {
            return;
        }
        abort(403, 'Akses ditolak. Area khusus Administrator.');
    }

    /**
     * Guard Keamanan Customer Token
     */
    private function authorizeCustomerPurchase($company, PurchaseHeader $purchase)
    {
        if ($company && $company->id == $purchase->company_id) {
            return;
        }
        abort(403, 'Akses ditolak. Token tidak sah untuk dokumen ini.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | METHOD TAMBAHAN UNTUK AREA CUSTOMER (TOKEN AREA)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan daftar purchase berdasarkan Token
     */
    public function indexByToken(Request $request, string $token)
    {
        $company = $request->attributes->get('company');

        $purchases = PurchaseHeader::with(['supplier', 'details.item'])
            ->where('company_id', $company->id)
            ->latest()
            ->paginate(15);

        foreach ($purchases as $purchase) {
            $this->validator->analyze($purchase);
        }

        $membership = $this->membershipService->get($company);

        return view('dashboard.home', [
            'company'    => $company,
            'purchases'  => $purchases,
            'membership' => $membership,
        ]);
    }

    /**
     * Menampilkan Detail Nota berdasarkan Token
     */
    public function showByToken(Request $request, string $token, $purchase)
    {
        $purchaseId = is_object($purchase) ? $purchase->id : $purchase;
        $purchaseData = PurchaseHeader::with(['company', 'supplier', 'details.item'])->findOrFail($purchaseId);

        $company = $request->attributes->get('company');
        $this->authorizeCustomerPurchase($company, $purchaseData);
        $this->validator->analyze($purchaseData);

        return view('dashboard.show', ['purchase' => $purchaseData]);
    }

    /**
     * Menampilkan Form Edit Nota berdasarkan Token
     */
    public function editByToken(Request $request, string $token, $purchase)
    {
        $purchaseId = is_object($purchase) ? $purchase->id : $purchase;
        $purchaseData = PurchaseHeader::with(['company', 'supplier', 'details.item'])->findOrFail($purchaseId);

        $company = $request->attributes->get('company');
        $this->authorizeCustomerPurchase($company, $purchaseData);
        $this->validator->analyze($purchaseData);

        return view('dashboard.edit', ['purchase' => $purchaseData]);
    }

    /**
     * Menghapus Data Transaksi Nota via Token
     */
    public function destroyByToken(Request $request, string $token, $purchase)
    {
        $purchaseId = is_object($purchase) ? $purchase->id : $purchase;
        $purchaseData = PurchaseHeader::findOrFail($purchaseId);

        $company = $request->attributes->get('company');
        $this->authorizeCustomerPurchase($company, $purchaseData);

        $purchaseData->delete();

        return redirect()
            ->route('company.purchase', ['token' => $token])
            ->with('success', 'Nota berhasil dihapus.');
    }
}