<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemPromo extends Model
{
    protected $fillable = [
        'name',
        'discount_type',
        'discount_value',
        'max_discount',
        'channel_scope',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'integer',
        'max_discount' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_promo_item')->withTimestamps();
    }

    public function itemsDirect(): HasMany
    {
        return $this->hasMany(Item::class, 'item_promo_id');
    }

    /**
     * Cek apakah promo sedang aktif berdasarkan status is_active dan periode tanggal.
     */
    public function isCurrentlyActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Cek apakah promo item aktif dan cocok dengan channel pesanan (online/offline).
     */
    public function isEligibleForScope(bool $isOnline): bool
    {
        if (! $this->isCurrentlyActive()) {
            return false;
        }

        return match ($this->channel_scope) {
            'online_only'  => $isOnline,
            'offline_only' => ! $isOnline,
            default        => true,
        };
    }

    /**
     * Cek apakah promo item aktif, masih dalam periode berlaku, dan cocok dengan channel (online/offline).
     */
    public function isEligibleForSource(?Source $source = null): bool
    {
        if (! $this->isCurrentlyActive()) {
            return false;
        }

        if (! $source) {
            return true;
        }

        return match ($this->channel_scope) {
            'online_only'  => (bool) $source->is_online,
            'offline_only' => ! (bool) $source->is_online,
            default        => true,
        };
    }

    /**
     * Hitung rupiah potongan per porsi menu.
     */
    public function calculateUnitDiscount(int $originalPrice): int
    {
        if ($this->discount_type === 'nominal') {
            return min($this->discount_value, $originalPrice);
        }

        $rawDiscount = (int) round($originalPrice * ($this->discount_value / 100));
        $cappedDiscount = $this->max_discount
            ? min($rawDiscount, $this->max_discount)
            : $rawDiscount;

        return min($cappedDiscount, $originalPrice);
    }
}