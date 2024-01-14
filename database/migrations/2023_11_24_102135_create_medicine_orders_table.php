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
        Schema::create('medicine_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained();
            $table->foreignId('order_id')->constrained();
            $table->integer('medicine_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_orders');
    }
};
