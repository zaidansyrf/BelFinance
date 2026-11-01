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
        'amount',
        'paid_amount',
        'change_amount',
        'date',
        'description',
        'type',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

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
