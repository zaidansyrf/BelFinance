<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeDetail extends Model
{
    use HasFactory;
    protected $table = 'income_details';
    protected $fillable = [
        'income_id',
        'item_id',
        'quantity',
        'item_discount_type',
        'item_discount_value',
        'subtotal',
    ];

    /** Nominal diskon per item (sudah × qty) */
    public function discountAmount(): int
    {
        if (! $this->item_discount_type || ! $this->item_discount_value) {
            return 0;
        }
        if ($this->item_discount_type === 'percent') {
            $hargaSatuan = $this->item && $this->quantity
                ? ($this->subtotal / $this->quantity)
                : 0;
            return (int) round(($this->item_discount_value / 100) * $hargaSatuan);
        }
        return (int) $this->item_discount_value;
    }

    public function income()
    {
        return $this->belongsTo(Income::class);

    }

    public function item()
    {
        return $this->belongsTo(Item::class);

    }

}
