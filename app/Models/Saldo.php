<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_akun', 'saldo_awal', 'saldo_akhir', 'tanggal',
    ];

    // Relasi ke tabel Akun
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }
}
