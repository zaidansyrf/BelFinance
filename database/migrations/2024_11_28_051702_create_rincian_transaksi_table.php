<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRincianTransaksiTable extends Migration
{
    public function up()
    {
        Schema::create('rincian_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi')->constrained('pemasukan');
            $table->integer('jumlah_item');
            $table->decimal('harga_item', 15, 2);
            $table->decimal('harga_real', 15, 2)->nullable();
            $table->decimal('asumsi_pendapatan', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rincian_transaksi');
    }
}
