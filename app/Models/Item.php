<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'name',
        'unit',
    ];
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}