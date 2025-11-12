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
            // Check if column doesn't exist before adding it
            if (!Schema::hasColumn('products', 'linking_type')) {
                $table->enum('linking_type', ['automatic', 'manual'])->default('automatic')->after('product_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'linking_type')) {
                $table->dropColumn('linking_type');
            }
        });
    }
};
