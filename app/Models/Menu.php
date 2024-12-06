<?php

// app/Models/Menu.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'menu';

    // Define the fillable columns
    protected $fillable = ['nama', 'harga', 'jumlah'];
}
