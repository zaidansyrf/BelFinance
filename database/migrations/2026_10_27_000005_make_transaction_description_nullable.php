<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Keterangan pemasukan dan pengeluaran bersifat opsional di form/validasi,
 * jadi kolomnya juga harus boleh kosong (sebelumnya NOT NULL sehingga simpan gagal).
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['incomes', 'expenses'] as $tabel) {
            Schema::table($tabel, function (Blueprint $table) {
                $table->text('description')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        foreach (['incomes', 'expenses'] as $tabel) {
            DB::table($tabel)->whereNull('description')->update(['description' => '']);

            Schema::table($tabel, function (Blueprint $table) {
                $table->text('description')->nullable(false)->change();
            });
        }
    }
};
