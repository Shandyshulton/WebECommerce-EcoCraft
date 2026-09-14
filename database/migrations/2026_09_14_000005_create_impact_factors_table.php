<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('impact_factors', function (Blueprint $table) {
            $table->id();
            $table->string('material_type')->unique();
            $table->decimal('waste_per_item', 8, 2)->default(1.20);
            $table->decimal('carbon_per_item', 8, 2)->default(2.70);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impact_factors');
    }
};
