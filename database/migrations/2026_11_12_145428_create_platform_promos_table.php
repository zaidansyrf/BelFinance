<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
            $table->string('name', 150);
            $table->enum('discount_type', ['percent', 'nominal']);
            $table->unsignedInteger('discount_value');
            $table->unsignedInteger('max_discount')->nullable();
            // 0 = 100% subsidi aplikasi, 50 = patungan 50:50, 100 = 100% ditanggung merchant
            $table->decimal('merchant_share_percent', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_promos');
    }
};