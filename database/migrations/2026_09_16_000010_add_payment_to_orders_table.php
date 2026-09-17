<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Pembayaran dicatat terpisah dari status pengiriman: sebuah pesanan
            // bisa sudah dibayar tapi belum dikirim, atau sebaliknya untuk COD.
            $table->enum('payment_status', ['Unpaid', 'Paid'])->default('Unpaid')->after('payment_method');
            $table->string('virtual_account', 30)->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('virtual_account');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'virtual_account', 'paid_at']);
        });
    }
};
