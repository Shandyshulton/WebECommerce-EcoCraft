<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dokumen kebijakan
    |--------------------------------------------------------------------------
    |
    | Konten halaman Kebijakan Privasi dan Ketentuan Layanan. Setiap dokumen
    | punya judul, tanggal pembaruan, ringkasan, dan daftar bagian. Tiap
    | bagian boleh punya paragraf dan/atau poin daftar.
    |
    */

    'privacy' => [
        'eyebrow' => 'Kebijakan',
        'title' => 'Kebijakan Privasi',
        'updated' => '15 September 2026',
        'lead' => 'Kebijakan ini menjelaskan data apa yang kami kumpulkan saat kamu menggunakan EcoCraft, bagaimana data itu dipakai, dan pilihan yang kamu miliki atasnya.',
        'sections' => [
            [
                'id' => 'data-yang-dikumpulkan',
                'heading' => 'Data yang kami kumpulkan',
                'paragraphs' => [
                    'Kami hanya mengumpulkan data yang diperlukan untuk menjalankan layanan marketplace EcoCraft.',
                ],
                'bullets' => [
                    'Data akun: nama, email, nomor WhatsApp, tanggal lahir, jenis kelamin, alamat, provinsi, dan kota.',
                    'Data transaksi: produk yang dibeli, jumlah, metode pengiriman, dan status pesanan.',
                    'Data seller: nama toko, kontak, dan informasi usaha yang kamu daftarkan.',
                    'Konten yang kamu kirim: komentar cerita komunitas dan pertanyaan produk ke seller.',
                    'Data teknis dasar: sesi login dan isi keranjang yang disimpan di perangkatmu.',
                ],
            ],
            [
                'id' => 'penggunaan-data',
                'heading' => 'Bagaimana data digunakan',
                'bullets' => [
                    'Memproses pesanan, menghitung total, dan mengatur pengiriman.',
                    'Menghubungkan kamu dengan seller dan menjawab pertanyaan produk.',
                    'Menampilkan rekomendasi katalog dan riwayat pembelianmu.',
                    'Menjalankan program koin sirkular dan voucher yang kamu miliki.',
                    'Menjaga keamanan akun serta mencegah penyalahgunaan.',
                ],
            ],
            [
                'id' => 'berbagi-data',
                'heading' => 'Berbagi data dengan pihak lain',
                'paragraphs' => [
                    'Kami tidak menjual data pribadimu. Data dibagikan hanya sebatas keperluan layanan:',
                ],
                'bullets' => [
                    'Seller terkait, untuk memproses dan mengirim pesananmu.',
                    'Jasa pengiriman, untuk mengantarkan paket ke alamatmu.',
                    'Penyedia layanan pembayaran, untuk memproses transaksi.',
                    'Pihak berwenang, apabila diwajibkan oleh hukum yang berlaku.',
                ],
            ],
            [
                'id' => 'cookie-sesi',
                'heading' => 'Cookie dan penyimpanan sesi',
                'paragraphs' => [
                    'EcoCraft memakai cookie dan penyimpanan lokal secukupnya untuk menjaga sesi login dan mengingat isi keranjang. Menonaktifkan cookie dapat membuat sebagian fitur tidak berjalan.',
                ],
            ],
            [
                'id' => 'keamanan',
                'heading' => 'Keamanan data',
                'paragraphs' => [
                    'Kata sandi disimpan dalam bentuk ter-enkripsi dan tidak pernah ditampilkan kembali. Akses ke data pelanggan dibatasi hanya untuk pengelola yang berwenang.',
                ],
            ],
            [
                'id' => 'hak-kamu',
                'heading' => 'Hak kamu',
                'bullets' => [
                    'Memperbarui data profil kapan saja melalui halaman Akun.',
                    'Meminta salinan atau penghapusan data dengan menghubungi admin EcoCraft.',
                    'Berhenti menggunakan layanan dan keluar dari akun kapan pun.',
                ],
            ],
            [
                'id' => 'perubahan',
                'heading' => 'Perubahan kebijakan',
                'paragraphs' => [
                    'Kebijakan ini dapat diperbarui sewaktu-waktu. Versi terbaru selalu ditampilkan di halaman ini beserta tanggal pembaruannya.',
                ],
            ],
        ],
    ],

    'terms' => [
        'eyebrow' => 'Kebijakan',
        'title' => 'Ketentuan Layanan',
        'updated' => '15 September 2026',
        'lead' => 'Dengan menggunakan EcoCraft, kamu menyetujui ketentuan berikut. Mohon dibaca sebelum bertransaksi atau mendaftarkan toko.',
        'sections' => [
            [
                'id' => 'penerimaan',
                'heading' => 'Penerimaan ketentuan',
                'paragraphs' => [
                    'Ketentuan ini berlaku bagi seluruh pengunjung, customer, dan seller yang menggunakan layanan EcoCraft. Jika kamu tidak menyetujuinya, mohon tidak menggunakan layanan.',
                ],
            ],
            [
                'id' => 'akun',
                'heading' => 'Akun dan keamanan',
                'bullets' => [
                    'Data pendaftaran harus benar dan mutakhir.',
                    'Kata sandi adalah tanggung jawabmu; jangan bagikan ke orang lain.',
                    'Aktivitas yang terjadi melalui akunmu menjadi tanggung jawab pemilik akun.',
                ],
            ],
            [
                'id' => 'seller',
                'heading' => 'Kewajiban seller',
                'bullets' => [
                    'Karya yang dipasarkan harus asli, aman, dan sesuai deskripsi.',
                    'Akun seller wajib melewati verifikasi dan persetujuan admin.',
                    'Seller bertanggung jawab atas kualitas, stok, dan penyelesaian pesanan.',
                ],
            ],
            [
                'id' => 'pesanan-pembayaran',
                'heading' => 'Pesanan dan pembayaran',
                'bullets' => [
                    'Pesanan diproses setelah checkout dan pembayaran terkonfirmasi.',
                    'Harga dan ketersediaan dapat berubah sesuai kondisi katalog.',
                    'Pembatalan mengikuti kebijakan seller dan status pesanan saat itu.',
                ],
            ],
            [
                'id' => 'pengiriman-garansi',
                'heading' => 'Pengiriman dan garansi',
                'paragraphs' => [
                    'Estimasi pengiriman bergantung pada alamat, seller, dan ekspedisi yang dipilih. Produk tertentu memiliki garansi pengrajin untuk reparasi anyaman dan jahitan sesuai ketentuan seller.',
                ],
            ],
            [
                'id' => 'koin-voucher',
                'heading' => 'Koin sirkular dan voucher',
                'bullets' => [
                    'Koin dan voucher tidak dapat diuangkan menjadi uang tunai.',
                    'Pemakaian mengikuti syarat minimum belanja dan masa berlaku yang tertera.',
                    'Penyalahgunaan dapat menyebabkan pembatalan manfaat.',
                ],
            ],
            [
                'id' => 'konten-komunitas',
                'heading' => 'Konten komunitas',
                'paragraphs' => [
                    'Komentar dan pertanyaan yang kamu kirim harus sopan dan relevan. Dilarang mengunggah konten yang menyinggung, menyesatkan, bersifat spam, atau melanggar hukum. Kami berhak menghapus konten yang melanggar.',
                ],
            ],
            [
                'id' => 'larangan',
                'heading' => 'Penggunaan yang dilarang',
                'bullets' => [
                    'Mengakses sistem tanpa izin atau mengganggu jalannya layanan.',
                    'Memalsukan identitas, ulasan, atau data transaksi.',
                    'Menggunakan layanan untuk kegiatan yang melanggar hukum.',
                ],
            ],
            [
                'id' => 'tanggung-jawab',
                'heading' => 'Batasan tanggung jawab',
                'paragraphs' => [
                    'EcoCraft memfasilitasi pertemuan antara customer dan seller. Transaksi utama terjadi antara kedua pihak, sehingga penyelesaian sengketa diupayakan terlebih dahulu melalui mediasi admin EcoCraft.',
                ],
            ],
            [
                'id' => 'perubahan-ketentuan',
                'heading' => 'Perubahan ketentuan',
                'paragraphs' => [
                    'Ketentuan ini dapat diperbarui sewaktu-waktu. Dengan terus menggunakan layanan setelah pembaruan, kamu dianggap menyetujui versi terbarunya.',
                ],
            ],
        ],
    ],

];
