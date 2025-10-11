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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // ترجم عربي/إنجليزي
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->json('currencies'); // ترجم عربي/إنجليزي
            $table->json('recipient_name')->nullable(); // ترجم عربي/إنجليزي
            $table->string('account_number')->nullable();
            $table->json('bank_name')->nullable(); // ترجم عربي/إنجليزي
            $table->string('transfer_number')->nullable();
            $table->json('instructions')->nullable(); // ترجم عربي/إنجليزي
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
