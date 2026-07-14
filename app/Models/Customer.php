<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    /**
     * ==========================================================
     * Table
     * ==========================================================
     */
    protected $table = 'customers';

    /**
     * ==========================================================
     * Mass Assignment
     * ==========================================================
     */
    protected $fillable = [

        'company_id',

        'name',

        'phone',

        'email',

        'address',

        'notes',

    ];

    /**
     * ==========================================================
     * Casting
     * ==========================================================
     */
    protected $casts = [

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

    ];

    /**
     * ==========================================================
     * Company
     * ==========================================================
     */
    public function company()
    {
        return $this->belongsTo(
            Company::class
        );
    }

    /**
     * ==========================================================
     * Sales Header
     * ==========================================================
     */
    public function salesHeaders()
    {
        return $this->hasMany(
            SalesHeader::class,
            'customer_id'
        );
    }

    /**
     * ==========================================================
     * Accessor
     * ==========================================================
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->phone) {
            return "{$this->name} ({$this->phone})";
        }

        return $this->name;
    }
}