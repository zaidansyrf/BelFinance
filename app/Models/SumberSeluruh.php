<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberSeluruh extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'sumber_seluruh';

    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = [
        'id_metode_transaksis',
        'pemasukkan',
        'pengeluaran',
        'keterangan',
        'tanggal',
    ];

    /**
     * Relasi ke tabel metode_transaksis
     * (One to Many)
     */
    public function metodeTransaksi()
    {
        return $this->belongsTo(MetodeTransaksi::class, 'id_metode_transaksis');
    }
}
