<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kurir EcoCraft')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root{--ink:#1b2520;--muted:#717e77;--brand:#1e4b38;--accent:#c86d51;--line:#e5dfd5;--canvas:#fbf9f5}
        *{box-sizing:border-box}
        body{margin:0;background:var(--canvas);color:var(--ink);font:14px/1.55 'Plus Jakarta Sans',system-ui,sans-serif;padding-bottom:32px}
        .c-nav{background:var(--brand);color:#fff;padding:13px 16px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:10}
        .c-nav .brand{font-weight:800;letter-spacing:.02em;line-height:1.2}
        .c-nav .brand small{display:block;font-weight:500;font-size:11px;opacity:.78}
        .c-logout{border:1px solid rgba(255,255,255,.4);background:transparent;color:#fff;border-radius:7px;font-size:11px;font-weight:700;padding:6px 12px;cursor:pointer}
        .c-wrap{max-width:640px;margin:0 auto;padding:16px}
        .c-card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:16px;margin-bottom:14px}
        .c-card h2{margin:0 0 4px;font-size:15px;font-weight:800}
        .c-card .hint{margin:0 0 14px;font-size:11px;color:var(--muted)}
        .c-task{background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px;margin-bottom:10px;display:block;text-decoration:none;color:inherit}
        .c-task:hover{border-color:var(--brand)}
        .c-task-top{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:8px}
        .c-task-no{font-weight:800;font-size:13px}
        .c-task-name{font-weight:700;font-size:13px;margin-bottom:2px}
        .c-task-meta{font-size:11px;color:var(--muted)}
        .c-badge{display:inline-block;font-size:10px;font-weight:800;padding:3px 9px;border-radius:99px;text-transform:uppercase;letter-spacing:.05em}
        .c-badge.warn{background:#fdf3e3;color:#8a5b13}
        .c-badge.info{background:#e7f0f7;color:#1f4f75}
        .c-badge.ok{background:#e6f2ea;color:var(--brand)}
        .c-badge.off{background:#f2f2f2;color:#666}
        .c-empty{font-size:12px;color:var(--muted);padding:4px 0}
        .c-meta{list-style:none;margin:0 0 14px;padding:0;font-size:12px}
        .c-meta li{display:flex;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid var(--line)}
        .c-meta li:last-child{border-bottom:0}
        .c-meta li strong{text-align:right;font-weight:700}
        .c-items{list-style:none;margin:0;padding:0;font-size:12px}
        .c-items li{display:flex;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid var(--line)}
        .c-items li:last-child{border-bottom:0}
        .c-label{display:block;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:6px}
        .c-input,.c-select{width:100%;min-height:46px;border:1px solid var(--line);border-radius:9px;background:#f8faf8;padding:10px 12px;font-size:13px;font-family:inherit}
        .c-input:focus,.c-select:focus{outline:0;border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}
        .c-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:0;border-radius:9px;background:var(--brand);color:#fff;font-size:13px;font-weight:800;padding:12px 18px;cursor:pointer;text-decoration:none}
        .c-btn.wide{width:100%}
        .c-btn.ghost{background:#f1f4f1;border:1px solid var(--line);color:var(--ink)}
        .c-field{margin-bottom:14px}
        .c-timeline{list-style:none;margin:0;padding:0;position:relative}
        .c-timeline:before{content:'';position:absolute;left:5px;top:6px;bottom:6px;width:2px;background:var(--line)}
        .c-timeline li{position:relative;padding:0 0 14px 22px}
        .c-timeline li:last-child{padding-bottom:0}
        .c-timeline li:before{content:'';position:absolute;left:0;top:4px;width:12px;height:12px;border-radius:50%;background:#fff;border:2px solid var(--brand)}
        .c-timeline li:first-child:before{background:var(--brand)}
        .c-tl-status{display:block;font-size:12px;font-weight:800}
        .c-tl-desc{display:block;font-size:12px;color:var(--muted)}
        .c-tl-meta{display:block;font-size:10px;color:var(--muted);margin-top:3px}
        .c-proof{display:flex;gap:12px;align-items:center;padding:12px;border:1px solid var(--line);border-radius:10px;background:#f4f8f5;margin-bottom:14px}
        .c-proof img{width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid var(--line)}
        .c-section-title{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin:22px 0 10px}
    </style>
</head>
<body>
<header class="c-nav">
    <div class="brand">EcoCraft<small>Kurir Lokal</small></div>
    <form action="{{ route('courier.logout') }}" method="POST">@csrf<button type="submit" class="c-logout">Keluar</button></form>
</header>
<main class="c-wrap">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    @yield('content')
</main>
</body>
</html>
