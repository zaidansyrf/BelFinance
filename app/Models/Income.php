<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;
    protected $table = 'incomes';

    protected $fillable = [
        'source_id',
        'name',
        'amount',
        'date',
        'description',
    ];
    protected $casts = [
        'date' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(IncomeDetail::class, 'income_id');
    }
    public function source()
    {
        return $this->belongsTo(Source::class);
    }
}
