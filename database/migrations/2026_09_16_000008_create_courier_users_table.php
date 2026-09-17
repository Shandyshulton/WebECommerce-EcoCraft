<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_users', function (Blueprint $table) {
            $table->id('id_courier_users');
            // Orangnya, bukan jasanya. Tabel `couriers` tetap menjadi referensi
            // ekspedisi; akun login berada di tabel terpisah agar satu jasa
            // kurir bisa memiliki banyak petugas.
            $table->foreignId('courier_id')->constrained('couriers', 'id_couriers')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('phone_number', 30)->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courier_users');
    }
};
