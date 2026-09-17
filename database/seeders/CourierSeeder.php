<?php

namespace Database\Seeders;

use App\Models\Courier;
use App\Models\CourierUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CourierSeeder extends Seeder
{
    /**
     * Ekspedisi yang bisa dipilih pengrajin saat mengirim paket.
     *
     * tracking_url berisi halaman pelacakan resmi masing-masing ekspedisi.
     * Templat mendukung placeholder {resi}, tetapi ekspedisi yang diuji tidak
     * menerima nomor resi lewat URL (Anteraja mengabaikannya, SiCepat dan JNE
     * mengembalikan 404). Karena itu nomor resi ditampilkan terpisah dan
     * disalin otomatis saat pembeli menekan tombol lacak.
     */
    public function run(): void
    {
        $couriers = [
            ['code' => 'jne', 'name' => 'JNE', 'tracking_url' => 'https://www.jne.co.id', 'sort_order' => 1],
            ['code' => 'jnt', 'name' => 'J&T Express', 'tracking_url' => 'https://www.jet.co.id', 'sort_order' => 2],
            ['code' => 'sicepat', 'name' => 'SiCepat Ekspres', 'tracking_url' => 'https://www.sicepat.com', 'sort_order' => 3],
            ['code' => 'anteraja', 'name' => 'AnterAja', 'tracking_url' => 'https://anteraja.id', 'sort_order' => 4],
            ['code' => 'ninja', 'name' => 'Ninja Xpress', 'tracking_url' => 'https://www.ninjaxpress.co.id', 'sort_order' => 5],
            ['code' => 'pos', 'name' => 'POS Indonesia', 'tracking_url' => 'https://www.posindonesia.co.id', 'sort_order' => 6],
            ['code' => 'lion', 'name' => 'Lion Parcel', 'tracking_url' => 'https://lionparcel.com', 'sort_order' => 7],
            ['code' => 'ecocraft_local', 'name' => 'Kurir Lokal EcoCraft', 'tracking_url' => null, 'is_local_delivery' => true, 'sort_order' => 8],
        ];

        foreach ($couriers as $courier) {
            Courier::updateOrCreate(
                ['code' => $courier['code']],
                $courier + ['is_active' => true]
            );
        }

        // Akun petugas untuk Kurir Lokal EcoCraft, agar alur tugas bisa dicoba.
        $local = Courier::where('code', 'ecocraft_local')->first();

        if ($local) {
            CourierUser::updateOrCreate(
                ['email' => 'kurir@ecocraft.test'],
                [
                    'courier_id' => $local->id_couriers,
                    'name' => 'Bagas Kurir',
                    'phone_number' => '081200000099',
                    'password' => Hash::make('kurir12345'),
                    'is_active' => true,
                ]
            );
        }
    }
}
