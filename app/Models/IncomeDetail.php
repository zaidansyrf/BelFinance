<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeDetail extends Model
{
    use HasFactory;

    protected $table = 'income_details';

    protected $fillable = [
        'income_id',
        'item_id',
        'item_promo_id',
        'quantity',
        'original_price',
        'discount_amount',
        'final_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity'        => 'integer',
        'original_price'  => 'integer',
        'discount_amount' => 'integer',
        'final_price'     => 'integer',
        'subtotal'        => 'integer',
    ];

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(ItemPromo::class, 'item_promo_id');
    }
}
