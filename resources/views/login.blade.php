<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | EcoCraft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--canvas:#fbf9f5;--surface:#f4efeb;--ink:#1b2520;--muted:#717e77;--brand:#1e4b38;--accent:#c86d51;--line:#e5dfd5}
        *{box-sizing:border-box}body{margin:0;min-height:100vh;background:var(--canvas);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;display:grid;place-items:center;padding:24px}a{color:inherit;text-decoration:none}
        .back-home{display:inline-flex;align-items:center;gap:7px;margin-bottom:18px;padding:8px 14px;border:1px solid var(--line);border-radius:999px;background:#fff;color:var(--brand);font:700 12px 'Plus Jakarta Sans';cursor:pointer}
        .back-home:hover{background:#edf4ee;border-color:#cfe1d3}
        .auth-shell{width:min(1060px,100%);display:grid;grid-template-columns:1fr 1fr;overflow:hidden;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:0 20px 48px -8px rgba(27,37,32,.14)}
        .auth-panel{padding:clamp(28px,5vw,58px)}.brand{display:flex;align-items:center;gap:10px;color:var(--brand);font-weight:800;margin-bottom:44px}.brand img{width:34px;height:34px;object-fit:contain}.eyebrow{color:var(--accent);font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.auth-panel h1{font:600 clamp(34px,4vw,48px)/1.05 'EB Garamond',serif;margin:10px 0}.lead{color:var(--muted);font-size:14px;line-height:1.7;margin:0 0 28px}.field{margin-bottom:17px}.field label{display:block;font-size:12px;font-weight:700;margin-bottom:7px}.field input{width:100%;min-height:46px;padding:11px 13px;border:1px solid var(--line);border-radius:8px;background:var(--surface);font:inherit;color:var(--ink)}.field input:focus{outline:0;border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}.password-wrap{position:relative}.password-wrap input{padding-right:48px}.toggle-password{position:absolute;right:12px;top:50%;border:0;background:none;color:var(--muted);cursor:pointer;transform:translateY(-50%)}.actions{display:flex;justify-content:space-between;align-items:center;gap:12px;margin:8px 0 22px;font-size:12px}.actions a{color:var(--brand);font-weight:700}.btn{display:inline-flex;justify-content:center;align-items:center;width:100%;min-height:46px;padding:11px 18px;border:1px solid var(--brand);border-radius:8px;background:var(--brand);color:#fff;font:700 13px 'Plus Jakarta Sans';cursor:pointer}.btn:hover{background:#163729}.auth-footer{text-align:center;color:var(--muted);font-size:12px;margin-top:22px}.auth-footer a{color:var(--brand);font-weight:800}.alert{padding:11px 13px;border-radius:8px;background:#ffebe6;color:#8b351f;font-size:12px;margin-bottom:18px}.alert ul{margin:0;padding-left:18px}.auth-art{min-height:560px;display:flex;align-items:flex-end;padding:34px;background:linear-gradient(180deg,rgba(2,52,35,.1),rgba(2,52,35,.82)),url('{{ asset('assets/images/collection/banner welcome.png') }}') center/cover}.auth-art h2{color:#fff;font:600 42px/1 'EB Garamond',serif;margin:0 0 8px}.auth-art p{color:rgba(255,255,255,.82);font-size:13px;line-height:1.6;max-width:280px;margin:0}@media(max-width:760px){body{padding:12px}.auth-shell{grid-template-columns:1fr}.auth-art{min-height:190px;order:-1;padding:24px}.auth-art h2{font-size:32px}.auth-panel{padding:28px 22px}.brand{margin-bottom:30px}}
    </style>
</head>
<body>
<main class="auth-shell">
    <section class="auth-panel">
        <a class="back-home" href="{{ route('customer.dashboard') }}">&larr; Kembali ke beranda</a>
        <div class="eyebrow">Area pelanggan terkurasi</div>
        <h1>Selamat datang kembali</h1>
        <p class="lead">Masuk untuk melanjutkan belanja karya sirkular dan memantau pesananmu.</p>
        @if(session('success'))<div class="alert" style="background:#e5f3e9;color:#1e4b38">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="field"><label for="email">Email atau nomor WhatsApp</label><input id="email" type="text" name="email" value="{{ old('email') }}" autocomplete="username" placeholder="nama@email.com atau 08xxxxxxxxxx" required></div>
            <div class="field"><label for="password">Kata sandi</label><div class="password-wrap"><input id="password" type="password" name="password" autocomplete="current-password" placeholder="Masukkan kata sandi" required><button class="toggle-password" type="button" onclick="togglePassword('password',this)" aria-label="Tampilkan kata sandi">Lihat</button></div></div>
            <div class="actions"><a href="{{ route('password.request') }}">Lupa kata sandi?</a><span>Belum punya akun?</span></div>
            <button class="btn" type="submit">Masuk ke akun</button>
        </form>
        <div class="auth-footer">Belum menjadi bagian EcoCraft? <a href="{{ route('register') }}">Daftar akun baru</a></div>
    </section>
    <aside class="auth-art"><div><h2>Belanja dengan makna.</h2><p>Temukan karya pengrajin lokal dari material yang dirawat kembali menjadi sesuatu yang indah.</p></div></aside>
</main>
<script>function togglePassword(id,button){const input=document.getElementById(id);const visible=input.type==='text';input.type=visible?'password':'text';button.textContent=visible?'Lihat':'Sembunyikan';}</script>
</body>
</html>
