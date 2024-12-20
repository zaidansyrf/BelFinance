<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'name',
        'amount',
        'date',
        'description',
    ];

    public function details()
    {
        return $this->hasMany(IncomeDetail::class);
    }
}
