<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'stock',
        'code',
        'image',
        'item_promo_id',
        'category_id',
    ];

    protected $casts = [
        'price'         => 'integer',
        'quantity'      => 'integer',
        'stock'         => 'integer',
        'item_promo_id' => 'integer',
    ];

    public function incomeDetails(): HasMany
    {
        return $this->hasMany(IncomeDetail::class);
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(ItemPromo::class, 'item_promo_id');
    }

    /**
     * Ambil promo yang sedang aktif dan valid untuk menu ini.
     */
    public function activePromo(): ?ItemPromo
    {
        if ($this->promo && $this->promo->isCurrentlyActive()) {
            return $this->promo;
        }

        return null;
    }

    // Stok di bawah atau sama dengan angka ini dianggap menipis
    public const STOK_MENIPIS = 5;

    public function lacakStok(): bool
    {
        return $this->stock !== null;
    }

    public function stokMenipis(): bool
    {
        return $this->lacakStok() && $this->stock <= self::STOK_MENIPIS;
    }

    public function kembalikanStok(int $jumlah): void
    {
        if ($this->lacakStok()) {
            $this->increment('stock', $jumlah);
        }
    }

    /**
     * URL gambar menu di disk public, atau null bila belum ada gambar.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }

    /**
     * Inisial nama menu untuk placeholder gambar, mis. "Ayam Bakar" -> "AB".
     */
    protected function initials(): Attribute
    {
        return Attribute::get(function () {
            $words = preg_split('/\s+/', trim($this->name));
            if (count($words) > 1 && preg_match('/^[A-Z]{2,4}$/', $words[0])) {
                array_shift($words);
            }

            if (count($words) === 1) {
                return mb_strtoupper(mb_substr($words[0], 0, 2));
            }

            return mb_strtoupper(collect($words)->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
        });
    }
}
