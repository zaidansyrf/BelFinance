<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'opened_at', 'closed_at', 'opening_cash',
        'closing_cash', 'expected_cash', 'difference', 'note',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    public function isOpen(): bool
    {
        return $this->closed_at === null;
    }

    /**
     * Shift yang sedang berjalan milik user, atau null bila belum buka shift.
     */
    public static function aktifUntuk(User $user): ?self
    {
        return static::where('user_id', $user->id)->whereNull('closed_at')->latest('opened_at')->first();
    }

    public function totalPenjualan(): int
    {
        return (int) $this->incomes()->sum('amount');
    }

    /**
     * Penjualan tunai selama shift (uang yang seharusnya masuk laci kas).
     */
    public function penjualanTunai(): int
    {
        return (int) $this->incomes()
            ->whereHas('source', fn ($q) => $q->whereIn('name', Source::NAMA_TUNAI))
            ->sum('amount');
    }

    public function kasDiharapkan(): int
    {
        return $this->opening_cash + $this->penjualanTunai();
    }

    /**
     * Tutup shift dengan uang kas hasil hitung fisik. Selisih = hitung fisik - kas yang diharapkan.
     */
    public function tutup(int $closingCash, ?string $note = null): void
    {
        $expected = $this->kasDiharapkan();

        $this->update([
            'closed_at' => now(),
            'closing_cash' => $closingCash,
            'expected_cash' => $expected,
            'difference' => $closingCash - $expected,
            'note' => $note,
        ]);
    }
}
