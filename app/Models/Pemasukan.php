<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_akun', 'id_metode_transaksi', 'nominal', 'keterangan', 'tanggal',
    ];

    // Relasi ke tabel Akun
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    // Relasi ke tabel MetodeTransaksi
    public function metodeTransaksi()
    {
        return $this->belongsTo(MetodeTransaksi::class, 'id_metode_transaksi');
    }

    // Relasi ke tabel RincianTransaksi
    public function rincianTransaksi()
    {
        return $this->hasMany(RincianTransaksi::class, 'id_transaksi');
    }
}
