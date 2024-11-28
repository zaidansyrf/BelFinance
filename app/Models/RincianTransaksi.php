<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_transaksi', 'jumlah_item', 'harga_item', 'harga_real', 'asumsi_pendapatan',
    ];

    // Relasi ke tabel Pemasukan
    public function pemasukan()
    {
        return $this->belongsTo(Pemasukan::class, 'id_transaksi');
    }

    // Relasi ke tabel Pengeluaran
    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_transaksi');
    }
}
