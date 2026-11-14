<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformPromo extends Model
{
    protected $fillable = [
        'source_id',
        'name',
        'discount_type',
        'discount_value',
        'max_discount',
        'merchant_share_percent',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'integer',
        'max_discount' => 'integer',
        'merchant_share_percent' => 'float',
        'is_active' => 'boolean',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class, 'platform_promo_id');
    }

    /**
     * Hitung total voucher aplikasi beserta pemisahan porsi tanggungan merchant vs subsidi aplikasi.
     */
    public function calculateBreakdown(int $subtotalAfterItemPromo): array
    {
        if ($subtotalAfterItemPromo <= 0) {
            return [
                'total' => 0,
                'merchant_share' => 0,
                'app_subsidy' => 0,
            ];
        }

        if ($this->discount_type === 'nominal') {
            $total = min($this->discount_value, $subtotalAfterItemPromo);
        } else {
            $rawDiscount = (int) round($subtotalAfterItemPromo * ($this->discount_value / 100));
            $cappedDiscount = $this->max_discount
                ? min($rawDiscount, $this->max_discount)
                : $rawDiscount;
            $total = min($cappedDiscount, $subtotalAfterItemPromo);
        }

        $merchantShare = (int) round($total * ($this->merchant_share_percent / 100));
        $appSubsidy = max(0, $total - $merchantShare);

        return [
            'total' => $total,
            'merchant_share' => $merchantShare,
            'app_subsidy' => $appSubsidy,
        ];
    }
}