<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EcoCraft')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    @stack('styles')
</head>
<body>
<div class="customer-shell">
    <header class="customer-nav">
        <div class="customer-nav-inner">
            <a class="brand" href="{{ route('customer.dashboard') }}">
                <img src="{{ asset('assets/logo/ecocraft-logo.png') }}" alt="EcoCraft">
            </a>
            <form class="mobile-search" action="{{ route('customer.dashboard') }}#catalog" method="GET" role="search">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk, kategori, atau material" aria-label="Cari produk">
                <button type="submit" aria-label="Cari"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
            </form>
            <nav class="nav-links" aria-label="Customer navigation">
                <a href="{{ route('customer.dashboard') }}"><i class="fa fa-home"></i><span>Home</span></a>
                <a href="{{ route('catalog.index') }}"><i class="fa fa-th-large"></i><span>Katalog</span></a>
                <a href="{{ auth('customer')->check() ? route('track.track') : route('login') }}"><i class="fa fa-receipt"></i><span>Pembelian</span></a>
                <a href="{{ auth('customer')->check() ? route('customer.wallet') : route('login') }}"><i class="fa fa-coins"></i><span>Dompet</span></a>
                <a href="{{ route('customer.dashboard') }}#stories"><i class="fa fa-shopping-bag"></i><span>Cerita Pengrajin</span></a>
                <a href="{{ route('customer.dashboard') }}#impact"><i class="fa fa-leaf"></i><span>Dampak Lingkungan</span></a>
                <a href="{{ route('about') }}"><i class="fa fa-info-circle"></i><span>Tentang Kami</span></a>
            </nav>
            <div class="nav-actions @auth('customer') auth-actions @endauth">
                @auth('customer')
                    @php($navCustomer = Auth::guard('customer')->user())
                    @php($navAvatar = $navCustomer->profile_image ? asset('storage/' . $navCustomer->profile_image) : asset('assets/logo/Logo_Eco-Craft-removebg-preview 1.png'))
                    @php($navUnreadChats = \App\Models\ProductInquiryMessage::where('sender_type', 'seller')->whereNull('read_at')->whereHas('inquiry', fn ($q) => $q->where('customer_id', $navCustomer->id_customers))->count())
                    <a class="cart-icon chat-icon-link" href="{{ route('customer.inquiries.index') }}" aria-label="Chat seller" title="Chat seller">
                        <i class="fa fa-comments" aria-hidden="true"></i>
                        @if($navUnreadChats > 0)<span class="chat-badge">{{ $navUnreadChats > 9 ? '9+' : $navUnreadChats }}</span>@endif
                    </a>
                    <a class="cart-icon" href="{{ route('cart.show') }}" aria-label="Cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                    <details class="customer-profile-menu">
                        <summary aria-label="Buka menu akun">
                            <span class="account-chip"><img src="{{ $navAvatar }}" alt="{{ $navCustomer->name_customers }}"></span>
                        </summary>
                        <div class="profile-popover">
                            <div class="popover-head"><strong>{{ $navCustomer->name_customers }}</strong><small>Sahabat Pengrajin</small></div>
                            <a href="{{ route('customer.profile') }}"><i class="fa fa-user" aria-hidden="true"></i>Edit profile</a>
                            <a href="{{ route('track.track') }}"><i class="fa fa-receipt" aria-hidden="true"></i>Riwayat Pembelian</a>
                            <a href="{{ route('customer.addresses.index') }}"><i class="fa fa-location-dot" aria-hidden="true"></i>Alamat Pengiriman</a>
                            <a href="{{ route('customer.wallet') }}"><i class="fa fa-coins" aria-hidden="true"></i>Dompet Sirkular</a>
                            <a href="{{ route('customer.inquiries.index') }}"><i class="fa fa-comments" aria-hidden="true"></i>Pertanyaan Produk</a>
                            <a href="{{ route('customer.claims.index') }}"><i class="fa fa-shield-heart" aria-hidden="true"></i>Klaim Garansi</a>
                            <a href="{{ route('seller.register.form') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Jadi Seller</a>
                            <form action="{{ route('logout') }}" method="POST" class="popover-logout">@csrf<button type="submit"><i class="fa fa-right-from-bracket" aria-hidden="true"></i>Keluar</button></form>
                        </div>
                    </details>
                @else
                    <a class="cart-icon" href="{{ route('login') }}" aria-label="Chat seller" title="Chat seller"><i class="fa fa-comments" aria-hidden="true"></i></a>
                    <a class="cart-icon" href="{{ route('login') }}" aria-label="Keranjang" title="Keranjang"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                    <a class="btn btn-outline-brand btn-sm" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-brand btn-sm" href="{{ route('register') }}">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </header>
    @php($quickMember = auth('customer')->check())
    {{-- Pintasan cepat: bentuk menu utama di bawah desktop. Isinya sengaja
         lengkap supaya tidak ada menu yang hilang saat baris menu header
         disembunyikan (<=1024px). --}}
    <nav class="quick-nav" aria-label="Pintasan cepat">
        <a class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}"><i class="fa fa-home"></i><span>Home</span></a>
        <a class="{{ request()->routeIs('catalog.index') || request()->routeIs('product.show') ? 'active' : '' }}" href="{{ route('catalog.index') }}"><i class="fa fa-th-large"></i><span>Katalog</span></a>
        <a class="{{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ $quickMember ? route('cart.show') : route('login') }}"><i class="fa fa-shopping-cart"></i><span>Keranjang</span></a>
        <a class="{{ request()->routeIs('customer.inquiries.*') ? 'active' : '' }}" href="{{ $quickMember ? route('customer.inquiries.index') : route('login') }}"><i class="fa fa-comments"></i><span>Chat Seller</span></a>
        <a class="{{ request()->routeIs('track.track') ? 'active' : '' }}" href="{{ $quickMember ? route('track.track') : route('login') }}"><i class="fa fa-receipt"></i><span>Pembelian</span></a>
        <a class="{{ request()->routeIs('customer.wallet') ? 'active' : '' }}" href="{{ $quickMember ? route('customer.wallet') : route('login') }}"><i class="fa fa-coins"></i><span>Dompet</span></a>
        <a href="{{ route('customer.dashboard') }}#stories"><i class="fa fa-shopping-bag"></i><span>Cerita Pengrajin</span></a>
        <a href="{{ route('customer.dashboard') }}#impact"><i class="fa fa-leaf"></i><span>Dampak Lingkungan</span></a>
        <a class="{{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}"><i class="fa fa-info-circle"></i><span>Tentang Kami</span></a>
        <a class="is-hidden {{ request()->routeIs('customer.profile') ? 'active' : '' }}" href="{{ $quickMember ? route('customer.profile') : route('login') }}"><i class="fa fa-user"></i><span>Akun</span></a>
        <a class="is-hidden {{ request()->routeIs('customer.addresses.*') ? 'active' : '' }}" href="{{ $quickMember ? route('customer.addresses.index') : route('login') }}"><i class="fa fa-location-dot"></i><span>Alamat</span></a>
        <a class="is-hidden" href="{{ route('seller.register.form') }}"><i class="fa fa-store"></i><span>Jadi Seller</span></a>
        <button type="button" class="quick-nav-more" data-quick-nav-more aria-expanded="false"><i class="fa fa-plus" aria-hidden="true"></i> Muat lebih banyak</button>
    </nav>
    <main class="customer-main">@yield('content')</main>
    <footer @auth('customer') id="about" @endauth class="site-footer">
        <div class="page-wrap">
            <div class="footer-grid">
                <div><h3>EcoCraft</h3><p class="small">Karya lokal, material berkelanjutan, dan belanja yang lebih bermakna.</p></div>
                <div><h4>Jelajahi</h4><a href="{{ route('catalog.index') }}">Katalog produk</a><a href="{{ route('customer.dashboard') }}#stories">Cerita pengrajin</a><a href="{{ route('customer.dashboard') }}#impact">Dampak lingkungan</a><a href="{{ route('about') }}">Tentang kami</a></div>
                <div><h4>Bantuan</h4><a href="{{ route('customer.dashboard') }}#faq">FAQ</a><a href="{{ route('track.track') }}">Lacak pesanan</a><a href="{{ route('login') }}">Masuk akun</a></div>
                <div><h4>Kebijakan</h4><a href="{{ route('policy.privacy') }}">Kebijakan Privasi</a><a href="{{ route('policy.terms') }}">Ketentuan Layanan</a><a href="#">Instagram · TikTok</a></div>
            </div>
            <div class="footer-bottom"><span>&copy; {{ date('Y') }} EcoCraft. Semua hak dilindungi.</span><span>Dirancang untuk konsumsi yang lebih sadar.</span></div>
        </div>
    </footer>
    @php($isMember = Auth::guard('customer')->check())
    @php($bnUnread = 0)
    @if($isMember)
        @php($bnCustomer = Auth::guard('customer')->user())
        @php($bnUnread = \App\Models\ProductInquiryMessage::where('sender_type', 'seller')->whereNull('read_at')->whereHas('inquiry', fn ($q) => $q->where('customer_id', $bnCustomer->id_customers))->count())
    @endif
    <nav class="mobile-bottom-nav" aria-label="Navigasi utama">
        <a class="mbn-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
            <i class="fa fa-home"></i><span>Beranda</span>
        </a>
        <a class="mbn-item {{ request()->routeIs('catalog.index') || request()->routeIs('product.show') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
            <i class="fa fa-th-large"></i><span>Katalog</span>
        </a>
        <a class="mbn-item {{ request()->routeIs('track.track') ? 'active' : '' }}" href="{{ $isMember ? route('track.track') : route('login') }}">
            <i class="fa fa-receipt"></i><span>Pembelian</span>
        </a>
        <a class="mbn-item {{ request()->routeIs('customer.inquiries.*') ? 'active' : '' }}" href="{{ $isMember ? route('customer.inquiries.index') : route('login') }}">
            <span class="mbn-icon-wrap"><i class="fa fa-comments"></i>@if($bnUnread > 0)<span class="mbn-badge">{{ $bnUnread > 9 ? '9+' : $bnUnread }}</span>@endif</span><span>Chat</span>
        </a>
        <a class="mbn-item {{ request()->routeIs('customer.profile') ? 'active' : '' }}" href="{{ $isMember ? route('customer.profile') : route('login') }}">
            <i class="fa fa-user"></i><span>Akun</span>
        </a>
    </nav>
</div>
@stack('scripts')
<script>
    // "Muat lebih banyak" pada pintasan cepat: tampilkan sisa menu lalu tombolnya hilang.
    document.querySelector('[data-quick-nav-more]')?.addEventListener('click', function () {
        document.querySelectorAll('.quick-nav a.is-hidden').forEach(function (item) {
            item.classList.remove('is-hidden');
        });
        this.setAttribute('aria-expanded', 'true');
        this.remove();
    });

    // Animasi slider komunitas: kartu yang paling dekat tepi awal rel tampil
    // paling besar (`--rail-focus` = 1), kartu berikutnya mengecil dan meredup
    // seiring rel digeser. Dihitung sekali per frame, dan dilewati sama sekali
    // saat prefers-reduced-motion aktif sehingga semua kartu sama besar.
    (function () {
        if (!window.matchMedia || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        var tracks = Array.prototype.slice.call(document.querySelectorAll('.community-row-track, .community-highlights-grid'));
        if (!tracks.length) return;

        function paint(track) {
            var cards = track.children;
            if (!cards.length) return;
            var base = cards[0].offsetLeft;
            var span = Math.max(track.clientWidth * 0.55, 1);

            for (var i = 0; i < cards.length; i++) {
                var delta = (cards[i].offsetLeft - base) - track.scrollLeft;
                var focus = Math.max(0, 1 - Math.abs(delta) / span);
                cards[i].style.setProperty('--rail-focus', focus.toFixed(3));
            }
        }

        tracks.forEach(function (track) {
            var queued = false;

            function schedule() {
                if (queued) return;
                queued = true;
                requestAnimationFrame(function () {
                    queued = false;
                    paint(track);
                });
            }

            track.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule);
            paint(track);
        });
    })();
</script>
</body>
</html>
