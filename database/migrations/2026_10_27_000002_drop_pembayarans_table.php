<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel `pembayarans` tidak pernah dipakai (pembayaran disimpan di `incomes` dengan type = payment).
     */
    public function up(): void
    {
        Schema::dropIfExists('pembayarans');
    }

    public function down(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
