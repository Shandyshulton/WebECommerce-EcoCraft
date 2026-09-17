# EcoCraft

Marketplace kerajinan daur ulang dari pengrajin lokal, dengan pencatatan **dampak lingkungan yang dihitung dari data transaksi nyata**.

Ini bukan e-commerce biasa. Setiap produk punya faktor limbah dan emisi, setiap pesanan menumbuhkan "paspor jejak lingkungan" milik pembeli, dan setiap klaim garansi ditangani langsung oleh pengrajin yang membuat barangnya.

---

## Tentang proyek

EcoCraft menghubungkan pengrajin Nusantara yang mengolah material bekas dengan pembeli yang ingin belanja lebih bertanggung jawab. Tiga hal yang membedakannya dari platform e-commerce umum:

1. **Dampak dihitung, bukan diklaim.** Setiap produk punya `waste_factor` dan `carbon_factor` yang diwarisi dari tabel referensi material. Total dampak pembeli dihitung dari item pesanannya yang sebenarnya — bukan angka statis. Lihat `app/Services/ImpactService.php`.
2. **Garansi pengrajin.** Pembeli dapat mengajukan klaim untuk barang rusak atau tidak sesuai, dan pengrajin yang bersangkutan yang menanggapi.
3. **Ekonomi sirkular.** Koin sirkular dan voucher reward mendorong pembelian berulang sekaligus menutup lingkaran dampak.

---

## Teknologi

| Lapisan | Teknologi |
| --- | --- |
| Bahasa | PHP 8.2+ (diuji pada 8.5.8) |
| Framework | Laravel 12.69 |
| Database | MySQL / MariaDB |
| Tampilan | Blade, Bootstrap 5.3, Font Awesome 6.4 (CDN), EB Garamond + Plus Jakarta Sans |
| Autentikasi | Session guard — 4 guard terpisah |
| Pengujian | PHPUnit 11 |

---

## Peran pengguna

Aplikasi memakai empat guard terpisah (`config/auth.php`); guard `admin` punya dua tingkat peran sehingga muncul dua baris di bawah.

| Guard | Tabel | Akses |
| --- | --- | --- |
| `admin` (role `super_admin`) | `admins` | Semua akses admin + kelola staf, faktor dampak material, voucher, dan akun kurir |
| `admin` (role `admin`) | `admins` | Verifikasi pengrajin & produk |
| `seller` | `sellers` | Produk, pesanan, pengiriman, klaim garansi. **Wajib `status = approved`** — `SellerMiddleware` memaksa logout jika belum |
| `customer` | `customers` | Katalog, keranjang, checkout, dompet, klaim, alamat, pelacakan |
| `courier` | `courier_users` | Daftar tugas antar, ambil paket, perbarui status, unggah bukti serah terima |

Login customer bisa memakai **email atau nomor WhatsApp**. Ada form login terpisah untuk seller (`/seller/login`), admin (`/admin/login`), dan kurir (`/courier/login`).

Perhatikan bahwa `couriers` (referensi jasa ekspedisi) dan `courier_users` (orang yang mengantar) adalah dua entitas berbeda — akun login tidak ditumpangkan ke tabel referensi, sehingga satu jasa kurir bisa memiliki banyak petugas.

---

## Fitur

### Autentikasi & peran
- Empat guard session, satu form login customer yang mencoba admin → seller → customer berurutan
- Registrasi customer dan registrasi seller (dengan unggah foto KTP)
- Reset password lintas tabel
- Super admin dapat membuat, mengubah peran, dan menghapus akun staf admin

### Katalog & verifikasi
- CRUD produk oleh pengrajin, lengkap dengan galeri gambar
- Alur persetujuan: pengrajin dan produk harus disetujui admin sebelum tayang
- Katalog publik dengan pencarian (nama, kategori, material, deskripsi)
- Halaman detail produk menampilkan **limbah dialihkan** dan **emisi dihindari** per pcs

### Belanja & checkout
- Keranjang berbasis session dengan dukungan "beli langsung"
- **Pilihan item menentukan checkout.** Checkbox di keranjang bukan sekadar alat hapus: total mengikuti pilihan, hanya item terpilih yang menjadi item pesanan, dan sisa keranjang tetap tersimpan setelah checkout. "Beli langsung" memilih produk itu saja, bukan seluruh isi keranjang
- Checkout memakai buku alamat tersimpan atau mengisi alamat baru
- Pilih metode kirim (`Reguler` / `Express` / `Sameday`) dan metode bayar (`COD` / `Transfer Bank` / `QRIS`)
- Potongan voucher dan koin dihitung dalam satu transaksi database, termasuk penguncian baris (`lockForUpdate`) untuk mencegah perebutan saldo koin

### Pembayaran
- COD tidak punya langkah pembayaran di muka; Transfer dan QRIS diarahkan ke halaman pembayaran setelah checkout
- **Transfer Bank** → nomor Virtual Account, dibuat sekali per pesanan lalu disimpan (`orders.virtual_account`) supaya nomornya tidak berubah saat halaman dibuka ulang
- **QRIS** → kode QR dari payload format EMVCo (tag-length-value + CRC16-CCITT), digambar di sisi klien lewat pustaka `qrcodejs`
- Tiap payload memuat nomor pesanan sebagai reference label, sehingga dua pesanan bernominal sama tetap menghasilkan QR yang berbeda
- Status bayar dicatat **terpisah** dari status pengiriman (`orders.payment_status`) dan tampil di halaman lacak maupun panel pengrajin
- Pesanan yang belum dibayar **tidak bisa berjalan lebih jauh**: pengrajin ditolak saat menandai paket dikirim, dan paketnya tidak muncul di daftar tugas kurir. COD dikecualikan karena dibayar saat diterima
- **Simulasi:** nomor VA dan payload QR dibuat aplikasi sendiri, dan status "sudah dibayar" berpindah atas pernyataan pembeli — lihat `app/Services/PaymentService.php`

### Pengiriman & pelacakan
Satu pesanan bisa memuat produk dari **beberapa pengrajin sekaligus**, sehingga pengiriman dilacak per pasangan (order, seller) — bukan per order.
- `shipments`: ekspedisi, nomor resi, status, waktu kirim/terima, nama & foto bukti penerimaan — unik per (order, seller)
- `order_tracking_events`: riwayat perjalanan paket dengan kolom `source` (`system` / `seller` / `admin` / `courier` / `customer`)
- Status pesanan **diagregasi** dari seluruh pengirimannya: `Delivered` hanya bila semua paket dikonfirmasi tiba, dan status tidak bisa turun kembali ke `Processing` saat sebagian paket sudah berjalan
- **Peran pengrajin dipersempit.** Pengrajin hanya memilih ekspedisi dan mengisi nomor resi — statusnya ditentukan sistem: ekspedisi pihak ketiga langsung `Shipped` (menyerahkan paket berarti mulai dikirim), Kurir Lokal langsung `Packed` (siap diambil). Setelah diserahkan, halaman berubah jadi **baca saja**
- **Kurir hanya punya satu aksi: menyatakan paket sampai.** Mengambil tugas dari daftar "siap diantar" (dikunci agar tidak bentrok) langsung membuat paket berstatus `Shipped` — paket berpindah ke tangan kurir. Setelah itu kurir cukup menekan **"Paket sudah sampai"** dengan nama penerima dan foto bukti, keduanya **wajib**. Titik perjalanan bisa dicatat opsional tanpa mengubah status
- **Titik perjalanan hanya dicatat kurir**, dan hanya untuk pengiriman yang kita kendalikan (`couriers.is_local_delivery`). Untuk ekspedisi pihak ketiga posisi paket tidak diketahui siapa pun di sini, jadi pembeli diarahkan ke situs ekspedisi — bukan disuguhi timeline tebakan
- **Dua jalur konfirmasi terima:** kurir menandai `Delivered` dengan bukti foto untuk pengiriman lokal, atau pembeli sendiri menekan "Paket sudah saya terima" untuk ekspedisi pihak ketiga
- **Timeline dibaca dari yang terbaru.** Riwayat perjalanan di halaman pembeli, pengrajin, dan kurir semuanya menampilkan status terakhir di paling atas, dengan titik penanda aktif di baris pertama. Query-nya tetap urut kronologis; pembalikannya dilakukan saat ditampilkan
- Data ekspedisi (`couriers`) adalah referensi biasa — tanpa akun login. Kolom `tracking_url` mendukung placeholder `{resi}`, tetapi ekspedisi yang diuji tidak menerima resi lewat URL, jadi nomor resi disalin otomatis saat tombol lacak ditekan

### Loyalitas: koin sirkular & voucher
Konfigurasi di `config/rewards.php`.
- Koin didapat dari nilai belanja (`coin_earn_per`) dan dipakai sebagai potongan saat checkout (`coin_value`), dibatasi `max_discount_ratio`
- Voucher reward otomatis diberikan setiap 5 pesanan selesai
- Voucher bisa diklaim dari dompet atau dipakai lewat kode
- Semua mutasi tercatat di `coin_transactions` beserta saldo setelah transaksi
- Seluruh logika terpusat di `app/Services/RewardService.php`

### Dampak lingkungan
- `impact_factors` menyimpan acuan per jenis material (limbah & karbon per item)
- Faktor produk diambil dari input pengrajin, atau diwarisi dari acuan material, atau nilai bawaan
- Dashboard pembeli menampilkan paspor jejak lingkungan: total limbah, emisi, setara pohon, pengrajin yang didukung, dan tren 6 bulan
- **Sertifikat dampak** siap cetak dengan nomor stabil per pembeli (`/customer/impact-certificate`) — dapat diunduh sebagai PDF lewat dialog cetak browser
- Rumus dampak terpusat di `app/Services/ImpactService.php`

### Purna jual: garansi pengrajin
- Klaim hanya bisa diajukan untuk item dari pesanan berstatus `Shipped` atau `Delivered`
- Satu item tidak bisa diklaim ganda selama klaim sebelumnya belum ditolak
- Kategori: kerusakan, jahitan, tidak sesuai, lainnya — dengan unggah foto
- Pengrajin menanggapi dengan status (`Reviewing` / `Approved` / `Rejected` / `Completed`) dan catatan penyelesaian

### Komunitas & komunikasi
- Cerita komunitas bertopik, dengan komentar dari member terdaftar
- Tanya jawab produk: satu thread per (customer, produk), dengan penanda pesan sudah dibaca

### Buku alamat
- CRUD alamat dengan invarian yang dijaga: selalu tepat satu alamat utama
- Menghapus alamat utama otomatis mempromosikan alamat lain
- Terintegrasi dua arah dengan checkout

---

## Alur satu pesanan

1. Pembeli menjelajah katalog → halaman detail produk (melihat faktor dampak)
2. Tambah ke keranjang, atau beli langsung
3. Di keranjang, pilih produk mana yang mau dibeli — total mengikuti pilihan
4. Checkout: pilih alamat, metode kirim, metode bayar, voucher, dan jumlah koin
5. `CheckoutController::store` dalam **satu transaksi**: buat order (`Processing`), buat item dari produk terpilih, potong koin, tandai voucher terpakai, beri koin baru, simpan alamat baru bila diminta, dan **buat data pengiriman untuk setiap pengrajin**
6. Transfer/QRIS → halaman pembayaran: pembeli menekan **"Saya sudah bayar"**, status bayar menjadi `Paid`. COD melewati langkah ini
7. Pengrajin membuka *Pengiriman*, memilih ekspedisi, lalu menyerahkan paket. Ekspedisi pihak ketiga: isi nomor resi → status `Shipped`. Kurir Lokal EcoCraft: tanpa resi → status `Packed`, paket masuk daftar tugas kurir
8. Kurir lokal mengambil tugas — paket otomatis menjadi `Shipped` — lalu mengantar dan menekan **"Paket sudah sampai"** dengan nama penerima dan foto bukti
9. Pembeli memantau di `/track-order`: status per paket dengan **timeline terbaru di atas**, tombol salin resi, dan tautan ke situs ekspedisi
10. Paket tiba → dinyatakan kurir (wajib bukti foto) atau oleh pembeli sendiri lewat **"Paket sudah saya terima"**
11. Saat **semua** paket dinyatakan tiba, pesanan menjadi `Delivered` dan voucher reward diberikan tiap kelipatan 5

---

## Akun demo

Tersedia setelah `php artisan db:seed`.

| Peran | Email | Password |
| --- | --- | --- |
| Super admin | `rafly@gmail.com` | `rafly123` |
| Admin | `admin@ecocraft.test` | `admin123` |
| Pengrajin | `demo.pengrajin@ecocraft.test` | `demo12345` |
| Kurir lokal | `kurir@ecocraft.test` | `kurir12345` |

Akun pengrajin demo hanya dibuat bila belum ada pengrajin berstatus `approved`. **Pembeli tidak di-seed** — daftarkan lewat `/register`.

Data contoh lain: 10 material acuan dampak, 8 cerita komunitas, 10 produk, 2 voucher (`WELCOME10`, `REWARD5`), dan 8 ekspedisi Indonesia.

---

## Instalasi

### Prasyarat
- PHP 8.2+ dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`
- Composer
- MySQL / MariaDB

### Langkah

```bash
# 1. Dependensi
composer install

# 2. Konfigurasi lingkungan
cp .env.example .env
php artisan key:generate
```

Sesuaikan `.env` dengan server database kamu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306          # Laragon sering memakai 3308
DB_DATABASE=ecocraft_db
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 3. Buat database bernama ecocraft_db, lalu:
php artisan migrate
php artisan db:seed

# 4. Tautkan penyimpanan gambar
php artisan storage:link

# 5. Jalankan
php artisan serve
```

Aplikasi tersedia di `http://localhost:8000`.

---

## Menjalankan test

```bash
php artisan test
```

Saat ini **91 test (365 assertion)** dan semuanya lulus, mencakup akses per peran, pilihan keranjang & checkout, alamat pengiriman, klaim garansi, pengiriman multi-pengrajin, tugas kurir, pembayaran, dan sertifikat dampak.

Catatan: test berjalan pada **database MySQL sungguhan**, bukan SQLite in-memory (`DB_CONNECTION` di `phpunit.xml` sengaja dikomentari). Tiap test dibungkus `DatabaseTransactions` agar rollback. Pastikan server database hidup dan sudah dimigrasi sebelum menjalankan test.

---

## Struktur direktori

```
app/
├── Http/
│   ├── Controllers/     # 28 controller, dipisah per peran & domain
│   └── Middleware/      # AdminMiddleware, SuperAdminMiddleware, SellerMiddleware,
│                        # CourierMiddleware
├── Models/              # 20 model Eloquent
└── Services/            # RewardService, ShipmentService, ImpactService, PaymentService
database/
├── migrations/          # 33 migrasi
└── seeders/             # 7 seeder
resources/views/
├── customer/            # Dashboard, katalog, komunitas, dompet, sertifikat
├── seller/              # Dashboard, pesanan, pengiriman, klaim
├── courier/             # Login, daftar tugas, detail tugas
├── payment/             # Virtual Account, QRIS, tampilan sudah dibayar
├── admin/, order/, products/, checkout/, track/
tests/Feature/           # 9 file test
```

---

## Batasan yang diketahui

Bagian ini ditulis terbuka agar batas antara **yang sudah dibangun** dan **yang dirancang** jelas.

- **Pembayaran masih simulasi.** Nomor Virtual Account dan payload QRIS dibuat aplikasi sendiri, dan status "sudah dibayar" berpindah atas pernyataan pembeli. Tidak ada payment gateway, verifikasi, maupun rekonsiliasi.
- **Pengrajin belum bisa melihat rincian pembayaran.** Status bayar tampil, tetapi tidak ada halaman bagi pengrajin untuk menandai pembayaran diterima secara manual.
- **Ongkos kirim tidak dihitung.** `orders.shipping_method` hanya pilihan layanan; tarif kirim belum dihitung dan tidak masuk ke `orders.total`.
- **Tidak ada tarikan data dari API ekspedisi.** Nomor resi diisi pengrajin dan posisi paket di tengah perjalanan tidak diketahui sistem, jadi pembeli diarahkan ke situs ekspedisi.
- **Deep link resi tidak bisa dipakai.** Mekanismenya sudah didukung di kode (`couriers.tracking_url` dengan placeholder `{resi}`), tetapi saat diuji langsung ekspedisi besar tidak menerimanya: Anteraja mengabaikan resi di URL (respons byte-identik), SiCepat dan JNE mengembalikan 404. Karena itu nomor resi **disalin otomatis** saat tombol lacak ditekan, sehingga pembeli tinggal menempelkannya di kolom pencarian.
- **Penugasan kurir masih berbasis klaim.** Petugas mengambil sendiri paket dari daftar "siap diantar" (dikunci agar tidak bentrok), bukan ditugaskan admin berdasarkan rute atau beban kerja.
- **Titik perjalanan kurir belum berisi koordinat.** Kurir mencatat status, bukan posisi GPS; pelacakan real-time belum ada.
- **Admin tidak mengelola pesanan maupun pengiriman.** Keduanya ada di sisi pengrajin, dan pengiriman lokal di sisi kurir; admin hanya mengelola akun kurirnya.
- **Tanya jawab produk hanya customer ↔ seller.** Teks antarmuka menyebut "admin EcoCraft", tetapi sisi admin belum ada.
- **Klaim garansi berhenti di status.** Persetujuan belum memicu aksi otomatis seperti penggantian barang atau kompensasi voucher.
- **Notifikasi email belum aktif.** Mail belum dikonfigurasi.
- **Model `User` dan tabel `users`** adalah sisa scaffold Laravel dan tidak dipakai oleh guard mana pun.

---

## Lisensi

MIT.
