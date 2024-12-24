<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
        use HasFactory;
        // Specify the table name
        protected $table = 'items';

        // Define the fillable columns
        protected $fillable = ['name', 'price', 'quantity'];
        
        // Define the relationship with income details
        public function incomeDetails()
        {
            return $this->hasMany(IncomeDetail::class);
        }
        
}

