<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Pesan individual dalam sebuah thread pertanyaan produk.
     * sender_type membedakan pesan dari customer atau admin.
     */
    public function up(): void
    {
        Schema::create('product_inquiry_messages', function (Blueprint $table) {
            $table->id('id_messages');
            $table->unsignedBigInteger('inquiry_id'); // Thread pemilik pesan
            $table->enum('sender_type', ['customer', 'seller']); // Pengirim
            $table->unsignedBigInteger('sender_id')->nullable(); // id_customers / id_sellers
            $table->text('body'); // Isi pesan
            $table->timestamp('read_at')->nullable(); // Kapan dibaca lawan bicara
            $table->timestamps();

            $table->foreign('inquiry_id')->references('id_inquiries')->on('product_inquiries')->onDelete('cascade');
            $table->index(['inquiry_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_inquiry_messages');
    }
};
