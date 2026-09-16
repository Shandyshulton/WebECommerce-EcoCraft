@extends('layouts.customer')

@section('title', 'EcoCraft | Catalog')

@section('content')
@guest('customer')
<section class="reference-hero">
    <div class="page-wrap reference-grid">
        <div>
            <div class="mobile-quick-grid" id="quickGrid" aria-label="Pintasan cepat">
                <a class="mqg-item" href="{{ route('catalog.index') }}"><span class="mqg-icon"><i class="fa fa-th-large"></i></span><span class="mqg-label">Katalog</span></a>
                <a class="mqg-item" href="{{ route('login') }}"><span class="mqg-icon"><i class="fa fa-shopping-cart"></i></span><span class="mqg-label">Keranjang</span></a>
                <a class="mqg-item" href="{{ route('login') }}"><span class="mqg-icon accent"><i class="fa fa-comments"></i></span><span class="mqg-label">Chat Seller</span></a>
                <a class="mqg-item" href="{{ route('login') }}"><span class="mqg-icon"><i class="fa fa-truck"></i></span><span class="mqg-label">Lacak Pesanan</span></a>
                <a class="mqg-item is-hidden" href="{{ route('customer.dashboard') }}#stories"><span class="mqg-icon accent"><i class="fa fa-book-open"></i></span><span class="mqg-label">Cerita</span></a>
                <a class="mqg-item is-hidden" href="{{ route('customer.dashboard') }}#impact"><span class="mqg-icon"><i class="fa fa-leaf"></i></span><span class="mqg-label">Dampak</span></a>
                <a class="mqg-item is-hidden" href="{{ route('login') }}"><span class="mqg-icon"><i class="fa fa-user"></i></span><span class="mqg-label">Akun</span></a>
                <a class="mqg-item is-hidden" href="{{ route('seller.register.form') }}"><span class="mqg-icon accent"><i class="fa fa-store"></i></span><span class="mqg-label">Jadi Seller</span></a>
                <button type="button" class="mqg-more" data-quick-more><i class="fa fa-th"></i> Lihat semua</button>
            </div>
            <div class="eyebrow">Pintu masuk gaya hidup berkelanjutan</div>
            <h1>Selamat datang di EcoCraft</h1>
            <p class="welcome-note">Gerakan belanja sirkular berkelanjutan</p>
            <p class="lead">Temukan kerajinan tangan Nusantara dari material yang lebih bertanggung jawab, lalu dukung pengrajin lokal melalui setiap pilihanmu.</p>
            <div class="reference-metrics mt-4">
                <div class="reference-metric"><span class="eyebrow">Produk terkurasi</span><strong>{{ number_format($stats['products']) }}</strong><small class="text-muted">aktif di katalog</small></div>
                <div class="reference-metric"><span class="eyebrow">Mitra pengrajin</span><strong>{{ number_format($stats['sellers']) }}</strong><small class="text-muted">seller terverifikasi</small></div>
                <div class="reference-metric"><span class="eyebrow">Pohon tertanam</span><strong>{{ number_format($stats['trees']) }}</strong><small class="text-muted">mangrove & mahoni</small></div>
            </div>
            <div id="stories" class="community-highlights-grid">
                @forelse($stories as $story)
                @php($storyUrl = $story->slug ? route('community.show', $story->slug) : route('community.index'))
                <article class="community-highlight">
                    <a class="community-highlight-image" href="{{ $storyUrl }}" style="background-image:url('{{ $story->image ? asset(str_replace(' ', '%20', $story->image)) : asset('assets/images/collection/arrivals1.png') }}')" role="img" aria-label="{{ $story->title }}"></a>
                    <div><div class="eyebrow" style="color:var(--accent)">{{ $story->label }}</div><h3>{{ $story->title }}</h3><p>{{ $story->excerpt }}</p><a href="{{ $storyUrl }}">Baca cerita <span aria-hidden="true">&rarr;</span></a></div>
                </article>
                @empty
                    <p class="text-muted">Belum ada cerita komunitas tersedia.</p>
                @endforelse
            </div>
            <div class="text-center mt-3"><a class="btn btn-outline-brand" href="{{ route('community.index') }}">Lihat semua sorotan komunitas <span aria-hidden="true">&rarr;</span></a></div>
        </div>
        <aside class="guest-impact-panel">
            <div class="eyebrow" style="color:var(--accent)">Dampak &amp; kepercayaan</div>
            <h2>Belanja yang meninggalkan jejak baik</h2>
            <p>Setiap karya di EcoCraft melewati kurasi material dan pengrajin, jadi pilihanmu ikut menekan limbah sekaligus menopang ekonomi lokal.</p>
            <ul class="guest-impact-list">
                <li><span class="guest-impact-icon"><i class="fa fa-recycle" aria-hidden="true"></i></span><div><strong>100% bahan berkelanjutan</strong><span>Terverifikasi adil untuk lingkungan.</span></div></li>
                <li><span class="guest-impact-icon"><i class="fa fa-hand-holding-heart" aria-hidden="true"></i></span><div><strong>70% margin ke pengrajin</strong><span>Keadilan ekonomi UMKM Nusantara.</span></div></li>
                <li><span class="guest-impact-icon"><i class="fa fa-box" aria-hidden="true"></i></span><div><strong>Bungkus bebas plastik</strong><span>Karton daur ulang &amp; fitosintetik.</span></div></li>
                <li><span class="guest-impact-icon"><i class="fa fa-truck-fast" aria-hidden="true"></i></span><div><strong>Pengiriman karbon netral</strong><span>Kompensasi tiap perjalanan ke rumahmu.</span></div></li>
            </ul>
            <div class="guest-impact-foot"><a class="btn btn-outline-brand" href="{{ route('about') }}">Pelajari misi kami <span aria-hidden="true">&rarr;</span></a></div>
        </aside>
    </div>
</section>
@else
@php($customer = Auth::guard('customer')->user())
<section class="member-dashboard">
    <div class="member-banner mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div><div class="eyebrow">Pecinta bumi · customer terverifikasi</div><h1 class="mb-2"><span class="sr-only">{{ $greetings[0] ?? 'Selamat Pagi' }}, </span><span aria-hidden="true"><span class="greeting-typist" data-greeting-typist>{{ $greetings[0] ?? 'Selamat Pagi' }}</span><span data-greeting-sep>, </span></span>{{ $customer->name_customers }}</h1><p class="lead mb-0">Senang melihatmu kembali. Pilihanmu membantu karya lokal dan material sirkular terus tumbuh.</p></div>
            <a class="btn btn-brand px-4 py-2" href="#catalog">Belanja sekarang</a>
        </div>
    </div>

    <div class="mobile-quick-grid" id="quickGrid" aria-label="Pintasan cepat">
        <a class="mqg-item" href="{{ route('catalog.index') }}"><span class="mqg-icon"><i class="fa fa-th-large"></i></span><span class="mqg-label">Katalog</span></a>
        <a class="mqg-item" href="{{ route('cart.show') }}"><span class="mqg-icon"><i class="fa fa-shopping-cart"></i></span><span class="mqg-label">Keranjang</span></a>
        <a class="mqg-item" href="{{ route('customer.inquiries.index') }}"><span class="mqg-icon accent"><i class="fa fa-comments"></i></span><span class="mqg-label">Chat Seller</span></a>
        <a class="mqg-item" href="{{ route('track.track') }}"><span class="mqg-icon"><i class="fa fa-truck"></i></span><span class="mqg-label">Lacak Pesanan</span></a>
        <a class="mqg-item is-hidden" href="{{ route('customer.dashboard') }}#stories"><span class="mqg-icon accent"><i class="fa fa-book-open"></i></span><span class="mqg-label">Cerita</span></a>
        <a class="mqg-item is-hidden" href="{{ route('customer.dashboard') }}#impact"><span class="mqg-icon"><i class="fa fa-leaf"></i></span><span class="mqg-label">Dampak</span></a>
        <a class="mqg-item is-hidden" href="{{ route('customer.profile') }}"><span class="mqg-icon"><i class="fa fa-user"></i></span><span class="mqg-label">Akun</span></a>
        <a class="mqg-item is-hidden" href="{{ route('customer.addresses.index') }}"><span class="mqg-icon"><i class="fa fa-location-dot"></i></span><span class="mqg-label">Alamat</span></a>
        <a class="mqg-item is-hidden" href="{{ route('seller.register.form') }}"><span class="mqg-icon accent"><i class="fa fa-store"></i></span><span class="mqg-label">Jadi Seller</span></a>
        <button type="button" class="mqg-more" data-quick-more><i class="fa fa-th"></i> Lihat semua</button>
    </div>
    @if($stories->isNotEmpty())
    <section id="stories" class="member-section">
        <div class="member-section-head">
            <div><div class="eyebrow" style="color:var(--accent)">Cerita di balik karya</div><h2>Sorotan komunitas</h2></div>
            <a class="member-section-link" href="{{ route('community.index') }}">Lihat semua <span aria-hidden="true">&rarr;</span></a>
        </div>
        <div class="community-row-track" id="story-row-member">
            @foreach($stories as $story)
                @include('customer.partials.story-card', ['story' => $story, 'hidden' => $loop->index >= 5])
            @endforeach
            @if($stories->count() > 5)
                <button type="button" class="story-row-more" data-load-more="story-row-member">Muat lebih banyak<span aria-hidden="true">+</span></button>
            @endif
        </div>
    </section>
    @endif
    <div class="member-stat-grid mb-5">
        <div class="member-panel member-stat"><div><span class="member-stat-label">Sampah terkelola</span><span class="member-stat-icon">♻</span><div class="member-stat-value">{{ number_format($impact['waste'], 1) }} <small class="text-muted">kg</small></div><small class="text-muted">Estimasi material yang dialihkan dari TPA.</small></div><div><div class="d-flex justify-content-between small text-muted"><span>Target Level 4 (20 kg)</span><strong>{{ min(100, (int) round(($impact['waste'] / 20) * 100)) }}%</strong></div><div class="member-progress"><span style="width:{{ min(100, ($impact['waste'] / 20) * 100) }}%"></span></div></div></div>
        <div class="member-panel member-stat"><div><span class="member-stat-label">Koin sirkular</span><span class="member-stat-icon">◉</span><div class="member-stat-value" style="color:var(--accent)">{{ number_format($impact['coins']) }} <small class="text-muted">poin</small></div><small class="text-muted">Senilai Rp {{ number_format($impact['coins'] * config('rewards.coin_value'), 0, ',', '.') }} potongan belanja.</small></div><a class="btn btn-sm btn-outline-brand" href="{{ route('customer.wallet') }}">Tukar Hadiah Hijau →</a></div>
        <div class="member-panel member-stat"><div><span class="member-stat-label">Pesanan aktif</span><span class="member-stat-icon">▣</span><div class="member-stat-value">{{ $activeOrders->count() }} <small class="text-muted">berlangsung</small></div><small class="text-muted">{{ $orders->where('status', 'Processing')->count() }} menunggu diproses.</small></div><a class="btn btn-sm btn-outline-brand" href="#orders">Lihat Semua Pesanan →</a></div>
        <div class="member-panel member-stat"><div><span class="member-stat-label">Voucher tersedia</span><span class="member-stat-icon">▤</span><div class="member-stat-value">{{ $impact['vouchers'] }} <small class="text-muted">kupon aktif</small></div><small class="text-muted">Termasuk promo dari riwayat belanjamu.</small></div><a class="btn btn-sm btn-outline-brand" href="{{ route('customer.wallet') }}">Klaim di Dompet →</a></div>
    </div>
    <section id="orders" class="member-section">
        <div class="member-section-head"><div><div class="eyebrow" style="color:var(--accent)">Logistik sadar lingkungan</div><h2>Pesanan Berlangsung & Status Pengiriman</h2></div><a class="member-section-link" href="{{ route('track.track') }}">Riwayat Transaksi →</a></div>
        <div class="member-orders">
            @forelse($activeOrders as $order)
                @php($item = $order->items->first()) @php($product = optional($item)->product)
                <article class="member-order"><div class="order-top"><span class="order-status {{ $order->status === 'Processing' ? 'terracotta' : '' }}">{{ $order->status === 'Processing' ? 'Sedang Diproses' : 'Dalam Perjalanan' }}</span><span class="order-number">Order: #{{ $order->order_number }}</span></div><div class="order-product"><img src="{{ optional($product)->image_url ? asset('storage/'.$product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ optional($item)->product_name }}"><div><div class="eyebrow" style="font-size:9px;color:var(--accent)">{{ optional(optional($item)->seller)->store_name ?: 'Mitra EcoCraft' }}</div><h3>{{ optional($item)->product_name ?: 'Pesanan EcoCraft' }}</h3><p>Jumlah {{ optional($item)->quantity ?: 0 }} · Ekspedisi {{ $order->shipping_method }}</p><span class="order-pack">Kemasan lebih ramah lingkungan</span></div></div><div class="order-stepper"><small class="text-muted">Estimasi pembaruan status: {{ $order->created_at->copy()->addDays(3)->format('d M, H:i') }}</small><div class="stepper-line"><span class="step done">Diterima</span><span class="step {{ in_array($order->status, ['Processing','Shipped']) ? 'done' : '' }}">Diproses</span><span class="step {{ $order->status === 'Shipped' ? 'done' : '' }}">Transit</span><span class="step">Terkirim</span></div></div><div class="order-bottom"><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong><a class="btn btn-sm btn-brand" href="{{ route('track.track') }}">Lacak Pesanan</a></div></article>
            @empty
                <div class="member-panel"><p class="mb-0 text-muted">Belum ada pesanan aktif. Jelajahi katalog untuk menemukan karya pilihanmu.</p></div>
            @endforelse
        </div>
    </section>
    <section id="impact" class="member-section impact-panel">
        <div class="impact-head"><div><div class="eyebrow">Paspor jejak lingkungan terverifikasi</div><h2>Dampak Kolektif Belanja Sirkularmu</h2><p>Angka dihitung dari riwayat pesananmu menggunakan estimasi material dan emisi yang dialihkan dari setiap produk.</p></div><a class="btn btn-brand" href="{{ route('customer.impact.certificate') }}">Unduh Sertifikat Dampak</a></div>
        <div class="impact-metrics"><div class="impact-metric"><span class="member-stat-label">Emisi karbon dihindari</span><strong>{{ number_format($impact['carbon'], 1) }} kg CO2e</strong><small>Estimasi pengurangan emisi dari pembelianmu.</small></div><div class="impact-metric"><span class="member-stat-label">Limbah padat tercegah</span><strong>{{ number_format($impact['waste'], 1) }} kg material</strong><small>Bahan bekas yang dialihkan dari TPA.</small></div><div class="impact-metric"><span class="member-stat-label">Dukungan ekonomi lokal</span><strong>{{ $impact['artisans'] }} pengrajin</strong><small>Seller yang menerima manfaat dari pesananmu.</small></div></div>
        <div class="d-flex justify-content-between small mb-2"><strong>Tren Pengurangan Limbah & Emisi</strong><span class="text-muted">■ kg sampah &nbsp; <span style="color:var(--accent)">■ kg CO2e</span></span></div><div class="chart">@foreach($impactTrend as $trend)<div><div class="chart-group"><span class="chart-bar" style="height:{{ max(3, min(100, $trend['waste'] * 8)) }}%" title="{{ $trend['waste'] }} kg"></span><span class="chart-bar terracotta" style="height:{{ max(3, min(100, $trend['carbon'] * 8)) }}%" title="{{ $trend['carbon'] }} kg CO2e"></span></div><div class="chart-label">{{ $trend['label'] }}</div></div>@endforeach</div>
    </section>
</section>
@endguest

@guest('customer')
<section id="impact" class="guest-section guest-section-soft">
    <div class="page-wrap">
        <div class="guest-section-title"><div class="eyebrow" style="color:var(--accent)">Keistimewaan komunitas</div><h2>Kenapa harus bergabung sebagai anggota EcoCraft?</h2><p>Menjadi bagian dari lingkungan EcoCraft berarti mendapatkan pengalaman belanja yang lebih bermakna dan terhubung dengan ekosistem pengrajin mandiri.</p></div>
        <div class="guest-benefit-grid">
            <article class="guest-benefit"><div class="guest-benefit-icon">&#10003;</div><h3>Pelacak dampak ekologis pribadi</h3><p>Setiap pembelian memiliki catatan kontribusi untuk mengurangi sampah dan mendukung karya berkelanjutan.</p></article>
            <article class="guest-benefit"><div class="guest-benefit-icon">&#8635;</div><h3>Koin sirkular & reward kurasi</h3><p>Tukarkan kontribusi belanja dengan voucher, potongan harga, atau dukungan langsung ke pengrajin.</p></article>
            <article class="guest-benefit"><div class="guest-benefit-icon">&#9673;</div><h3>Akses awal seni terbatas</h3><p>Dapatkan kesempatan melihat karya baru dan edisi terbatas dari seller pilihan EcoCraft.</p></article>
            <article class="guest-benefit"><div class="guest-benefit-icon">&#10065;</div><h3>Garansi higienis bebas cemas</h3><p>Produk diproses dengan standar yang jelas dan dukungan purna jual dari seller.</p></article>
        </div>
    </div>
</section>
@endguest

<section id="catalog" class="{{ auth('customer')->check() ? 'member-section' : 'section' }}">
    <div class="page-wrap">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        <div class="section-head">
            <div><div class="eyebrow">Katalog kurasi</div><h2>{{ auth('customer')->check() ? 'Rekomendasi untukmu' : 'Karya Populer Pekan Ini' }}</h2><p>Paling diminati dari komunitas EcoCraft minggu ini.</p></div>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-brand">Lihat Semua Kerajinan &rarr;</a>
        </div>
        <div class="product-grid {{ auth('customer')->check() ? 'member-product-grid' : '' }}">
            @forelse($products as $product)
                <article class="product-card">
                    <a class="product-card-image" href="{{ route('product.show', $product->id_products) }}">
                        <img src="{{ $product->image_url ? asset('storage/'.$product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $product->name }}">
                    </a>
                    <div class="product-card-body">
                        <small class="eyebrow">{{ $product->category ?: 'Karya pilihan' }}</small>
                        <h3><a href="{{ route('product.show', $product->id_products) }}">{{ $product->name }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit($product->description, 70) }}</p>
                        <small class="d-block text-muted mb-2">Oleh {{ optional($product->seller)->store_name ?: 'Mitra EcoCraft' }}</small>
                        @auth('customer')
                            <form class="card-buy" action="{{ route('cart.items.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_products }}">
                                <div class="card-buy-row">
                                    <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <div class="qty-stepper" data-qty-stepper>
                                        <button type="button" class="qty-btn" data-qty-minus aria-label="Kurangi">&minus;</button>
                                        <input type="text" inputmode="numeric" name="quantity" value="1" class="qty-input" data-qty-input readonly>
                                        <button type="button" class="qty-btn" data-qty-plus aria-label="Tambah">+</button>
                                    </div>
                                </div>
                                <div class="card-buy-actions">
                                    <button class="btn btn-sm btn-outline-brand card-add-btn" title="Tambah ke keranjang" type="submit"><i class="fa fa-cart-plus"></i> Tambah</button>
                                    <button class="btn btn-sm btn-brand card-buy-btn" title="Beli langsung" type="submit" name="buy_now" value="1"><i class="fa fa-bolt"></i> Beli</button>
                                </div>
                            </form>
                        @else
                            <div class="card-buy">
                                <div class="card-buy-row"><span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span></div>
                                <div class="card-buy-actions">
                                    <button class="btn btn-sm btn-outline-brand card-add-btn" title="Tambah ke keranjang" type="button" data-auth-open><i class="fa fa-cart-plus"></i> Tambah</button>
                                    <button class="btn btn-sm btn-brand card-buy-btn" title="Beli langsung" type="button" data-auth-open><i class="fa fa-bolt"></i> Beli</button>
                                </div>
                            </div>
                        @endauth
                    </div>
                </article>
            @empty
                <p class="text-muted">Belum ada produk tersedia.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</section>

@auth('customer')
<section id="quick-actions" class="member-section">
    <div class="quick-actions"><div class="font-weight-bold mb-2" style="grid-column:1/-1">⚡ Pintasan Layanan & Aksi Cepat</div><a class="quick-action" href="{{ route('customer.claims.index') }}"><span class="quick-action-icon">✓</span><span><strong>Klaim Garansi Pengrajin</strong><span>Garansi reparasi anyaman dan jahitan.</span></span></a><a class="quick-action" href="#impact"><span class="quick-action-icon">♻</span><span><strong>Donasi Limbah Rumah Tangga</strong><span>Kirim material bersih, dapatkan poin.</span></span></a><a class="quick-action" href="#about"><span class="quick-action-icon">?</span><span><strong>Hubungi Admin EcoCraft</strong><span>Konsultasi kurasi atau bantuan pengiriman.</span></span></a></div>
</section>
<section id="seller-center" class="member-section seller-invite">
    <div>
        <div class="eyebrow" style="color:var(--accent)">Seller Center</div>
        <h2>Bangun toko berkelanjutanmu</h2>
        <p>Customer EcoCraft dapat mendaftarkan toko atau masuk sebagai seller yang sudah disetujui admin.</p>
    </div>
    <div class="seller-actions"><a class="btn btn-brand" href="{{ route('seller.register.form') }}">Daftar sebagai Seller</a><a class="btn btn-outline-brand" href="{{ route('seller.login') }}">Login Seller</a></div>
</section>
@endauth

@guest('customer')
<section id="about" class="guest-section">
    <div class="page-wrap">
        <div class="guest-trust">
            <div><strong>&#9851; 100% Bahan Berkelanjutan</strong><span>Terverifikasi adil untuk lingkungan</span></div>
            <div><strong>&#9825; Keadilan Ekonomi UMKM</strong><span>70% margin langsung diterima pengrajin</span></div>
            <div><strong>&#9633; Bungkus Bebas Plastik</strong><span>Karton daur ulang & fitosintetik</span></div>
            <div><strong>&#8679; Pengiriman Karbon Netral</strong><span>Kompensasi perjalanan menuju rumahmu</span></div>
        </div>
    </div>
</section>

<section id="faq" class="guest-section guest-section-soft">
    <div class="page-wrap">
        <div class="guest-section-title"><div class="eyebrow" style="color:var(--accent)">Pusat informasi</div><h2>Pertanyaan seputar berbelanja di EcoCraft</h2></div>
        <div class="guest-faq">
            <details><summary>Apakah saya bisa berbelanja tanpa mendaftar akun terlebih dahulu?</summary><p>Guest bisa menjelajah katalog dan melihat detail produk. Untuk menambahkan produk ke keranjang dan checkout, kamu perlu masuk atau membuat akun terlebih dahulu.</p></details>
            <details><summary>Bagaimana cara kerja voucher selamat datang?</summary><p>Voucher tersedia untuk akun customer baru sesuai kebijakan promosi yang sedang aktif.</p></details>
            <details><summary>Apakah seluruh produk terjamin aman dan higienis?</summary><p>Produk yang tampil di katalog telah melewati proses verifikasi admin dan berasal dari seller yang terdaftar.</p></details>
            <details><summary>Berapa lama estimasi pengiriman dan ke mana saja jangkauannya?</summary><p>Estimasi bergantung pada alamat, seller, dan pilihan ekspedisi yang tersedia saat checkout.</p></details>
        </div>
    </div>
</section>

<section class="guest-section">
    <div class="page-wrap"><div class="guest-cta"><div><div class="eyebrow" style="color:#a1d1b8">Langkah kecil berdampak nyata</div><h2>Siap menjadi konsumen sadar iklim?</h2><p>Daftar sekarang untuk menyimpan alamat dan mendapatkan akses pengalaman komunitas EcoCraft.</p></div><div class="guest-cta-actions"><a class="btn btn-accent" href="{{ route('register') }}">Buat akun gratis sekarang</a><a class="btn btn-outline-light" href="#catalog">Jelajahi produk dulu</a></div></div></div>
</section>
<div class="guest-modal" data-auth-modal aria-hidden="true"><div class="guest-modal-card"><button class="guest-modal-close" type="button" data-auth-close aria-label="Tutup">&times;</button><div class="eyebrow">Aksi ini membutuhkan akun</div><h2>Masuk untuk melanjutkan</h2><p class="small text-muted">Buat akun atau masuk agar produk tersimpan di keranjang dan checkout dapat diproses.</p><div class="d-grid gap-2"><a class="btn btn-brand" href="{{ route('login') }}">Masuk ke akun</a><a class="btn btn-outline-brand" href="{{ route('register') }}">Daftar akun baru</a></div></div></div>
@endguest
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-auth-tab]').forEach(function (tab) {
    tab.addEventListener('click', function () {
        document.querySelectorAll('[data-auth-tab]').forEach(function (item) { item.classList.remove('active'); });
        document.querySelectorAll('[data-auth-form]').forEach(function (form) { form.classList.remove('active'); });
        tab.classList.add('active');
        document.querySelector('[data-auth-form="' + tab.dataset.authTab + '"]').classList.add('active');
    });
});
document.querySelectorAll('[data-auth-open]').forEach(function (button) {
    button.addEventListener('click', function () { document.querySelector('[data-auth-modal]').classList.add('open'); });
});
document.querySelectorAll('[data-auth-close]').forEach(function (button) {
    button.addEventListener('click', function () { document.querySelector('[data-auth-modal]').classList.remove('open'); });
});
document.querySelectorAll('[data-auth-switch]').forEach(function (link) {
    link.addEventListener('click', function () {
        var target = document.querySelector('[data-auth-tab="' + link.dataset.authSwitch + '"]');
        if (target) target.click();
        document.querySelector('[data-auth-modal]')?.classList.remove('open');
    });
});
document.querySelector('[data-auth-modal]')?.addEventListener('click', function (event) {
    if (event.target === this) this.classList.remove('open');
});
var provinceCities = {
    'Aceh': ['Banda Aceh', 'Langsa', 'Lhokseumawe', 'Sabang', 'Subulussalam'],
    'Sumatera Utara': ['Medan', 'Binjai', 'Pematangsiantar', 'Sibolga', 'Tebing Tinggi'],
    'Sumatera Barat': ['Padang', 'Bukittinggi', 'Padang Panjang', 'Pariaman', 'Payakumbuh'],
    'Riau': ['Pekanbaru', 'Dumai'],
    'Kepulauan Riau': ['Batam', 'Tanjungpinang'],
    'Jambi': ['Jambi', 'Sungai Penuh'],
    'Sumatera Selatan': ['Palembang', 'Lubuklinggau', 'Pagar Alam', 'Prabumulih'],
    'Bengkulu': ['Bengkulu'],
    'Lampung': ['Bandar Lampung', 'Metro'],
    'Bangka Belitung': ['Pangkalpinang'],
    'DKI Jakarta': ['Jakarta Barat', 'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Utara'],
    'Jawa Barat': ['Bandung', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya'],
    'Jawa Tengah': ['Semarang', 'Magelang', 'Pekalongan', 'Salatiga', 'Surakarta', 'Tegal'],
    'DI Yogyakarta': ['Yogyakarta'],
    'Jawa Timur': ['Batu', 'Blitar', 'Kediri', 'Malang', 'Mojokerto', 'Pasuruan', 'Probolinggo', 'Surabaya'],
    'Banten': ['Cilegon', 'Serang', 'Tangerang', 'Tangerang Selatan'],
    'Bali': ['Denpasar', 'Singaraja'],
    'Nusa Tenggara Barat': ['Bima', 'Mataram'],
    'Nusa Tenggara Timur': ['Kupang'],
    'Kalimantan Barat': ['Pontianak', 'Singkawang'],
    'Kalimantan Tengah': ['Palangka Raya'],
    'Kalimantan Selatan': ['Banjarbaru', 'Banjarmasin'],
    'Kalimantan Timur': ['Balikpapan', 'Bontang', 'Samarinda'],
    'Kalimantan Utara': ['Tarakan'],
    'Sulawesi Utara': ['Bitung', 'Kotamobagu', 'Manado', 'Tomohon'],
    'Sulawesi Tengah': ['Palu'],
    'Sulawesi Selatan': ['Makassar', 'Palopo', 'Parepare'],
    'Sulawesi Tenggara': ['Baubau', 'Kendari'],
    'Gorontalo': ['Gorontalo'],
    'Maluku': ['Ambon', 'Tual'],
    'Maluku Utara': ['Ternate', 'Tidore Kepulauan'],
    'Papua': ['Jayapura'],
    'Papua Barat': ['Manokwari', 'Sorong']
};
function initializeLocationFields(provinceId, cityId) {
    var provinceSelect = document.getElementById(provinceId);
    var citySelect = document.getElementById(cityId);
    if (!provinceSelect || !citySelect) return;

    Object.keys(provinceCities).forEach(function (province) {
        provinceSelect.add(new Option(province, province));
    });

    function populateCities(province, selectedCity) {
        citySelect.innerHTML = '<option value="">Pilih kota</option>';
        (provinceCities[province] || []).forEach(function (city) {
            citySelect.add(new Option(city, city, false, city === selectedCity));
        });
        citySelect.disabled = !(provinceCities[province] || []).length;
    }

    provinceSelect.addEventListener('change', function () { populateCities(this.value, ''); });
    var oldProvince = @json(old('province'));
    var oldCity = @json(old('city'));
    if (oldProvince && provinceCities[oldProvince]) {
        provinceSelect.value = oldProvince;
        populateCities(oldProvince, oldCity);
    }
}

initializeLocationFields('guest-register-province', 'guest-register-city');
initializeLocationFields('seller-province', 'seller-city');

document.querySelectorAll('[data-seller-tab]').forEach(function (tab) {
    tab.addEventListener('click', function () {
        var targetId = this.dataset.sellerTab;
        document.querySelectorAll('[data-seller-tab]').forEach(function (item) {
            var active = item === tab;
            item.classList.toggle('is-active', active);
            item.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        document.querySelectorAll('.seller-tab-panel').forEach(function (panel) {
            var active = panel.id === targetId;
            panel.classList.toggle('is-active', active);
            panel.hidden = !active;
        });
        document.getElementById(targetId)?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
});

// "Muat lebih banyak": tampilkan 5 kartu tersembunyi berikutnya di baris sorotan member
document.querySelectorAll('[data-load-more]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var row = document.getElementById(btn.getAttribute('data-load-more'));
        if (!row) return;

        var hidden = row.querySelectorAll('.community-story-card.is-hidden');
        var shown = 0;
        while (shown < 5 && shown < hidden.length) {
            hidden[shown].classList.remove('is-hidden');
            shown++;
        }

        if (row.querySelectorAll('.community-story-card.is-hidden').length === 0) {
            btn.style.display = 'none';
        }
    });
});

// "Lihat semua" pada pintasan cepat (mobile): tampilkan ikon tersembunyi lalu sembunyikan tombol
document.querySelector('[data-quick-more]')?.addEventListener('click', function () {
    document.querySelectorAll('#quickGrid .mqg-item.is-hidden').forEach(function (item) {
        item.classList.remove('is-hidden');
    });
    this.style.display = 'none';
});

// Stepper quantity pada kartu produk (− / +)
document.querySelectorAll('[data-qty-stepper]').forEach(function (stepper) {
    var input = stepper.querySelector('[data-qty-input]');
    var max = 999;
    stepper.querySelector('[data-qty-minus]')?.addEventListener('click', function () {
        var v = parseInt(input.value, 10) || 1;
        input.value = Math.max(1, v - 1);
    });
    stepper.querySelector('[data-qty-plus]')?.addEventListener('click', function () {
        var v = parseInt(input.value, 10) || 1;
        input.value = Math.min(max, v + 1);
    });
});

// Sapaan daerah pada banner member: animasi ketik lalu hapus, bergantian
(function () {
    var el = document.querySelector('[data-greeting-typist]');
    if (!el) return;
    var words = @json($greetings ?? []);
    if (!words.length) return;
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var sep = document.querySelector('[data-greeting-sep]');
    var wordIndex = 0;
    var chars = words[0].length;
    var deleting = true;

    function render(text) {
        el.textContent = text;
        if (sep) sep.style.visibility = text ? 'visible' : 'hidden';
    }

    function tick() {
        var word = words[wordIndex];
        if (deleting) {
            chars--;
            render(word.slice(0, Math.max(0, chars)));
            if (chars <= 0) {
                deleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                chars = 0;
                setTimeout(tick, 550);
                return;
            }
            setTimeout(tick, 75);
            return;
        }
        chars++;
        render(word.slice(0, chars));
        if (chars >= word.length) {
            deleting = true;
            setTimeout(tick, 2200);
            return;
        }
        setTimeout(tick, 150);
    }

    setTimeout(tick, 1800);
})();
</script>
@endpush
