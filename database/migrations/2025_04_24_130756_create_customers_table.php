<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id_customers');
            $table->string('name_customers');
            $table->string('email');
            $table->string('phone_number');
            $table->date('dob');               // Tambahan kolom DOB (date)
            $table->enum('gender', ['male', 'female']);
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('password');
            $table->string('profile_image')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
