<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Faktor dampak lingkungan per produk.
     * Default 1.2 kg limbah/item dan 2.7 kg CO₂/item agar produk lama tetap dihitung.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('waste_factor', 8, 2)->default(1.20)->after('material_type');
            $table->decimal('carbon_factor', 8, 2)->default(2.70)->after('waste_factor');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['waste_factor', 'carbon_factor']);
        });
    }
};
