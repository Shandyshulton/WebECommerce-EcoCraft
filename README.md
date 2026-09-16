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
| Bahasa | PHP 8.1+ (diuji pada 8.5.8) |
| Framework | Laravel 10.50 |
| Database | MySQL / MariaDB |
| Tampilan | Blade, Bootstrap 5.3 (CDN), Font Awesome 6.4 (CDN), EB Garamond + Plus Jakarta Sans |
| Aset | Laravel Mix 6 (Webpack) |
| Autentikasi | Session guard — 4 guard terpisah |
| Pengujian | PHPUnit 10 |

---

## Peran pengguna

Aplikasi memakai empat guard terpisah (`config/auth.php`), masing-masing dengan tabel dan alur login sendiri.

| Guard | Tabel | Akses |
| --- | --- | --- |
| `admin` (role `super_admin`) | `admins` | Semua akses admin + kelola staf, faktor dampak material, dan voucher |
| `admin` (role `admin`) | `admins` | Verifikasi pengrajin & produk |
| `seller` | `sellers` | Produk, pesanan, pengiriman, klaim garansi. **Wajib `status = approved`** — `SellerMiddleware` memaksa logout jika belum |
| `customer` | `customers` | Katalog, keranjang, checkout, dompet, klaim, alamat, pelacakan |

Login customer bisa memakai **email atau nomor WhatsApp**. Ada form login terpisah untuk seller (`/seller/login`) dan admin (`/admin/login`).

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
- Checkout memakai buku alamat tersimpan atau mengisi alamat baru
- Pilih metode kirim (`Reguler` / `Express` / `Sameday`) dan metode bayar (`COD` / `Transfer Bank` / `QRIS`)
- Potongan voucher dan koin dihitung dalam satu transaksi database, termasuk penguncian baris (`lockForUpdate`) untuk mencegah perebutan saldo koin

### Pengiriman & pelacakan
Satu pesanan bisa memuat produk dari **beberapa pengrajin sekaligus**, sehingga pengiriman dilacak per pasangan (order, seller) — bukan per order.
- `shipments`: ekspedisi, nomor resi, status, waktu kirim/terima — unik per (order, seller)
- `order_tracking_events`: riwayat perjalanan paket dengan kolom `source` (`system` / `seller` / `admin` / `courier`)
- Status pesanan **diagregasi** dari seluruh pengirimannya: `Delivered` hanya bila semua paket tiba, dan status tidak bisa turun kembali ke `Processing` saat sebagian paket sudah berjalan
- Halaman pelacakan pembeli menampilkan timeline per paket, tombol salin resi, dan tautan ke situs ekspedisi
- Data ekspedisi (`couriers`) adalah referensi biasa — tanpa akun login. Kolom `tracking_url` mendukung placeholder `{resi}` untuk deep link

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
3. Checkout: pilih alamat, metode kirim, metode bayar, voucher, dan jumlah koin
4. `CheckoutController::store` dalam **satu transaksi**: buat order (`Processing`), buat item, potong koin, tandai voucher terpakai, beri koin baru, simpan alamat baru bila diminta, dan **buat data pengiriman untuk setiap pengrajin**
5. Pengrajin membuka *Pengiriman*, memilih ekspedisi dan mengisi nomor resi → status paket `Shipped`
6. Pembeli memantau di `/track-order`: timeline per paket + tautan ke situs ekspedisi
7. Setelah pengrajin menambahkan titik perjalanan, status pesanan ikut menyesuaikan
8. Saat **semua** paket `Delivered`, pesanan menjadi `Delivered` dan voucher reward diberikan tiap kelipatan 5

---

## Akun demo

Tersedia setelah `php artisan db:seed`.

| Peran | Email | Password |
| --- | --- | --- |
| Super admin | `rafly@gmail.com` | `rafly123` |
| Admin | `admin@ecocraft.test` | `admin123` |
| Pengrajin | `demo.pengrajin@ecocraft.test` | `demo12345` |

Akun pengrajin demo hanya dibuat bila belum ada pengrajin berstatus `approved`. **Pembeli tidak di-seed** — daftarkan lewat `/register`.

Data contoh lain: 10 material acuan dampak, 8 cerita komunitas, 10 produk, 2 voucher (`WELCOME10`, `REWARD5`), dan 8 ekspedisi Indonesia.

---

## Instalasi

### Prasyarat
- PHP 8.1+ dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`
- Composer
- Node.js & npm
- MySQL / MariaDB

### Langkah

```bash
# 1. Dependensi
composer install
npm install

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

# 5. Bangun aset
npm run dev            # pengembangan
npm run production     # produksi

# 6. Jalankan
php artisan serve
```

Aplikasi tersedia di `http://localhost:8000`.

---

## Menjalankan test

```bash
php artisan test
```

Saat ini **37 test (125 assertion)** dan semuanya lulus, mencakup alamat pengiriman, klaim garansi, pengiriman multi-pengrajin, dan sertifikat dampak.

Dua catatan penting:

- Test berjalan pada **database MySQL sungguhan**, bukan SQLite in-memory (`DB_CONNECTION` di `phpunit.xml` sengaja dikomentari). Tiap test dibungkus `DatabaseTransactions` agar rollback. Pastikan server database hidup dan sudah dimigrasi sebelum menjalankan test.
- Pada PHP 8.5 muncul notice *deprecation* dari konektor MySQL Laravel 10 (`PDO::MYSQL_ATTR_SSL_CA`). Ini berasal dari framework, bukan dari kode aplikasi, dan tidak memengaruhi hasil test.

---

## Struktur direktori

```
app/
├── Http/
│   ├── Controllers/     # 24 controller, dipisah per peran & domain
│   └── Middleware/      # AdminMiddleware, SuperAdminMiddleware,
│                        # CustomerMiddleware, SellerMiddleware
├── Models/              # 21 model Eloquent
└── Services/            # RewardService, ShipmentService, ImpactService
database/
├── migrations/          # 30 migrasi
└── seeders/             # 7 seeder
resources/views/
├── customer/            # Dashboard, katalog, komunitas, dompet, sertifikat
├── seller/              # Dashboard, pesanan, pengiriman, klaim
├── admin/, order/, products/, checkout/, track/
tests/Feature/           # 5 file test (4 ranah + ExampleTest)
```

---

## Batasan yang diketahui

Bagian ini ditulis terbuka agar batas antara **yang sudah dibangun** dan **yang dirancang** jelas.

- **Pembayaran tidak diproses.** `orders.payment_method` mencatat pilihan pembeli, tetapi tidak ada integrasi payment gateway dan tidak ada rekonsiliasi pembayaran.
- **Ongkos kirim tidak dihitung.** `orders.shipping_method` hanya pilihan layanan; tarif kirim belum dihitung dan tidak masuk ke `orders.total`.
- **Pelacakan resi diinput manual** oleh pengrajin, bukan ditarik dari API ekspedisi. `couriers.tracking_url` mengarah ke halaman pelacakan resmi ekspedisi. Mekanisme deep link `{resi}` sudah didukung di kode, tetapi polanya belum diisi karena belum dipastikan per ekspedisi.
- **Belum ada sisi admin untuk pesanan & pengiriman** — keduanya dikelola pengrajin.
- **Tanya jawab produk hanya customer ↔ seller.** Teks antarmuka menyebut "admin EcoCraft", tetapi sisi admin belum ada.
- **Klaim garansi berhenti di status.** Persetujuan belum memicu aksi otomatis seperti penggantian barang atau kompensasi voucher.
- **Notifikasi email belum aktif.** Mail belum dikonfigurasi.
- **Model `User` dan tabel `users`** adalah sisa scaffold Laravel dan tidak dipakai oleh guard mana pun.

---

## Lisensi

MIT.
