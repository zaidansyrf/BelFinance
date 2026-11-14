<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_promos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('discount_type', ['percent', 'nominal']);
            $table->unsignedInteger('discount_value');
            $table->unsignedInteger('max_discount')->nullable();
            $table->enum('channel_scope', ['all', 'online_only', 'offline_only'])->default('all');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_promos');
    }
};