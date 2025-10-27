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
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete('cascade');
            $table->foreignId('theme_id')->nullable()->constrained()->nullOnDelete('cascade');

            $table->string('key');
            $table->string('value');

            $table->unique(['store_id', 'key']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
