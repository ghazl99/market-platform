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
            // Add generated column for name in current locale
            $table->string('name_ar')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(name, "$.ar"))');
            $table->string('name_en')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(name, "$.en"))');
        });

        // Add unique constraint after creating virtual columns
        Schema::table('products', function (Blueprint $table) {
            $table->unique(['name_ar', 'store_id'], 'products_name_ar_store_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_name_ar_store_unique');
            $table->dropColumn(['name_ar', 'name_en']);
        });
    }
};
