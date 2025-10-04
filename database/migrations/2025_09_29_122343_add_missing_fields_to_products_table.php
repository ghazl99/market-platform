<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            $table->boolean('is_active')->default(true)->after('status');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->string('sku')->nullable()->after('is_featured');
            $table->integer('stock_quantity')->default(0)->after('sku');
            $table->string('weight')->nullable()->after('stock_quantity');
            $table->string('dimensions')->nullable()->after('weight');
            $table->string('seo_title')->nullable()->after('dimensions');
            $table->text('seo_description')->nullable()->after('seo_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sale_price',
                'is_active',
                'is_featured',
                'sku',
                'stock_quantity',
                'weight',
                'dimensions',
                'seo_title',
                'seo_description'
            ]);
        });
    }
};
