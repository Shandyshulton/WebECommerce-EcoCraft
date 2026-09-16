<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Ekspedisi yang bisa dipilih pengrajin saat mengirim paket.
     *
     * tracking_url berisi halaman pelacakan resmi masing-masing ekspedisi.
     * Templat mendukung placeholder {resi} untuk deep link, tetapi polanya
     * belum dipastikan per ekspedisi sehingga sementara memakai halaman lacak
     * dan nomor resi ditampilkan terpisah agar bisa disalin pembeli.
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
            ['code' => 'ecocraft_local', 'name' => 'Kurir Lokal EcoCraft', 'tracking_url' => null, 'sort_order' => 8],
        ];

        foreach ($couriers as $courier) {
            Courier::updateOrCreate(
                ['code' => $courier['code']],
                $courier + ['is_active' => true]
            );
        }
    }
}
