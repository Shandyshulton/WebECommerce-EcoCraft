<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id('id_shipments');
            $table->foreignId('order_id')->constrained('orders', 'id_orders')->cascadeOnDelete();
            // Satu pesanan bisa memuat produk dari beberapa pengrajin, sehingga
            // pengiriman dilacak per (order, seller), bukan per order.
            $table->foreignId('seller_id')->constrained('sellers', 'id_sellers')->cascadeOnDelete();
            $table->foreignId('courier_id')->nullable()->constrained('couriers', 'id_couriers')->nullOnDelete();
            $table->string('tracking_number', 60)->nullable();
            $table->enum('status', ['Pending', 'Packed', 'Shipped', 'In Transit', 'Delivered', 'Cancelled'])
                ->default('Pending');
            $table->text('note')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'seller_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
