<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id('id_sellers');
            $table->string('name_sellers');
            $table->string('email');
            $table->string('phone_number');
            $table->string('password');
            $table->text('address');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('province');
            $table->string('city');
            $table->string('store_name');
            $table->string('ktp_image');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sellers');
    }
}
