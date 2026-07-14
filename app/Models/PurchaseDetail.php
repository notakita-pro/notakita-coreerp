<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_details';

    protected $fillable = [

        'company_id',

        'purchase_header_id',

        'item_id',

        'qty',

        'unit_price',

        'total_price',

    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function purchase()
    {
        return $this->belongsTo(
            PurchaseHeader::class,
            'purchase_header_id'
        );
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}