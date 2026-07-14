<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipOrder extends Model
{
    use HasFactory;

    protected $table = 'membership_orders';

    /**
     * --------------------------------------------------------------------------
     * Route Model Binding
     * --------------------------------------------------------------------------
     *
     * Seluruh URL Payment menggunakan invoice_number,
     * bukan ID database.
     *
     */
    public function getRouteKeyName(): string
    {
        return 'invoice_number';
    }

    /**
     * --------------------------------------------------------------------------
     * Mass Assignment
     * --------------------------------------------------------------------------
     */
    protected $fillable = [

        'company_id',

        'invoice_number',

        'package',

        'amount',

        'currency',

        'status',

        'payment_gateway',

        'payment_url',

        'external_id',

        'expires_at',

        'paid_at',

        'notes',

    ];

    /**
     * --------------------------------------------------------------------------
     * Attribute Casting
     * --------------------------------------------------------------------------
     */
    protected $casts = [

        'amount' => 'decimal:2',

        'expires_at' => 'datetime',

        'paid_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'PAID');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'EXPIRED');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'CANCELLED');
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helper
    |--------------------------------------------------------------------------
    */

    public function getStatus(): string
    {
        return strtoupper(
            (string) $this->status
        );
    }

    public function isPaid(): bool
    {
        return $this->getStatus() === 'PAID';
    }

    public function isPending(): bool
    {
        return $this->getStatus() === 'PENDING';
    }

    public function isExpired(): bool
    {
        return $this->getStatus() === 'EXPIRED';
    }

    public function isCancelled(): bool
    {
        return $this->getStatus() === 'CANCELLED';
    }

    /**
     * Status akhir transaksi.
     */
    public function isFinished(): bool
    {
        return in_array(
            $this->getStatus(),
            [
                'PAID',
                'EXPIRED',
                'CANCELLED',
            ],
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Updater
    |--------------------------------------------------------------------------
    */

    public function markPaid(): void
    {
        $this->update([

            'status' => 'PAID',

            'paid_at' => now(),

        ]);
    }

    public function markPending(): void
    {
        $this->update([

            'status' => 'PENDING',

        ]);
    }

    public function markExpired(): void
    {
        $this->update([

            'status' => 'EXPIRED',

        ]);
    }

    public function markCancelled(): void
    {
        $this->update([

            'status' => 'CANCELLED',

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Helper
    |--------------------------------------------------------------------------
    */

    public function latestPayment(): ?Payment
    {
        return $this->payments()
            ->latest('id')
            ->first();
    }
}