<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            // Menandai pengiriman yang kita kendalikan sendiri. Hanya untuk kurir ini
            // posisi paket benar-benar diketahui pengrajin, sehingga titik perjalanan
            // boleh dicatat manual. Ekspedisi pihak ketiga tidak.
            $table->boolean('is_local_delivery')->default(false)->after('tracking_url');
        });
    }

    public function down(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropColumn('is_local_delivery');
        });
    }
};
