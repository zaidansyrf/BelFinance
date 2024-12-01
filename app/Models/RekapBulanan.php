<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapBulanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_akun', 'bulan', 'total_pemasukan', 'total_pengeluaran', 'surplus_defisit',
    ];

    // Relasi ke tabel Akun
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    // Relasi ke tabel RekapHarian
    public function rekapHarian()
    {
        return $this->hasMany(RekapHarian::class, 'id_rekap_bulanan');
    }
}
