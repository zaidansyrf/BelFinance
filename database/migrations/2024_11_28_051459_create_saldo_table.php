<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaldoTable extends Migration
{
    public function up()
    {
        Schema::create('saldo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_akun')->constrained('akuns');
            $table->decimal('saldo_awal', 15, 2);
            $table->decimal('saldo_akhir', 15, 2);
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saldo');
    }
}
