<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataItemTable extends Migration
{
    public function up()
    {
        Schema::create('data_item', function (Blueprint $table) {
            $table->id();
            $table->string('nama_item', 255);
            $table->integer('jumlah');
            $table->decimal('harga', 15, 2);
            $table->decimal('asumsi_pendapatan', 15, 2)->nullable();
            $table->decimal('harga_real', 15, 2)->nullable();
            $table->foreignId('id_rekap_harian')->constrained('rekap_harian');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_item');
    }
}
