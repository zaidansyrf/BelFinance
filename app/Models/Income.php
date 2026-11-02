<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Income extends Model
{
    use Auditable, HasFactory;

    protected $table = 'incomes';

    protected $fillable = [
        'source_id',
        'shift_id',
        'name',
        'order_source',
        'amount',
        'gross_total',
        'app_discount_type',
        'app_discount_value',
        'is_app_subsidy',
        'net_total',
        'paid_amount',
        'change_amount',
        'date',
        'description',
        'type',
    ];

    protected $casts = [
        'date'          => 'datetime',
        'is_app_subsidy' => 'boolean',
        'gross_total'   => 'integer',
        'net_total'     => 'integer',
        'app_discount_value' => 'float',
    ];

    // --- Helper Promo ---

    /** Nominal potongan dari aplikasi pihak ketiga */
    public function appDiscountAmount(): int
    {
        if (! $this->app_discount_type || ! $this->app_discount_value) {
            return 0;
        }
        $gross = $this->gross_total ?? $this->amount;
        if ($this->app_discount_type === 'percent') {
            return (int) round(($this->app_discount_value / 100) * $gross);
        }
        return (int) $this->app_discount_value;
    }

    /** Skenario A: selisih gross-net = piutang ke aplikasi */
    public function piutangAplikasi(): int
    {
        if (! $this->is_app_subsidy) {
            return 0;
        }
        return $this->appDiscountAmount();
    }

    /** Skenario B: selisih gross-net = beban promosi merchant */
    public function bebanPromosi(): int
    {
        if ($this->is_app_subsidy) {
            return 0;
        }
        return $this->appDiscountAmount();
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function incomeDetails(): HasMany
    {
        return $this->hasMany(IncomeDetail::class);
    }

    /**
     * Hapus (soft delete) pemasukan. Untuk pembayaran, jumlah terjual tiap menu
     * dikurangi lagi. Detail pesanan tetap disimpan agar data bisa dipulihkan.
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
