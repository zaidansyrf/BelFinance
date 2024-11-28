<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapHarianTable extends Migration
{
    public function up()
    {
        Schema::create('rekap_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rekap_bulanan')->constrained('rekap_bulanan');
            $table->date('tanggal');
            $table->decimal('total_pemasukan', 15, 2);
            $table->decimal('total_pengeluaran', 15, 2);
            $table->decimal('surplus_defisit', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekap_harian');
    }
}
