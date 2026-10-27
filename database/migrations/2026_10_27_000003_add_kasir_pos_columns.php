<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fitur POS: role kasir, gambar menu, dan uang diterima/kembalian di transaksi.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['keuangan', 'owner', 'kasir'])->change();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('image')->nullable()->after('quantity');
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->unsignedInteger('paid_amount')->nullable()->after('amount');
            $table->unsignedInteger('change_amount')->nullable()->after('paid_amount');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'change_amount']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['keuangan', 'owner'])->change();
        });
    }
};
