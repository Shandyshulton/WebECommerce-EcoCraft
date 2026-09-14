<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Kolom pendukung halaman detail cerita komunitas.
     */
    public function up(): void
    {
        Schema::table('community_stories', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title'); // Slug URL cerita
            $table->longText('body')->nullable()->after('excerpt'); // Kisah lengkap cerita
            $table->string('material')->nullable()->after('topic'); // Kata kunci bahan untuk produk terkait
        });
    }

    public function down(): void
    {
        Schema::table('community_stories', function (Blueprint $table) {
            $table->dropColumn(['slug', 'body', 'material']);
        });
    }
};
