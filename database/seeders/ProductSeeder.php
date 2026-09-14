<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Contoh produk agar katalog dan rekomendasi komunitas terlihat
     * penuh saat pengembangan. Idempotent: updateOrCreate per slug.
     */
    public function run()
    {
        $seller = Seller::where('status', 'approved')->first();

        if (!$seller) {
            $seller = Seller::create([
                'name_sellers' => 'Demo Pengrajin EcoCraft',
                'email' => 'demo.pengrajin@ecocraft.test',
                'phone_number' => '081200000000',
                'password' => bcrypt('demo12345'),
                'address' => 'Jl. Contoh No. 1',
                'gender' => 'male',
                'province' => 'Jawa Tengah',
                'city' => 'Jepara',
                'store_name' => 'Demo Pengrajin EcoCraft',
                'ktp_image' => '',
                'status' => 'approved',
            ]);
        }

        // [nama, deskripsi, harga, kategori, material, file gambar koleksi]
        $products = [
            ['Meja Kopi Kayu Resik', 'Meja kopi dari potongan kayu jati pilihan, dirakit tangan oleh pengrajin Jepara.', 875000, 'Furniture', 'kayu', 'table-banner.jpg'],
            ['Rak Dinding Rotan Cirebon', 'Rak dinding anyaman rotan kering alami, cocok untuk dekorasi ruang tamu berkarakter.', 345000, 'Home Decor', 'rotan', 'arrivals2.png'],
            ['Tas Anyaman Mendong', 'Tas tangan anyaman serat mendong yang ringan, kuat, dan hangat dipandang.', 189000, 'Clothing & Accessories', 'anyaman', 'arrivals1.png'],
            ['Lampu Gantung Bambu', 'Lampu gantung dari bambu pilihan dengan pola anyaman terbuka yang hangat.', 420000, 'Home Decor', 'bambu', 'arrivals4.png'],
            ['Vas Bunga Tanah Liat', 'Vas keramik tanah liat Kasongan dengan glazur alami dari abu dan tanah lokal.', 185000, 'Home Decor', 'keramik', 'arrivals5.png'],
            ['Taplak Tenun Pesisir', 'Taplak dari sisa kain tenun Pekalongan, dijahit ulang menjadi tekstil rumah yang tahan lama.', 260000, 'Home Decor', 'tekstil', 'arrivals3.png'],
            ['Sofa Kayu Pulih', 'Sofa santai dari kayu bekas yang dipulihkan, dengan bantal tenun yang dapat dilepas.', 2450000, 'Furniture', 'kayu', 'sofa-collection-banner.jpg'],
            ['Lampu Sendok Plastik Daur Ulang', 'Lampu meja dari sendok plastik bekas yang dipilah dan dirakit menjadi karya artistik.', 275000, 'Home Decor', 'daur ulang', 'plastic-spoon-lamp.jpg'],
            ['Tas Kemasan Kopi Daur Ulang', 'Tas jinjing dari kemasan kopi bekas yang dibersihkan dan dijahit ulang.', 155000, 'Clothing & Accessories', 'daur ulang', 'Tas Kemasan Kopi.jpg'],
            ['Hiasan Perahu Botol', 'Hiasan kapal dari botol plastik bekas, hadiah ramah lingkungan yang penuh cerita.', 95000, 'Toys', 'daur ulang', 'perahu-botol.png'],
        ];

        $targetDir = storage_path('app/public/product_images');
        File::ensureDirectoryExists($targetDir);

        foreach ($products as $index => [$name, $description, $price, $category, $material, $file]) {
            $slug = Str::slug($name);
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $stored = $slug . '.' . $ext;

            $source = public_path('assets/images/collection/' . $file);
            $target = $targetDir . DIRECTORY_SEPARATOR . $stored;
            if (File::exists($source) && !File::exists($target)) {
                File::copy($source, $target);
            }

            Product::updateOrCreate(['slug' => $slug], [
                'seller_id' => $seller->id_sellers,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category' => $category,
                'material_type' => $material,
                'in_stock' => true,
                'is_active' => true,
                'status' => 'approved',
                'image_url' => 'product_images/' . $stored,
                'quantity' => 8 + $index,
            ]);
        }
    }
}
