<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tabel cerita komunitas (sorotan pengrajin) yang ditampilkan
     * di halaman /community sebagai baris bergaya Netflix.
     */
    public function up(): void
    {
        Schema::create('community_stories', function (Blueprint $table) {
            $table->id('id_stories'); // Kolom id untuk tabel cerita komunitas
            $table->string('title'); // Judul cerita, mis. "Sanggar Kayu Resik, Jepara"
            $table->string('label')->nullable(); // Badge kecil opsional, mis. "Kayu & Mebel"
            $table->text('excerpt'); // Deskripsi singkat cerita
            $table->string('image')->nullable(); // Path gambar siap asset(), mis. assets/images/collection/arrivals1.png
            $table->string('topic')->nullable(); // Nama section/baris tema; null berarti hanya di Sorotan
            $table->boolean('featured')->default(false); // Masuk baris "Sorotan Komunitas"
            $table->boolean('is_active')->default(true); // Status tampil
            $table->integer('sort_order')->default(0); // Urutan kartu dalam baris
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_stories');
    }
};
