<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Petugas yang mengambil dan mengantar paket ini.
            $table->foreignId('courier_user_id')->nullable()->after('courier_id')
                ->constrained('courier_users', 'id_courier_users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('courier_user_id');
        });
    }
};
