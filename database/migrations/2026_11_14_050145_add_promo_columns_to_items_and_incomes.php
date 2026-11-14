<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Satu menu hanya memegang 1 promo aktif (mencegah double discount)
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('item_promo_id')
                ->nullable()
                ->after('price')
                ->constrained('item_promos')
                ->nullOnDelete();
        });

        // 2. Snapshot harga & diskon item saat transaksi terjadi
        Schema::table('income_details', function (Blueprint $table) {
            $table->foreignId('item_promo_id')
                ->nullable()
                ->after('item_id')
                ->constrained('item_promos')
                ->nullOnDelete();
            $table->unsignedInteger('original_price')->default(0)->after('quantity');
            $table->unsignedInteger('discount_amount')->default(0)->after('original_price');
            $table->unsignedInteger('final_price')->default(0)->after('discount_amount');
        });

        // 3. Rekap finansial & pemisahan beban promo di header transaksi (incomes)
        Schema::table('incomes', function (Blueprint $table) {
            $table->foreignId('platform_promo_id')
                ->nullable()
                ->after('shift_id')
                ->constrained('platform_promos')
                ->nullOnDelete();
            $table->unsignedInteger('gross_amount')->default(0)->after('name');
            $table->unsignedInteger('item_discount_total')->default(0)->after('gross_amount');
            $table->unsignedInteger('platform_promo_total')->default(0)->after('item_discount_total');
            $table->unsignedInteger('platform_merchant_share')->default(0)->after('platform_promo_total');
            $table->unsignedInteger('platform_app_subsidy')->default(0)->after('platform_merchant_share');
            $table->decimal('commission_percent', 5, 2)->default(0)->after('platform_app_subsidy');
            $table->unsignedInteger('commission_amount')->default(0)->after('commission_percent');
            $table->unsignedInteger('customer_paid_amount')->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('platform_promo_id');
            $table->dropColumn([
                'gross_amount',
                'item_discount_total',
                'platform_promo_total',
                'platform_merchant_share',
                'platform_app_subsidy',
                'commission_percent',
                'commission_amount',
                'customer_paid_amount',
            ]);
        });

        Schema::table('income_details', function (Blueprint $table) {
            $table->dropConstrainedForeignId('item_promo_id');
            $table->dropColumn(['original_price', 'discount_amount', 'final_price']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('item_promo_id');
        });
    }
};