<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('customer_voucher_id')->nullable()->after('customer_id')
                ->constrained('customer_vouchers')->nullOnDelete();
            $table->string('coupon_code')->nullable()->after('customer_voucher_id');
            $table->decimal('voucher_discount', 15, 2)->default(0)->after('subtotal');
            $table->unsignedInteger('coins_used')->default(0)->after('voucher_discount');
            $table->decimal('coin_discount', 15, 2)->default(0)->after('coins_used');
            $table->decimal('discount_total', 15, 2)->default(0)->after('coin_discount');
            $table->unsignedInteger('coins_earned')->default(0)->after('discount_total');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_voucher_id');
            $table->dropColumn(['coupon_code', 'voucher_discount', 'coins_used', 'coin_discount', 'discount_total', 'coins_earned']);
        });
    }
};
