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
        Schema::create('today_meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->constrained()->cascadeOnDelete();
            $table->foreignId('food_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('today_meals');
    }
};
