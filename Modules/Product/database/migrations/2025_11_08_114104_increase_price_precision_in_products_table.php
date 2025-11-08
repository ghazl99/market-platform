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
            // تغيير عمود price من decimal(10,2) إلى decimal(20,10) لدعم دقة أعلى
            $table->decimal('price', 20, 10)->change();
            
            // تغيير عمود original_price من decimal(10,2) إلى decimal(20,10) لدعم دقة أعلى
            $table->decimal('original_price', 20, 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // إرجاع الأعمدة إلى القيم الأصلية
            $table->decimal('price', 10, 2)->change();
            $table->decimal('original_price', 10, 2)->nullable()->change();
        });
    }
};
