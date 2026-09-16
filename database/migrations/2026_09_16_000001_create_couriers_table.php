<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id('id_couriers');
            $table->string('code', 40)->unique();
            $table->string('name', 120);
            // Templat tautan pelacakan. Gunakan placeholder {resi} bila pola
            // deep link kurir sudah dipastikan; kalau tidak, isi URL halaman lacak.
            $table->string('tracking_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
