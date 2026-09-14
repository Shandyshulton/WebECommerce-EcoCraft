<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_orders');
            $table->foreignId('customer_id')->nullable()->constrained('customers', 'id_customers')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_province');
            $table->string('shipping_postal_code', 20);
            $table->enum('shipping_method', ['Reguler', 'Express', 'Sameday']);
            $table->enum('payment_method', ['COD', 'Transfer Bank', 'QRIS']);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total', 15, 2);
            $table->enum('status', ['Hold', 'Processing', 'Shipped', 'Delivered', 'Cancelled'])->default('Hold');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
