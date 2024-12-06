<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembayaran')->constrained('pembayaran');
            $table->foreignId('id_menu')->constrained('menu');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pembayaran');
    }
};
