<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesHeader extends Model
{
    use HasFactory;

    protected $table = 'sales_headers';

    protected $fillable = [

        'company_id',

        'customer_id',

        'invoice_number',
        'invoice_date',
        'due_date',

        'subtotal',
        'discount',
        'tax',
        'transport',
        'other_cost',

        'grand_total',

        'payment_term',
        'payment_method',
        'payment_status',

        'down_payment',
        'paid_amount',
        'balance_due',

        'notes',

        'created_by',
        'updated_by',

    ];

    protected $casts = [

        'invoice_date' => 'date',
        'due_date'     => 'date',

        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'tax'          => 'decimal:2',
        'transport'    => 'decimal:2',
        'other_cost'   => 'decimal:2',

        'grand_total'  => 'decimal:2',

        'down_payment' => 'decimal:2',
        'paid_amount'  => 'decimal:2',
        'balance_due'  => 'decimal:2',

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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(SalesDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getBalanceAttribute()
    {
        return $this->balance_due;
    }

    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'paid';
    }

    public function getIsPartialAttribute()
    {
        return $this->payment_status === 'partial';
    }

    public function getIsUnpaidAttribute()
    {
        return $this->payment_status === 'unpaid';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('invoice_date', today());
    }
}