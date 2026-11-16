<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_promo_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('item_promo_id')->constrained('item_promos')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['item_id', 'item_promo_id']);
        });

        // Migrasikan relasi yang sudah ada di tabel items ke tabel pivot item_promo_item
        $existing = DB::table('items')->whereNotNull('item_promo_id')->get();
        foreach ($existing as $row) {
            DB::table('item_promo_item')->insertOrIgnore([
                'item_id' => $row->id,
                'item_promo_id' => $row->item_promo_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('item_promo_item');
    }
};
