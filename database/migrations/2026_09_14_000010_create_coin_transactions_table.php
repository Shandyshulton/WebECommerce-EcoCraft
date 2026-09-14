<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers', 'id_customers')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders', 'id_orders')->nullOnDelete();
            $table->enum('type', ['earn', 'redeem']);
            $table->integer('amount');
            $table->integer('balance_after');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};
