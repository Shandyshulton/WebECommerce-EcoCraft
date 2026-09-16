<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buku alamat pengiriman milik customer.
     */
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id('id_addresses');
            $table->unsignedBigInteger('customer_id');
            $table->string('label')->nullable();
            $table->string('recipient_name');
            $table->string('phone', 30);
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code', 20);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('customer_id')->references('id_customers')->on('customers')->cascadeOnDelete();
            $table->index(['customer_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
