<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers', 'id_customers')->cascadeOnDelete();
            $table->enum('source', ['claim', 'reward'])->default('claim');
            $table->enum('status', ['available', 'used'])->default('available');
            $table->foreignId('order_id')->nullable()->constrained('orders', 'id_orders')->nullOnDelete();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_vouchers');
    }
};
