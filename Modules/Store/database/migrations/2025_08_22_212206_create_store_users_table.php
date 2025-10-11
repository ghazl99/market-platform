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
        Schema::create('store_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade'); // معرف المتجر
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // معرف المستخدم
            $table->boolean('is_active')->default(true); // حالة النشاط
            $table->timestamps();

            // منع تكرار نفس المستخدم في نفس المتجر
            $table->unique(['store_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_users');
    }
};
