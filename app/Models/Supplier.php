<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    public function purchases()
    {
        return $this->hasMany(PurchaseHeader::class);
    }
}