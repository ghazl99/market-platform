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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon')->default('fas fa-tag')->after('description');
            $table->boolean('is_active')->default(true)->after('icon');
            $table->integer('sort_order')->default(0)->after('is_active');
            $table->json('seo_title')->nullable()->after('sort_order');
            $table->text('keywords')->nullable()->after('seo_title');
            $table->json('seo_description')->nullable()->after('keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['icon', 'is_active', 'sort_order', 'seo_title', 'keywords', 'seo_description']);
        });
    }
};
