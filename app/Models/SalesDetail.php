<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;

    protected $table = 'sales_details';

    protected $fillable = [

        'company_id',

        'sales_header_id',

        'item_id',
        'item_name',
        'unit',

        'qty',

        'unit_price',
        'cost_price',

        'discount',
        'tax',

        'total_price',

        'notes',

    ];

    protected $casts = [

        'qty'         => 'decimal:2',

        'unit_price'  => 'decimal:2',
        'cost_price'  => 'decimal:2',

        'discount'    => 'decimal:2',
        'tax'         => 'decimal:2',

        'total_price' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function header()
    {
        return $this->belongsTo(SalesHeader::class, 'sales_header_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Nilai sebelum pajak.
     */
    public function getNetTotalAttribute()
    {
        return $this->total_price - $this->tax;
    }

    /**
     * Estimasi laba kotor per item.
     */
    public function getGrossProfitAttribute()
    {
        return ($this->unit_price - $this->cost_price) * $this->qty;
    }
}