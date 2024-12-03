<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetodeTransaksiTable extends Migration
{
    public function up()
    {
        Schema::create('metode_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('metode_transaksi', 50);  // Menambah kolom metode_transaksi
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('metode_transaksis');
    }
    
}

