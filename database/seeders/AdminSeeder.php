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
        // Super Admin (akses penuh, termasuk kelola akun admin)
        Admin::updateOrCreate(['email' => 'rafly@gmail.com'], [
            'name' => 'Muhammad Rafly',
            'role' => 'super_admin',
            'phone_number' => '081290983455',
            'password' => Hash::make('rafly123'),
            'address' => 'Jl. Mesjid II Street No. 1',
            'gender' => 'male',
        ]);

        // Admin biasa (contoh — verifikasi seller/produk saja)
        Admin::updateOrCreate(['email' => 'admin@ecocraft.test'], [
            'name' => 'Admin EcoCraft',
            'role' => 'admin',
            'phone_number' => '081200000000',
            'password' => Hash::make('admin123'),
            'address' => 'Kantor EcoCraft',
            'gender' => 'other',
        ]);
    }
}
