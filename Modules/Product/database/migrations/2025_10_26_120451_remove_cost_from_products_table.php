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
        // إزالة عمود cost إذا كان موجوداً
        if (Schema::hasColumn('products', 'cost')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('cost');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة إضافة عمود cost إذا تم التراجع
        if (!Schema::hasColumn('products', 'cost')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('cost', 10, 2)->nullable()->after('original_price');
            });
        }
    }
};
