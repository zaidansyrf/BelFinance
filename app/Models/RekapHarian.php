<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_rekap_bulanan', 'tanggal', 'total_pemasukan', 'total_pengeluaran', 'surplus_defisit',
    ];

    // Relasi ke tabel RekapBulanan
    public function rekapBulanan()
    {
        return $this->belongsTo(RekapBulanan::class, 'id_rekap_bulanan');
    }

    // Relasi ke tabel DataItem
    public function dataItem()
    {
        return $this->hasMany(DataItem::class, 'id_rekap_harian');
    }
}
