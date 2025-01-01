<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    // Tentukan nama tabel (opsional, jika nama tabel berbeda dari default plural)
    protected $table = 'bills';

    // Tentukan kolom yang dapat diisi (fillable) untuk keamanan
    protected $fillable = ['name'];

    // Jika Anda ingin menambahkan timestamp secara otomatis
    // (created_at, updated_at) maka pastikan kolom tersebut ada di tabel
    public $timestamps = true; 
}
