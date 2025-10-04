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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->enum('type', ['deposit', 'withdraw'])->default('deposit');
            $table->decimal('amount', 12, 2);
            $table->string('reference_type')->nullable();  // model type (PaymentRequest, Order, ...)
            $table->unsignedBigInteger('reference_id')->nullable(); // id المرجع
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
