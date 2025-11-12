<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحديث enum لإضافة 'processing' إلى القيم المسموحة
        // القيم الحالية: 'pending', 'confirmed', 'completed', 'canceled'
        // القيم الجديدة: 'pending', 'processing', 'confirmed', 'completed', 'canceled', 'cancelled'

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'confirmed', 'completed', 'canceled', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع enum إلى القيم السابقة (بدون 'processing')
        // تحويل 'processing' إلى 'confirmed' قبل إزالتها
        DB::statement("UPDATE orders SET status = 'confirmed' WHERE status = 'processing'");

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'completed', 'canceled', 'cancelled') DEFAULT 'pending'");
    }
};
