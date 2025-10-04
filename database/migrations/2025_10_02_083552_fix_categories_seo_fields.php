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
        // First, clear all problematic data
        DB::table('categories')->update([
            'seo_title' => null,
            'seo_description' => null
        ]);

        // Change column types to string/text
        Schema::table('categories', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->change();
            $table->text('seo_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to JSON
        Schema::table('categories', function (Blueprint $table) {
            $table->json('seo_title')->nullable()->change();
            $table->json('seo_description')->nullable()->change();
        });
    }
};
