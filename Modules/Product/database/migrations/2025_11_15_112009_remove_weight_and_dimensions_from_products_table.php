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
            // Check if columns exist before dropping
            if (Schema::hasColumn('products', 'weight')) {
                $table->dropColumn('weight');
            }
            if (Schema::hasColumn('products', 'dimensions')) {
                $table->dropColumn('dimensions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('weight')->nullable()->after('stock_quantity');
            $table->string('dimensions')->nullable()->after('weight');
        });
    }
};
