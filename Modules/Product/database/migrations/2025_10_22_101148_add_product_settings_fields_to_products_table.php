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
            $table->enum('product_type', ['transfer', 'code'])->default('transfer')->after('status');
            $table->enum('linking_type', ['automatic', 'manual'])->default('automatic')->after('product_type');
            $table->text('notes')->nullable()->after('linking_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_type', 'linking_type', 'notes']);
        });
    }
};
