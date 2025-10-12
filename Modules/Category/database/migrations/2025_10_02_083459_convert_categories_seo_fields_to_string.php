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
        // Convert JSON data back to string (keep Arabic values)
        $categories = DB::table('categories')->whereNotNull('seo_title')->get();
        foreach ($categories as $category) {
            if ($category->seo_title) {
                $seoTitleData = json_decode($category->seo_title, true);
                $seoTitleString = is_array($seoTitleData) ? ($seoTitleData['ar'] ?? '') : $category->seo_title;
                DB::table('categories')->where('id', $category->id)->update(['seo_title' => $seoTitleString]);
            }
        }

        $categories = DB::table('categories')->whereNotNull('seo_description')->get();
        foreach ($categories as $category) {
            if ($category->seo_description) {
                $seoDescriptionData = json_decode($category->seo_description, true);
                $seoDescriptionString = is_array($seoDescriptionData) ? ($seoDescriptionData['ar'] ?? '') : $category->seo_description;
                DB::table('categories')->where('id', $category->id)->update(['seo_description' => $seoDescriptionString]);
            }
        }

        // Change column types back to string/text
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
        // Convert string back to JSON format
        $categories = DB::table('categories')->whereNotNull('seo_title')->get();
        foreach ($categories as $category) {
            if ($category->seo_title && !is_array(json_decode($category->seo_title, true))) {
                $seoTitleJson = json_encode(['ar' => $category->seo_title, 'en' => '']);
                DB::table('categories')->where('id', $category->id)->update(['seo_title' => $seoTitleJson]);
            }
        }

        $categories = DB::table('categories')->whereNotNull('seo_description')->get();
        foreach ($categories as $category) {
            if ($category->seo_description && !is_array(json_decode($category->seo_description, true))) {
                $seoDescriptionJson = json_encode(['ar' => $category->seo_description, 'en' => '']);
                DB::table('categories')->where('id', $category->id)->update(['seo_description' => $seoDescriptionJson]);
            }
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->json('seo_title')->nullable()->change();
            $table->json('seo_description')->nullable()->change();
        });
    }
};
