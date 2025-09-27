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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->unsignedInteger('quantity')->default(1);

            // 🟢 خاص بالمنتجات الرقمية
            $table->string('player_id')->nullable();       // معرف اللاعب
            $table->string('delivery_email')->nullable(); // إيميل لو كود أو اشتراك
            $table->string('activation_code')->nullable(); // كود التفعيل لو المنتج بطاقة أو مفتاح

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
