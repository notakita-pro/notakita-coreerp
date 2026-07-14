<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PurchaseHeader extends Model
{
    protected $table = 'purchase_headers';

    protected $fillable = [
        'company_id',
        'supplier_id',
        'invoice_number',
        'invoice_date',
        'subtotal',
        'tax',
        'total',
        'image_file',
        'raw_json',
        'source',
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Receipt Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Mengembalikan path absolut file nota.
     *
     * Contoh:
     * /storage/app/public/receipts/2026/07/xxxxx.jpg
     */
    public function getReceiptImagePathAttribute()
    {
        if (blank($this->image_file)) {
            return null;
        }

        $files = Storage::disk('public')->allFiles('receipts');

        foreach ($files as $file) {

            if (basename($file) === $this->image_file) {
                return storage_path('app/public/' . $file);
            }

        }

        return null;
    }

    /**
     * Mengembalikan URL public gambar nota.
     *
     * Contoh:
     * /storage/receipts/2026/07/xxxxx.jpg
     */
    public function getReceiptImageUrlAttribute()
    {
        if (blank($this->image_file)) {
            return null;
        }

        $files = Storage::disk('public')->allFiles('receipts');

        foreach ($files as $file) {

            if (basename($file) === $this->image_file) {
                return Storage::url($file);
            }

        }

        return null;
    }

    /**
     * Mengecek apakah file nota benar-benar ada.
     */
    public function getHasReceiptAttribute()
    {
        return ! empty($this->receipt_image_path)
            && file_exists($this->receipt_image_path);
    }
}