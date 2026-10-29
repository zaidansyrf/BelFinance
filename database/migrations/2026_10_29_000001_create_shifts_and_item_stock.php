<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Shift kasir (modal awal, hitung kas akhir) dan stok menu.
     * Stok bernilai null = menu tidak dilacak stoknya, jadi data lama tetap bisa dijual.
     */
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->unsignedInteger('opening_cash')->default(0);
            $table->unsignedInteger('closing_cash')->nullable();
            $table->unsignedInteger('expected_cash')->nullable();
            $table->integer('difference')->nullable();
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->foreignId('shift_id')->nullable()->after('source_id')->constrained('shifts')->nullOnDelete();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('stock')->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('items', fn (Blueprint $table) => $table->dropColumn('stock'));
        Schema::table('incomes', fn (Blueprint $table) => $table->dropConstrainedForeignId('shift_id'));
        Schema::dropIfExists('shifts');
    }
};
