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
        'subtotal',
    ];

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
