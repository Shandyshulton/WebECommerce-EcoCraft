<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_tracking_events', function (Blueprint $table) {
            $table->id('id_events');
            $table->foreignId('shipment_id')->constrained('shipments', 'id_shipments')->cascadeOnDelete();
            $table->string('status', 30);
            $table->string('description');
            $table->string('location', 120)->nullable();
            // Siapa yang mencatat peristiwa ini. 'courier' disediakan agar akun
            // kurir bisa ditambahkan nanti tanpa mengubah skema.
            $table->enum('source', ['system', 'seller', 'admin', 'courier'])->default('system');
            $table->timestamp('happened_at');
            $table->timestamps();

            $table->index(['shipment_id', 'happened_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_tracking_events');
    }
};
