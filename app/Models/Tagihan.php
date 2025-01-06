<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;


    protected $table = 'tagihan';

    // Tambahkan kolom yang bisa diisi
    protected $fillable = [
        'nama',
    ];
    public $timestamps = true;  // Ini sudah diatur secara default jadi bisa dihilangkan
}
