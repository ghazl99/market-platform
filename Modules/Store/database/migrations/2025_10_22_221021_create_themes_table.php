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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // اسم الثيم (مثل classic / modern / default)

            $table->timestamps();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('theme'); // نحذف العمود القديم
            $table->foreignId('theme_id')->nullable()->constrained('themes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
            $table->dropColumn('theme_id');
            $table->string('theme')->default('default'); // نرجع العمود القديم إذا تم الرجوع بالهجرة
        });

        Schema::dropIfExists('themes');
    }
};
