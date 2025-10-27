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
        // إزالة عمود sale_price إذا كان موجوداً
        if (Schema::hasColumn('products', 'sale_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('sale_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة إضافة عمود sale_price إذا تم التراجع
        if (!Schema::hasColumn('products', 'sale_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            });
        }
    }
};
