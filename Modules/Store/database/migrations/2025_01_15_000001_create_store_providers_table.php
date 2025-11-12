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
        Schema::create('store_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade'); // معرف المتجر
            $table->foreignId('provider_id')->constrained('providers')->onDelete('cascade'); // معرف المزود
            $table->boolean('is_active')->default(true); // حالة الربط (تفعيل/تعطيل)
            $table->timestamps();

            // منع تكرار نفس المزود في نفس المتجر
            $table->unique(['store_id', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_providers');
    }
};

