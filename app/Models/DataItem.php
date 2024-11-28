<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_item', 'jumlah', 'harga', 'asumsi_pendapatan', 'harga_real', 'id_rekap_harian',
    ];

    // Relasi ke tabel RekapHarian
    public function rekapHarian()
    {
        return $this->belongsTo(RekapHarian::class, 'id_rekap_harian');
    }
}
