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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->json('name'); 
            $table->enum('type', ['traditional', 'digital', 'educational']);
            $table->string('domain')->unique(); // الدومين الخاص بالمتجر
            $table->json('description')->nullable(); // وصف المتجر
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending'); // حالة المتجر
            $table->string('theme')->default('default'); // الثيم المستخدم
            $table->json('settings')->nullable(); // إعدادات إضافية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
