<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    /**
     * --------------------------------------------------------------------------
     * Mass Assignment
     * --------------------------------------------------------------------------
     */
    protected $fillable = [

        'membership_order_id',

        'gateway',

        'channel',

        'reference',

        'external_id',

        'amount',

        'currency',

        'status',

        'paid_at',

        'payload',

    ];

    /**
     * --------------------------------------------------------------------------
     * Attribute Casting
     * --------------------------------------------------------------------------
     */
    protected $casts = [

        'payload' => 'array',

        'paid_at' => 'datetime',

        'amount' => 'decimal:2',

    ];


    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function membershipOrder()
    {
        return $this->belongsTo(
            MembershipOrder::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helper
    |--------------------------------------------------------------------------
    */

    public function isPaid(): bool
    {
        return $this->status === 'PAID';
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    public function isExpired(): bool
    {
        return $this->status === 'EXPIRED';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'CANCELLED';
    }

    public function isFailed(): bool
    {
        return $this->status === 'FAILED';
    }

    /*
    |--------------------------------------------------------------------------
    | Status Updater
    |--------------------------------------------------------------------------
    */

    public function markPaid(array $payload = []): void
    {
        $this->update([

            'status' => 'PAID',

            'paid_at' => now(),

            'payload' => $payload,

        ]);
    }

    public function markPending(array $payload = []): void
    {
        $this->update([

            'status' => 'PENDING',

            'payload' => $payload,

        ]);
    }

    public function markExpired(array $payload = []): void
    {
        $this->update([

            'status' => 'EXPIRED',

            'payload' => $payload,

        ]);
    }

    public function markCancelled(array $payload = []): void
    {
        $this->update([

            'status' => 'CANCELLED',

            'payload' => $payload,

        ]);
    }

    public function markFailed(array $payload = []): void
    {
        $this->update([

            'status' => 'FAILED',

            'payload' => $payload,

        ]);
    }
}