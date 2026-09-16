<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Klaim garansi pengrajin: satu baris mewakili satu klaim customer
     * atas satu item pesanan yang sudah diterima.
     */
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id('id_claims');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->enum('category', ['kerusakan', 'jahitan', 'tidak_sesuai', 'lainnya']);
            $table->text('description');
            $table->string('photo')->nullable();
            $table->enum('status', ['Submitted', 'Reviewing', 'Approved', 'Rejected', 'Completed'])->default('Submitted');
            $table->text('resolution')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id_orders')->on('orders')->cascadeOnDelete();
            $table->foreign('order_item_id')->references('id')->on('order_items')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id_customers')->on('customers')->cascadeOnDelete();
            $table->foreign('seller_id')->references('id_sellers')->on('sellers')->cascadeOnDelete();
            $table->foreign('product_id')->references('id_products')->on('products')->nullOnDelete();

            $table->index(['customer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
