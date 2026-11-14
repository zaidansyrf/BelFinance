<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Income extends Model
{
    use Auditable, HasFactory;

    protected $table = 'incomes';

    protected $fillable = [
        'source_id',
        'shift_id',
        'platform_promo_id',
        'name',
        'gross_amount',
        'item_discount_total',
        'platform_promo_total',
        'platform_merchant_share',
        'platform_app_subsidy',
        'commission_percent',
        'commission_amount',
        'amount',
        'customer_paid_amount',
        'paid_amount',
        'change_amount',
        'date',
        'description',
        'type',
    ];

    protected $casts = [
        'date'                    => 'date',
        'gross_amount'            => 'integer',
        'item_discount_total'     => 'integer',
        'platform_promo_total'    => 'integer',
        'platform_merchant_share' => 'integer',
        'platform_app_subsidy'    => 'integer',
        'commission_percent'      => 'float',
        'commission_amount'       => 'integer',
        'amount'                  => 'integer',
        'customer_paid_amount'    => 'integer',
        'paid_amount'             => 'integer',
        'change_amount'           => 'integer',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function platformPromo(): BelongsTo
    {
        return $this->belongsTo(PlatformPromo::class, 'platform_promo_id');
    }

    public function incomeDetails(): HasMany
    {
        return $this->hasMany(IncomeDetail::class);
    }

    /**
     * Subtotal setelah dikurangi diskon menu merchant.
     */
    public function subtotalAfterItemPromo(): int
    {
        return max(0, (int) $this->gross_amount - (int) $this->item_discount_total);
    }

    /**
     * Hapus (soft delete) pemasukan. Untuk pembayaran, jumlah terjual tiap menu
     * dikurangi lagi dan stok dikembalikan.
     */
    public function hapus(): void
    {
        DB::transaction(function () {
            foreach ($this->incomeDetails()->with('item')->get() as $detail) {
                $detail->item?->decrement('quantity', $detail->quantity);
                $detail->item?->kembalikanStok($detail->quantity);
            }

            $this->delete();
        });
    }
}
