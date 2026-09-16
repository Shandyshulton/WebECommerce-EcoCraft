@extends('layouts.customer')

@section('title', 'Tentang Kami | EcoCraft')

@push('styles')
<style>
    .about-page { padding:36px 0 72px; }
    .about-back { display:inline-flex; align-items:center; gap:8px; margin-bottom:22px; color:var(--muted); font-size:12px; font-weight:700; }
    .about-back:hover { color:var(--brand); }

    .about-hero { display:grid; grid-template-columns:minmax(0,1.05fr) minmax(320px,.95fr); gap:44px; align-items:center; }
    .about-hero h1 { margin:12px 0 16px; font:600 clamp(38px,4.8vw,60px)/1.04 'EB Garamond',Georgia,serif; }
    .about-hero .lead { max-width:58ch; margin:0; color:var(--muted); font-size:16px; line-height:1.8; }
    .about-hero-media { aspect-ratio:4/3; border:1px solid var(--line); border-radius:22px; background:url('{{ asset('assets/images/collection/Tas Kemasan Kopi.jpg') }}') center/cover; }
    .about-stats { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin-top:28px; padding:18px; background:var(--surface); border-radius:14px; }
    .about-stat strong { display:block; color:var(--brand); font:600 30px/1.1 'EB Garamond',Georgia,serif; }
    .about-stat span { color:var(--muted); font-size:10.5px; font-weight:800; letter-spacing:.09em; text-transform:uppercase; }

    .about-section { padding:58px 0; }
    .about-section-soft { background:var(--surface); }
    .about-head { max-width:720px; margin:0 auto 32px; text-align:center; }
    .about-head h2 { margin:8px 0 10px; font:600 clamp(26px,3.2vw,40px)/1.18 'EB Garamond',Georgia,serif; }
    .about-head p { margin:0; color:var(--muted); font-size:14px; line-height:1.8; }

    .about-story { max-width:68ch; margin:0 auto; }
    .about-story p { margin:0 0 18px; font-size:15.5px; line-height:1.9; color:#26342b; }
    .about-story p:last-child { margin-bottom:0; }
    .about-story strong { color:var(--brand); }

    .about-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }
    .about-card { padding:22px; background:#fff; border:1px solid var(--line); border-radius:14px; }
    .about-card-icon { display:grid; place-items:center; width:38px; height:38px; margin-bottom:14px; border-radius:10px; background:#dff1e5; color:var(--brand); font-size:16px; }
    .about-card:nth-child(2) .about-card-icon { background:#ffdbd0; color:var(--accent); }
    .about-card:nth-child(3) .about-card-icon { background:#c6ebd7; }
    .about-card:nth-child(4) .about-card-icon { background:#ece7df; }
    .about-card h3 { margin:0 0 8px; font-size:16px; }
    .about-card p { margin:0; color:var(--muted); font-size:12.5px; line-height:1.7; }

    .about-steps { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }
    .about-step { padding:22px 20px; background:#fff; border:1px solid var(--line); border-radius:14px; }
    .about-step-num { display:grid; place-items:center; width:30px; height:30px; margin-bottom:12px; border-radius:50%; background:var(--brand); color:#fff; font:700 13px 'Plus Jakarta Sans'; }
    .about-step h3 { margin:0 0 7px; font-size:15px; }
    .about-step p { margin:0; color:var(--muted); font-size:12px; line-height:1.7; }

    .about-cta { display:flex; align-items:center; justify-content:space-between; gap:22px; padding:28px; color:#fff; background:var(--brand); border-radius:16px; }
    .about-cta h2 { margin:0 0 6px; color:#fff; font:600 clamp(24px,3vw,34px)/1.2 'EB Garamond',Georgia,serif; }
    .about-cta p { margin:0; color:rgba(255,255,255,.78); font-size:13px; }
    .about-cta-actions { display:flex; gap:10px; flex-shrink:0; }
    .about-cta .btn-accent { color:#fff; background:var(--accent); border-color:var(--accent); }
    .about-cta .btn-accent:hover { background:#b25e44; border-color:#b25e44; }

    @media (max-width:1000px) {
        .about-hero { grid-template-columns:1fr; gap:28px; }
        .about-grid, .about-steps { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media (max-width:640px) {
        .about-page { padding:26px 0 56px; }
        .about-section { padding:44px 0; }
        .about-stats { grid-template-columns:1fr; }
        .about-grid, .about-steps { grid-template-columns:1fr; }
        .about-cta { display:block; }
        .about-cta-actions { margin-top:18px; flex-wrap:wrap; }
        .about-cta-actions .btn { flex:1; }
    }
</style>
@endpush

@section('content')
<main class="about-page">
    <div class="page-wrap">
        <a class="about-back" href="{{ route('customer.dashboard') }}">&larr; Kembali ke beranda</a>

        <section class="about-hero">
            <div>
                <div class="eyebrow">Tentang EcoCraft</div>
                <h1>Karya kecil, dampak yang bertumbuh</h1>
                <p class="lead">EcoCraft menghubungkan pengrajin Nusantara dengan pembeli yang peduli. Kami mengurasi karya dari material yang dirawat kembali, supaya setiap transaksi menekan limbah sekaligus menopang ekonomi lokal.</p>
                <div class="about-stats">
                    <div class="about-stat"><strong>{{ number_format($stats['products']) }}</strong><span>Produk terkurasi</span></div>
                    <div class="about-stat"><strong>{{ number_format($stats['sellers']) }}</strong><span>Mitra pengrajin</span></div>
                    <div class="about-stat"><strong>{{ number_format($stats['trees']) }}</strong><span>Pohon tertanam</span></div>
                </div>
            </div>
            <div class="about-hero-media" role="img" aria-label="Karya kerajinan daur ulang EcoCraft"></div>
        </section>
    </div>

    <section class="about-section about-section-soft">
        <div class="page-wrap">
            <div class="about-head">
                <div class="eyebrow" style="color:var(--accent)">Cerita kami</div>
                <h2>Berawal dari limbah yang sayang dibuang</h2>
                <p>Kami percaya barang yang dianggap selesai bisa punya hidup kedua di tangan yang tepat.</p>
            </div>
            <div class="about-story">
                <p>EcoCraft lahir dari pengamatan sederhana: banyak material masih layak pakai berakhir di tempat pembuangan, sementara pengrajin lokal kekurangan akses pasar. Kami mempertemukan keduanya dalam satu katalog yang dikurasi.</p>
                <p>Setiap produk melewati proses <strong>kurasi material</strong>, <strong>verifikasi pengrajin</strong>, dan <strong>uji kualitas</strong> sebelum tampil. Tujuannya bukan sekadar menjual, tapi memastikan karya yang kamu terima benar-benar bertanggung jawab—baik bagi lingkungan maupun bagi orang yang membuatnya.</p>
                <p>Lebih dari sekadar marketplace, kami ingin membangun kebiasaan: memilih produk yang tahan lama, tahu asalnya, dan memberi manfaat lebih luas. Setiap pilihan kecil di EcoCraft adalah bagian dari gerakan konsumsi yang lebih sadar.</p>
            </div>
        </div>
    </section>

    <section class="about-section">
        <div class="page-wrap">
            <div class="about-head">
                <div class="eyebrow" style="color:var(--accent)">Yang kami pegang</div>
                <h2>Empat prinsip di setiap karya</h2>
                <p>Prinsip ini jadi penyaring kami sebelum sebuah karya masuk katalog.</p>
            </div>
            <div class="about-grid">
                <article class="about-card">
                    <span class="about-card-icon"><i class="fa fa-recycle" aria-hidden="true"></i></span>
                    <h3>100% bahan berkelanjutan</h3>
                    <p>Material daur ulang dan terbarukan, terverifikasi aman serta adil untuk lingkungan.</p>
                </article>
                <article class="about-card">
                    <span class="about-card-icon"><i class="fa fa-hand-holding-heart" aria-hidden="true"></i></span>
                    <h3>Keadilan ekonomi pengrajin</h3>
                    <p>Sekitar 70% margin mengalir langsung ke pengrajin dan UMKM mitra kami.</p>
                </article>
                <article class="about-card">
                    <span class="about-card-icon"><i class="fa fa-box" aria-hidden="true"></i></span>
                    <h3>Bungkus bebas plastik</h3>
                    <p>Kemasan karton daur ulang dan material fitosintetik, tanpa plastik sekali pakai.</p>
                </article>
                <article class="about-card">
                    <span class="about-card-icon"><i class="fa fa-truck-fast" aria-hidden="true"></i></span>
                    <h3>Pengiriman karbon netral</h3>
                    <p>Setiap perjalanan pesanan dikompensasi untuk menekan jejak emisi pengiriman.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="about-section about-section-soft">
        <div class="page-wrap">
            <div class="about-head">
                <div class="eyebrow" style="color:var(--accent)">Cara kami mengurasi</div>
                <h2>Dari limbah menjadi karya layak pakai</h2>
                <p>Empat tahap yang kami lakukan sebelum sebuah produk sampai ke tanganmu.</p>
            </div>
            <div class="about-steps">
                <article class="about-step"><span class="about-step-num">1</span><h3>Kurasi material</h3><p>Menilai asal, keamanan, dan dampak lingkungan dari setiap bahan yang dipakai.</p></article>
                <article class="about-step"><span class="about-step-num">2</span><h3>Verifikasi pengrajin</h3><p>Seller dicek dan disetujui admin sebelum karyanya tampil di katalog.</p></article>
                <article class="about-step"><span class="about-step-num">3</span><h3>Uji kualitas</h3><p>Kerapian, daya tahan, dan fungsi produk diperiksa sebelum dipasarkan.</p></article>
                <article class="about-step"><span class="about-step-num">4</span><h3>Kirim sadar iklim</h3><p>Dikemas bebas plastik dan dikirim dengan kompensasi karbon.</p></article>
            </div>
        </div>
    </section>

    <div class="page-wrap">
        <section class="about-cta">
            <div>
                <h2>Siap jadi bagian gerakan ini?</h2>
                <p>Jelajahi karya terkurasi, atau buat akun untuk menyimpan keranjang dan melacak pesananmu.</p>
            </div>
            <div class="about-cta-actions">
                <a class="btn btn-outline-light" href="{{ route('catalog.index') }}">Jelajahi katalog</a>
                @auth('customer')
                    <a class="btn btn-accent" href="{{ route('customer.dashboard') }}#catalog">Belanja sekarang</a>
                @else
                    <a class="btn btn-accent" href="{{ route('register') }}">Buat akun gratis</a>
                @endauth
            </div>
        </section>
    </div>
</main>
@endsection
