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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status' , ['pending','in_progress','on_its_way','completed']);
            $table->foreignId('user_id')->constrained();
            $table->date('date');
            $table->boolean('paid');
            $table->integer('total_price');
            $table->json('admins_read')->nullable(); // for notification
            $table->json('amount_medicin')->nullable(); // for delete and update
            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
