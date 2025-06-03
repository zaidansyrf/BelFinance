<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\IncomeDetail;
use App\Models\Source;

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
        'type',
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
    public function incomeDetails(): HasMany
    {
        return $this->hasMany(IncomeDetail::class);
    }
}
