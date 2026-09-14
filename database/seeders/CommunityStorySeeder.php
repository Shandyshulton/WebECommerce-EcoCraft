<?php

namespace Database\Seeders;

use App\Models\CommunityStory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommunityStorySeeder extends Seeder
{
    /**
     * Isi awal cerita komunitas. Teks dan gambar sama dengan konten
     * yang sebelumnya di-hardcode di view, kini dipetakan ke baris
     * section berdasarkan topic. body berisi kisah lengkap untuk
     * halaman detail, material adalah kata kunci produk terkait.
     */
    public function run()
    {
        $stories = [
            [
                'title' => 'Sanggar Kayu Resik, Jepara',
                'label' => 'Kayu & Mebel',
                'excerpt' => 'Potongan mebel diolah menjadi piranti meja yang bernilai dan memberdayakan pengrajin pesisir.',
                'body' => "Berawal dari keprihatinan atas potongan kayu yang terbuang di bengkel-bengkel mebel Jepara, Sanggar Kayu Resik mengumpulkan sisa produksi itu menjadi bahan baku baru. Setiap potongan dipilah berdasarkan serat dan kekuatannya, lalu dirancang ulang menjadi piranti meja yang utuh secara visual maupun struktural.\n\nDi sanggar ini, pengrajin pesisir dilatih membaca karakter kayu sebelum mengolahnya. Hasilnya bukan sekadar furnitur — setiap meja membawa cerita kayu yang diberi kesempatan kedua, sekaligus menjadi sumber penghidupan bagi perajin lokal Jepara.",
                'image' => 'assets/images/collection/banner welcome.png',
                'topic' => 'Di Balik Proses',
                'material' => 'kayu',
            ],
            [
                'title' => 'Anyaman Lestari, Tasikmalaya',
                'label' => 'Anyaman',
                'excerpt' => 'Serat alami dipintal dengan teknik tradisional untuk menghadirkan koleksi rumah yang hangat.',
                'body' => "Di Tasikmalaya, Anyaman Lestari menjaga teknik anyam tradisional tetap hidup. Serat alami seperti mendong dan pandan dipintal serta dianyam dengan pola warisan turun-temurun, menghasilkan koleksi rumah yang hangat dan berkarakter.\n\nSetiap helai serat melewati proses perendaman dan penjemuran alami sebelum dianyam. Pengerjaan sepenuhnya manual membuat setiap produk sedikit berbeda — ciri khas yang justru menjadi nilainya.",
                'image' => 'assets/images/collection/arrivals1.png',
                'topic' => 'Di Balik Proses',
                'material' => 'anyaman',
            ],
            [
                'title' => 'Rumah Rotan, Cirebon',
                'label' => 'Rotan',
                'excerpt' => 'Material rotan pilihan dirawat menjadi dekorasi sederhana untuk ruang dengan karakter.',
                'body' => "Cirebon lama dikenal sebagai pusat rotan Nusantara, dan Rumah Rotan meneruskan tradisi itu dengan pendekatan yang lebih sadar lingkungan. Rotan pilihan dirawat, dikeringkan secara alami, lalu dianyam menjadi dekorasi sederhana untuk ruang berkarakter.\n\nRotan adalah material yang tumbuh cepat dan ramah lingkungan. Dengan perawatan yang tepat, produk Rumah Rotan awet digunakan bertahun-tahun dan mudah diperbaiki — bentuk lain dari keberlanjutan.",
                'image' => 'assets/images/collection/arrivals2.png',
                'topic' => 'Bahan Berkelanjutan',
                'material' => 'rotan',
            ],
            [
                'title' => 'Kain Pesisir, Pekalongan',
                'label' => 'Tekstil',
                'excerpt' => 'Sisa kain produksi dipadukan kembali menjadi aksesori yang lebih tahan lama.',
                'body' => "Kain Pesisir lahir dari sisa kain produksi batik dan tenun Pekalongan yang semula berakhir di tumpukan limbah. Kain-kain bermotif itu dipilah berdasarkan warna dan teksturnya, lalu dipadukan kembali menjadi aksesori baru yang lebih tahan lama.\n\nPerajin di baliknya bekerja dengan palet terbatas dari sisa produksi, sehingga tidak ada dua aksesori yang benar-benar sama. Pendekatan ini menekan limbah tekstil sekaligus merayakan motif pesisir yang kaya.",
                'image' => 'assets/images/collection/arrivals3.png',
                'topic' => 'Kriya Nusantara',
                'material' => 'tekstil',
            ],
            [
                'title' => 'Bambu Bumi, Yogyakarta',
                'label' => 'Bambu',
                'excerpt' => 'Peralatan harian dari bambu tumbuh cepat dengan proses produksi yang lebih ringan.',
                'body' => "Bambu Bumi mengolah bambu tumbuh cepat dari kaki Gunung Merapi menjadi peralatan harian yang ringan dan kuat. Prosesnya mengutamakan pengeringan bertahap dan perlakuan alami agar bambu tahan lama tanpa bahan kimia berlebih.\n\nPemanenan bambu yang bijak — memilih batang tua dan menyisakan rumpun muda — membuat sanggar ini tetap lestari. Produknya membuktikan bahwa peralatan sederhana bisa dibuat dengan proses yang lebih ringan bagi bumi.",
                'image' => 'assets/images/collection/arrivals4.png',
                'topic' => 'Di Balik Proses',
                'material' => 'bambu',
            ],
            [
                'title' => 'Kriya Tanah, Kasongan',
                'label' => 'Keramik',
                'excerpt' => 'Tanah liat lokal dibentuk menjadi benda pakai yang tenang, kuat, dan mudah dirawat.',
                'body' => "Kasongan telah lama menjadi kampung perajin tanah liat. Kriya Tanah mengambil lempung lokal, membentuknya dengan tangan, lalu membakarnya dalam tungku sederhana menjadi benda pakai yang tenang, kuat, dan mudah dirawat.\n\nGlazur yang digunakan berasal dari campuran abu dan tanah lokal, memberi warna alami yang hangat. Dari cangkir hingga wadah penyimpanan, setiap karya adalah hasil dialog antara tangan perajin dan material setempat.",
                'image' => 'assets/images/collection/arrivals5.png',
                'topic' => 'Bahan Berkelanjutan',
                'material' => 'keramik',
            ],
            [
                'title' => 'Daur Ulang Kota, Bandung',
                'label' => 'Daur Ulang',
                'excerpt' => 'Bahan kemasan bekas dipilah dan dirakit menjadi produk kecil yang fungsional.',
                'body' => "Daur Ulang Kota mengubah bahan kemasan bekas dari rumah-rumah di Bandung menjadi produk kecil yang fungsional. Kertas, karton, dan plastik kemasan dipilah, dibersihkan, lalu dirakit menjadi barang yang benar-benar dipakai sehari-hari.\n\nKomunitas ini bekerja sama dengan bank sampah lingkungan sekitar untuk memasok bahan baku, sehingga alurnya ikut mendorong budaya memilah dari sumbernya. Setiap produk jadi membawa cerita tentang sampah yang diberi nilai baru.",
                'image' => 'assets/images/collection/arrivals6.png',
                'topic' => 'Daur Ulang',
                'material' => 'daur ulang',
            ],
            [
                'title' => 'Kayu Pulih, Surabaya',
                'label' => 'Daur Ulang',
                'excerpt' => 'Kayu lama diberi kesempatan kedua melalui bentuk baru yang cocok untuk rumah modern.',
                'body' => "Kayu Pulih mengumpulkan kayu bekas — palet, bongkaran rumah, dan limbah pertukangan dari Surabaya — lalu memberinya kesempatan kedua lewat bentuk baru yang cocok untuk rumah modern.\n\nSetiap batang dibersihkan, disortir, dan diolah ulang tanpa menghilangkan karakter lamanya. Bekas paku dan serat yang menua justru menjadi jejak yang membuat setiap karyanya unik.",
                'image' => 'assets/images/collection/arrivals7.png',
                'topic' => 'Daur Ulang',
                'material' => 'kayu',
            ],
        ];

        foreach ($stories as $index => $story) {
            CommunityStory::updateOrCreate(
                ['title' => $story['title']],
                array_merge($story, [
                    'slug' => Str::slug($story['title']),
                    'featured' => true,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ])
            );
        }
    }
}
