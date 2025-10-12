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
        // First, update any existing data to match the new enum values
        DB::statement("UPDATE orders SET status = 'confirmed' WHERE status = 'processing'");
        DB::statement("UPDATE orders SET status = 'canceled' WHERE status = 'cancelled'");

        // Then modify the column to have the correct enum values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'completed', 'canceled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the old enum values if needed
        DB::statement("UPDATE orders SET status = 'processing' WHERE status = 'confirmed'");
        DB::statement("UPDATE orders SET status = 'cancelled' WHERE status = 'canceled'");

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
