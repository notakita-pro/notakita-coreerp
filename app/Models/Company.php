<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    /**
     * --------------------------------------------------------------------------
     * Mass Assignment
     * --------------------------------------------------------------------------
     */
    protected $fillable = [

        'name',
        'phone',

        // Dashboard Security
        'access_token',
        'dashboard_pin',
        'pin_created_at',
        'failed_attempts',
        'locked_until',

        // Membership
        'membership_type',
        'membership_expires_at',
        'used_quota',

    ];

    /**
     * --------------------------------------------------------------------------
     * Attribute Casting
     * --------------------------------------------------------------------------
     */
    protected $casts = [

        'pin_created_at'        => 'datetime',

        'locked_until'          => 'datetime',

        'membership_expires_at' => 'datetime',

        'used_quota'            => 'integer',

        'failed_attempts'       => 'integer',

    ];

    /**
     * --------------------------------------------------------------------------
     * Purchase Header
     * --------------------------------------------------------------------------
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(
            PurchaseHeader::class,
            'company_id'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Purchase Detail
     * --------------------------------------------------------------------------
     */
    public function purchaseDetails(): HasMany
    {
        return $this->hasMany(
            PurchaseDetail::class,
            'company_id'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * OCR Logs
     * --------------------------------------------------------------------------
     */
    public function ocrLogs(): HasMany
    {
        return $this->hasMany(
            OcrLog::class,
            'company_id'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Membership Orders
     * --------------------------------------------------------------------------
     */
    public function membershipOrders(): HasMany
    {
        return $this->hasMany(
            MembershipOrder::class,
            'company_id'
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Membership Aktif Saat Ini
     * --------------------------------------------------------------------------
     */
    public function currentMembership(): string
    {
        if (
            empty($this->membership_type) ||
            empty($this->membership_expires_at) ||
            $this->membership_expires_at->isPast()
        ) {
            return 'free';
        }

        return strtolower(
            $this->membership_type
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Apakah Membership Masih Aktif
     * --------------------------------------------------------------------------
     */
    public function hasMembership(): bool
    {
        return $this->currentMembership() !== 'free';
    }

    /**
     * --------------------------------------------------------------------------
     * Apakah Paket Unlimited
     * --------------------------------------------------------------------------
     */
    public function isUnlimitedQuota(): bool
    {
        $quota = (int) config(
            'membership.' . $this->currentMembership() . '.quota',
            0
        );

        return $quota === -1;
    }

    /**
     * --------------------------------------------------------------------------
     * Sisa Kuota
     * --------------------------------------------------------------------------
     */
    public function remainingQuota(): ?int
    {
        $quota = (int) config(
            'membership.' . $this->currentMembership() . '.quota',
            0
        );

        if ($quota === -1) {
            return null;
        }

        return max(
            $quota - (int) $this->used_quota,
            0
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Dashboard URL
     * --------------------------------------------------------------------------
     */
    public function dashboardUrl(): string
    {
        return route(
            'company.entry',
            [
                'token' => $this->access_token,
            ]
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Membership URL
     * --------------------------------------------------------------------------
     */
    public function membershipUrl(): string
    {
        return route(
            'company.membership',
            [
                'token' => $this->access_token,
            ]
        );
    }
}