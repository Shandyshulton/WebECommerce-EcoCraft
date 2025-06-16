<?php

// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Muhammad Rafly', // Nama admin
            'email' => 'rafly@gmail.com', // Email admin
            'phone_number' => '081290983455', // Nomor telepon admin
            'password' => Hash::make('rafly123'), // Password yang di-hash
            'address' => 'Jl. Mesjid II Street No. 1', // Alamat admin
            'gender' => 'male', // Gender admin (male, female, or other)
        ]);
    }
}

