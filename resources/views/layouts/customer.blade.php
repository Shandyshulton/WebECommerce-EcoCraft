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
    <header class="customer-nav" data-customer-nav>
        <div class="customer-nav-inner">
            <a class="brand" href="{{ route('customer.dashboard') }}">
                <img src="{{ asset('assets/logo/ecocraft-logo.png') }}" alt="EcoCraft">
            </a>
            <form class="mobile-search" action="{{ route('customer.dashboard') }}#catalog" method="GET" role="search">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk, kategori, atau material" aria-label="Cari produk">
                <button type="submit" aria-label="Cari"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
            </form>
            <button class="mobile-menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-label="Buka menu">☰</button>
            <nav class="nav-links" aria-label="Customer navigation">
                <a href="{{ route('customer.dashboard') }}"><i class="fa fa-home"></i><span>Home</span></a>
                <a href="{{ route('catalog.index') }}"><i class="fa fa-th-large"></i><span>Katalog</span></a>
                @auth('customer')
                    <a href="{{ route('track.track') }}"><i class="fa fa-receipt"></i><span>Pembelian</span></a>
                    <a href="{{ route('customer.wallet') }}"><i class="fa fa-coins"></i><span>Dompet</span></a>
                @endauth
                <a href="{{ route('customer.dashboard') }}#stories"><i class="fa fa-shopping-bag"></i><span>Cerita Pengrajin</span></a>
                <a href="{{ route('customer.dashboard') }}#impact"><i class="fa fa-leaf"></i><span>Dampak Lingkungan</span></a>
                <a href="{{ route('customer.dashboard') }}#about"><i class="fa fa-info-circle"></i><span>Tentang Kami</span></a>
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
                            <a href="{{ route('customer.wallet') }}"><i class="fa fa-coins" aria-hidden="true"></i>Dompet Sirkular</a>
                            <a href="{{ route('customer.inquiries.index') }}"><i class="fa fa-comments" aria-hidden="true"></i>Pertanyaan Produk</a>
                            <a href="{{ route('seller.register.form') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Jadi Seller</a>
                            <form action="{{ route('logout') }}" method="POST" class="popover-logout">@csrf<button type="submit"><i class="fa fa-right-from-bracket" aria-hidden="true"></i>Keluar</button></form>
                        </div>
                    </details>
                @else
                    <a class="btn btn-outline-brand btn-sm" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-brand btn-sm" href="{{ route('register') }}">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </header>
    <main class="customer-main">@yield('content')</main>
    <footer @auth('customer') id="about" @endauth class="site-footer">
        <div class="page-wrap">
            <div class="footer-grid">
                <div><h3>EcoCraft</h3><p class="small">Karya lokal, material berkelanjutan, dan belanja yang lebih bermakna.</p></div>
                <div><h4>Jelajahi</h4><a href="{{ route('catalog.index') }}">Katalog produk</a><a href="{{ route('customer.dashboard') }}#stories">Cerita pengrajin</a><a href="{{ route('customer.dashboard') }}#impact">Dampak lingkungan</a></div>
                <div><h4>Bantuan</h4><a href="{{ route('customer.dashboard') }}#faq">FAQ</a><a href="{{ route('track.track') }}">Lacak pesanan</a><a href="{{ route('login') }}">Masuk akun</a></div>
                <div><h4>Kebijakan</h4><a href="#">Privasi</a><a href="#">Ketentuan layanan</a><a href="#">Instagram · TikTok</a></div>
            </div>
            <div class="footer-bottom"><span>&copy; {{ date('Y') }} EcoCraft. Semua hak dilindungi.</span><span>Dirancang untuk konsumsi yang lebih sadar.</span></div>
        </div>
    </footer>
    @auth('customer')
        @php($bnCustomer = Auth::guard('customer')->user())
        @php($bnUnread = \App\Models\ProductInquiryMessage::where('sender_type', 'seller')->whereNull('read_at')->whereHas('inquiry', fn ($q) => $q->where('customer_id', $bnCustomer->id_customers))->count())
        <nav class="mobile-bottom-nav" aria-label="Navigasi utama">
            <a class="mbn-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                <i class="fa fa-home"></i><span>Beranda</span>
            </a>
            <a class="mbn-item {{ request()->routeIs('catalog.index') || request()->routeIs('product.show') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
                <i class="fa fa-th-large"></i><span>Katalog</span>
            </a>
            <a class="mbn-item {{ request()->routeIs('track.track') ? 'active' : '' }}" href="{{ route('track.track') }}">
                <i class="fa fa-receipt"></i><span>Pembelian</span>
            </a>
            <a class="mbn-item {{ request()->routeIs('customer.inquiries.*') ? 'active' : '' }}" href="{{ route('customer.inquiries.index') }}">
                <span class="mbn-icon-wrap"><i class="fa fa-comments"></i>@if($bnUnread > 0)<span class="mbn-badge">{{ $bnUnread > 9 ? '9+' : $bnUnread }}</span>@endif</span><span>Chat</span>
            </a>
            <a class="mbn-item {{ request()->routeIs('customer.profile') ? 'active' : '' }}" href="{{ route('customer.profile') }}">
                <i class="fa fa-user"></i><span>Akun</span>
            </a>
        </nav>
    @endauth
</div>
@stack('scripts')
<script>
document.querySelector('[data-menu-toggle]')?.addEventListener('click', function () {
    var nav = document.querySelector('[data-customer-nav]');
    var open = nav.classList.toggle('nav-open');
    this.setAttribute('aria-expanded', open ? 'true' : 'false');
    this.textContent = open ? '×' : '☰';
});
document.querySelectorAll('[data-customer-nav] .nav-links a').forEach(function (link) {
    link.addEventListener('click', function () {
        var nav = document.querySelector('[data-customer-nav]');
        var toggle = document.querySelector('[data-menu-toggle]');
        nav.classList.remove('nav-open');
        toggle?.setAttribute('aria-expanded', 'false');
        if (toggle) toggle.textContent = '☰';
    });
});
</script>
</body>
</html>
