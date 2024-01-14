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
        Schema::create('medicine_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->integer('amount');
            $table->date('final_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_warehouses');
    }
};
