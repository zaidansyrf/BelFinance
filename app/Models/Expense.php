<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'bill_id',
        'amount',
        'date',
        'description',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
    
    // Relationships
    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}
