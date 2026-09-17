<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin EcoCraft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
<div class="admin-shell">
    <header class="admin-nav-bar">
        <div class="admin-nav-inner">
            <a class="brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/logo/ecocraft-logo.png') }}" alt="EcoCraft">
                <span class="brand-role">Admin</span>
            </a>
            <nav class="admin-nav">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-grid-2"></i>Ringkasan</a>
                <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.verify') }}"><i class="fas fa-box-open"></i>Verifikasi Produk</a>
                <a class="{{ request()->routeIs('admin.sellers.*') || request()->routeIs('admin.show') ? 'active' : '' }}" href="{{ route('admin.sellers.verify') }}"><i class="fas fa-store"></i>Verifikasi Seller</a>
                <a class="{{ request()->routeIs('admin.customers') ? 'active' : '' }}" href="{{ route('admin.customers') }}"><i class="fas fa-user-group"></i>Customer</a>
                @if(optional(Auth::guard('admin')->user())->isSuperAdmin())
                    <a class="{{ request()->routeIs('admin.staff') ? 'active' : '' }}" href="{{ route('admin.staff') }}"><i class="fas fa-users"></i>Staff</a>
                    <a class="{{ request()->routeIs('admin.impact.*') ? 'active' : '' }}" href="{{ route('admin.impact.index') }}"><i class="fas fa-leaf"></i>Faktor Dampak</a>
                    <a class="{{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}" href="{{ route('admin.vouchers.index') }}"><i class="fas fa-ticket"></i>Voucher</a>
                    <a class="{{ request()->routeIs('admin.couriers*') ? 'active' : '' }}" href="{{ route('admin.couriers') }}"><i class="fas fa-people-carry-box"></i>Kurir</a>
                @endif
            </nav>
            <div class="nav-right">
                <span class="today" id="current-date"></span>
                <div class="profile-menu" tabindex="0">
                    <button class="profile-trigger" type="button">
                        <img class="profile-img" src="{{ Auth::guard('admin')->user()->profile_image ? asset('storage/' . Auth::guard('admin')->user()->profile_image) : asset('images/default-profile.png') }}" alt="Profil admin">
                        <span class="profile-name">{{ Auth::guard('admin')->user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size:10px;color:var(--muted)"></i>
                    </button>
                    <div class="profile-dropdown">
                        <div style="padding:8px 10px;font-weight:800;font-size:11px">{{ Auth::guard('admin')->user()->email }}</div>
                        <div style="padding:0 10px 8px">
                            @if(optional(Auth::guard('admin')->user())->isSuperAdmin())
                                <span class="status approved"><i class="fas fa-crown"></i> Super Admin</span>
                            @else
                                <span class="status" style="background:var(--soft);color:var(--brand)"><i class="fas fa-user-shield"></i> Admin</span>
                            @endif
                        </div>
                        <a href="{{ route('profile.edit') }}">Pengaturan profil</a>
                        <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit">Keluar dari akun</button></form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="content">
        @if(View::hasSection('breadcrumb'))
            <nav aria-label="breadcrumb" class="crumbs"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-house"></i></a>@yield('breadcrumb')</nav>
        @endif
        @yield('content')
    </main>
</div>

<div class="img-lightbox" id="imgLightbox" aria-hidden="true">
    <div class="img-lightbox-toolbar">
        <button type="button" class="lb-btn" data-lb-zoomout aria-label="Perkecil"><i class="fas fa-magnifying-glass-minus"></i></button>
        <span class="lb-zoom" id="lbZoom">100%</span>
        <button type="button" class="lb-btn" data-lb-zoomin aria-label="Perbesar"><i class="fas fa-magnifying-glass-plus"></i></button>
        <button type="button" class="lb-btn" data-lb-reset aria-label="Reset"><i class="fas fa-arrows-rotate"></i></button>
        <button type="button" class="lb-btn lb-close" data-lb-close aria-label="Tutup"><i class="fas fa-xmark"></i></button>
    </div>
    <div class="img-lightbox-stage" id="lbStage">
        <img id="lbImage" src="" alt="Pratinjau gambar" draggable="false">
    </div>
    <div class="img-lightbox-hint">Scroll untuk zoom · seret untuk menggeser</div>
</div>

<script>
    document.getElementById('current-date').textContent=new Date().toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});

    // ===== Image lightbox dengan zoom in/out + geser =====
    (function(){
        var lb=document.getElementById('imgLightbox'), img=document.getElementById('lbImage'),
            stage=document.getElementById('lbStage'), zoomLabel=document.getElementById('lbZoom');
        if(!lb) return;
        var scale=1, tx=0, ty=0, dragging=false, sx=0, sy=0;
        var MIN=0.5, MAX=5;

        function apply(){ img.style.transform='translate('+tx+'px,'+ty+'px) scale('+scale+')'; zoomLabel.textContent=Math.round(scale*100)+'%'; }
        function reset(){ scale=1; tx=0; ty=0; apply(); }
        function open(src){ img.src=src; reset(); lb.classList.add('open'); lb.setAttribute('aria-hidden','false'); }
        function close(){ lb.classList.remove('open'); lb.setAttribute('aria-hidden','true'); img.src=''; }
        function setScale(s){ scale=Math.min(MAX, Math.max(MIN, s)); if(scale<=1){ tx=0; ty=0; } apply(); }

        // Buka lightbox saat gambar zoomable diklik
        document.addEventListener('click', function(e){
            var t=e.target.closest('[data-zoomable]');
            if(t){ e.preventDefault(); open(t.getAttribute('data-full') || t.getAttribute('src')); }
        });

        lb.querySelector('[data-lb-close]').addEventListener('click', close);
        lb.querySelector('[data-lb-zoomin]').addEventListener('click', function(){ setScale(scale+0.25); });
        lb.querySelector('[data-lb-zoomout]').addEventListener('click', function(){ setScale(scale-0.25); });
        lb.querySelector('[data-lb-reset]').addEventListener('click', reset);
        lb.addEventListener('click', function(e){ if(e.target===lb || e.target===stage) close(); });
        document.addEventListener('keydown', function(e){ if(e.key==='Escape' && lb.classList.contains('open')) close(); });

        // Zoom via scroll
        stage.addEventListener('wheel', function(e){ e.preventDefault(); setScale(scale + (e.deltaY<0?0.15:-0.15)); }, {passive:false});

        // Geser (drag) saat ter-zoom
        stage.addEventListener('mousedown', function(e){ if(scale<=1) return; dragging=true; sx=e.clientX-tx; sy=e.clientY-ty; stage.classList.add('grabbing'); });
        window.addEventListener('mousemove', function(e){ if(!dragging) return; tx=e.clientX-sx; ty=e.clientY-sy; apply(); });
        window.addEventListener('mouseup', function(){ dragging=false; stage.classList.remove('grabbing'); });
    })();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
