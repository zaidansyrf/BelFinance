<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    use HasFactory;

    // Nama sumber pembayaran yang dianggap tunai (pencocokan di MySQL tidak peka huruf besar/kecil)
    public const NAMA_TUNAI = ['CASH', 'TUNAI'];

    protected $table = 'sources';

    protected $fillable = [
        'name',
        'is_online',
        'default_commission_percent',
    ];

    protected $casts = [
        'is_online'                  => 'boolean',
        'default_commission_percent' => 'float',
    ];

    public function isCash(): bool
    {
        return in_array(strtoupper(trim($this->name)), self::NAMA_TUNAI, true);
    }

    public function platformPromos(): HasMany
    {
        return $this->hasMany(PlatformPromo::class, 'source_id');
    }

    public function activePlatformPromos(): HasMany
    {
        return $this->platformPromos()->where('is_active', true);
    }
}
