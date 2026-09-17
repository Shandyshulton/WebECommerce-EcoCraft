<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Diisi penerima saat mengonfirmasi paket tiba.
            $table->string('receiver_name', 100)->nullable()->after('tracking_number');
            $table->string('proof_photo')->nullable()->after('receiver_name');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['receiver_name', 'proof_photo']);
        });
    }
};
