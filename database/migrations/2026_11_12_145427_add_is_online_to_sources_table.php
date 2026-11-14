<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->boolean('is_online')->default(false)->after('name');
            $table->decimal('default_commission_percent', 5, 2)->default(0)->after('is_online');
        });
    }

    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            if (Schema::hasColumn('sources', 'default_commission_percent')) {
                $table->dropColumn('default_commission_percent');
            }
            if (Schema::hasColumn('sources', 'is_online')) {
                $table->dropColumn('is_online');
            }
        });
    }
};