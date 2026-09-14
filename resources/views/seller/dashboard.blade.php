<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seller Center | EcoCraft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/seller.css') }}">
</head>
@php($seller = Auth::guard('seller')->user())
<body>
<div class="seller-shell">
    <header class="seller-nav-bar" id="sellerNav">
        <div class="seller-nav-inner">
            <a class="brand" href="{{ route('seller.dashboard') }}">
                <img src="{{ asset('assets/logo/ecocraft-logo.png') }}" alt="EcoCraft">
                <span class="brand-role">Seller Center</span>
            </a>
            <nav class="seller-nav">
                <a class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}"><i class="fas fa-grid-2"></i>Ringkasan</a>
                <a class="{{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}"><i class="fas fa-box-open"></i>Katalog Produk</a>
                <a class="{{ request()->routeIs('order.*') ? 'active' : '' }}" href="{{ route('order.index') }}"><i class="fas fa-receipt"></i>Pesanan</a>
                <a class="{{ request()->routeIs('seller.inquiries.*') ? 'active' : '' }}" href="{{ route('seller.inquiries.index') }}"><i class="fas fa-comments"></i>Pertanyaan</a>
            </nav>
            <div class="nav-right">
                <span class="today" id="current-date"></span>
                <div class="profile-menu" tabindex="0">
                    <button class="profile-trigger" type="button">
                        <img class="profile-img" src="{{ $seller->profile_image ? asset('storage/' . $seller->profile_image) : asset('images/default-profile.png') }}" alt="Profil seller">
                        <span class="profile-name">{{ $seller->name_sellers }}</span>
                        <i class="fas fa-chevron-down" style="font-size:10px;color:var(--muted)"></i>
                    </button>
                    <div class="profile-dropdown">
                        <strong style="display:block;padding:8px 10px;font-size:11px">{{ $seller->store_name }}</strong>
                        <a href="{{ route('seller.profile') }}">Pengaturan profil</a>
                        <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit">Keluar dari akun</button></form>
                    </div>
                </div>
            </div>
            <button class="mobile-toggle" id="navToggle" type="button" aria-label="Buka menu"><i class="fas fa-bars"></i></button>
        </div>
    </header>
    <main class="content">@if(View::hasSection('breadcrumb'))<nav class="crumbs" aria-label="breadcrumb"><a href="{{ route('seller.dashboard') }}"><i class="fas fa-house"></i></a>@yield('breadcrumb')</nav>@endif @yield('content')</main>
</div>
<script>
    document.getElementById('current-date').textContent=new Date().toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
    const navBar=document.getElementById('sellerNav'),navToggle=document.getElementById('navToggle');
    navToggle.addEventListener('click',()=>navBar.classList.toggle('nav-open'));
    document.querySelectorAll('.seller-nav a').forEach(a=>a.addEventListener('click',()=>navBar.classList.remove('nav-open')));
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
