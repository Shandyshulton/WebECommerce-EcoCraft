<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_products'); // Kolom id untuk tabel produk
            $table->unsignedBigInteger('seller_id'); // Kolom seller_id untuk menghubungkan produk dengan seller

            // Membuat foreign key yang merujuk ke id_sellers di tabel sellers
            $table->foreign('seller_id')->references('id_sellers')->on('sellers')->onDelete('cascade');

            $table->string('name'); // Nama produk
            $table->string('slug')->unique(); // Slug produk (URL-friendly name)
            $table->text('description')->nullable(); // Deskripsi produk
            $table->decimal('price', 15, 2); // Harga produk
            $table->string('category'); // Kategori produk
            $table->string('material_type'); // Jenis bahan produk
            $table->boolean('in_stock')->default(true); // Status stok produk
            $table->boolean('is_active')->default(true); // Status aktif produk
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('image_url')->nullable(); // Gambar utama produk
            $table->string('image_gallery')->nullable(); // Galeri gambar produk
            $table->integer('quantity')->default(0); // Jumlah stok produk
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
