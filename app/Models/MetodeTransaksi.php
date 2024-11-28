<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodeTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'metode_transaksi',
    ];

    // Relasi ke tabel Pemasukan
    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'id_metode_transaksi');
    }

    // Relasi ke tabel Pengeluaran
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_metode_transaksi');
    }
}
