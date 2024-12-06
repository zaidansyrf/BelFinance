<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pemasukkan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sumber')->constrained('sumber');
            $table->string('nama', 255);
            $table->integer('nominal');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemasukkan');
    }
};