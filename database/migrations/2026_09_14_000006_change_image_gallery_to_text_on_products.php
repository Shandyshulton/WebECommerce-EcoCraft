<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * image_gallery menyimpan JSON banyak path — ubah ke TEXT agar tidak terpotong 255 char.
     * Pakai ALTER mentah agar tidak butuh doctrine/dbal.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `products` MODIFY `image_gallery` TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `products` MODIFY `image_gallery` VARCHAR(255) NULL');
    }
};
