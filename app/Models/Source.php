<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;
    

    // Tentukan nama tabel (opsional, jika nama tabel berbeda dari default plural)
    // Nama sumber pembayaran yang dianggap tunai (pencocokan di MySQL tidak peka huruf besar/kecil)
    public const NAMA_TUNAI = ['CASH', 'TUNAI'];

    protected $table = 'sources';

    // Tentukan kolom yang dapat diisi (fillable) untuk keamanan
    protected $fillable = ['name'];

    // Jika Anda ingin menambahkan timestamp secara otomatis
    // (created_at, updated_at) maka pastikan kolom tersebut ada di tabel
    public $timestamps = true;  // Ini sudah diatur secara default jadi bisa dihilangkan

    public function isCash(): bool
    {
        return in_array(strtoupper(trim($this->name)), ['CASH', 'TUNAI'], true);
    }
}
