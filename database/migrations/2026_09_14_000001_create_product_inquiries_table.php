<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Thread pertanyaan produk antara customer dan admin.
     * Satu thread mewakili percakapan tentang satu produk oleh satu customer.
     */
    public function up(): void
    {
        Schema::create('product_inquiries', function (Blueprint $table) {
            $table->id('id_inquiries');
            $table->unsignedBigInteger('product_id')->nullable(); // Produk yang ditanyakan
            $table->unsignedBigInteger('seller_id')->nullable(); // Seller pemilik produk yang menjawab
            $table->unsignedBigInteger('customer_id')->nullable(); // Customer penanya
            $table->string('subject')->nullable(); // Ringkasan/nama produk saat dibuat
            $table->timestamp('last_message_at')->nullable(); // Untuk sorting inbox
            $table->timestamps();

            $table->foreign('product_id')->references('id_products')->on('products')->onDelete('set null');
            $table->foreign('seller_id')->references('id_sellers')->on('sellers')->onDelete('cascade');
            $table->foreign('customer_id')->references('id_customers')->on('customers')->onDelete('cascade');
            $table->index(['seller_id', 'customer_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_inquiries');
    }
};
