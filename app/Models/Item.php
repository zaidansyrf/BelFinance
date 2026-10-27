<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'items';

    // Define the fillable columns
    protected $fillable = ['name', 'price', 'quantity', 'code', 'image'];

    // Define the relationship with income details
    public function incomeDetails()
    {
        return $this->hasMany(IncomeDetail::class);
    }

    /**
     * URL gambar menu di disk public, atau null bila belum ada gambar.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }

    /**
     * Inisial nama menu untuk placeholder gambar, mis. "Ayam Bakar" -> "AB".
     * Awalan kategori berupa singkatan huruf kapital (mis. "KO", "ALC") dilewati.
     */
    protected function initials(): Attribute
    {
        return Attribute::get(function () {
            $words = preg_split('/\s+/', trim($this->name));
            if (count($words) > 1 && preg_match('/^[A-Z]{2,4}$/', $words[0])) {
                array_shift($words);
            }

            // Satu kata: ambil dua huruf pertama ("Cumi" -> "CU")
            if (count($words) === 1) {
                return mb_strtoupper(mb_substr($words[0], 0, 2));
            }

            return mb_strtoupper(collect($words)->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
        });
    }
}
