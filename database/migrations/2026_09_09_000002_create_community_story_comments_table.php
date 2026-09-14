<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Diskusi/komentar member pada halaman detail cerita komunitas.
     */
    public function up(): void
    {
        Schema::create('community_story_comments', function (Blueprint $table) {
            $table->id('id_comments'); // Kolom id untuk komentar
            $table->unsignedBigInteger('story_id'); // Cerita yang dikomentari
            $table->unsignedBigInteger('customer_id')->nullable(); // Member penulis; null saat member dihapus
            $table->text('comment'); // Isi pertanyaan/komentar

            $table->foreign('story_id')->references('id_stories')->on('community_stories')->onDelete('cascade');
            $table->foreign('customer_id')->references('id_customers')->on('customers')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_story_comments');
    }
};
