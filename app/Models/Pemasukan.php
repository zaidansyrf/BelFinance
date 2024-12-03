<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    // Explicitly set the table name to 'pemasukan' (singular)
    protected $table = 'pemasukan';

    protected $fillable = [
        'id_user', 'id_metode_transaksis', 'nominal', 'keterangan', 'tanggal',
    ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke tabel MetodeTransaksi
    public function metodeTransaksi()
    {
        return $this->belongsTo(MetodeTransaksi::class, 'id_metode_transaksis');
    }
}
