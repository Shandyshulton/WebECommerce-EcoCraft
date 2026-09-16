<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar | EcoCraft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--canvas:#fbf9f5;--surface:#f4efeb;--ink:#1b2520;--muted:#717e77;--brand:#1e4b38;--accent:#c86d51;--line:#e5dfd5}
        *{box-sizing:border-box}html{-webkit-text-size-adjust:100%;text-size-adjust:100%}body{margin:0;min-height:100vh;min-height:100dvh;background:var(--canvas);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;display:grid;place-items:center;padding:24px}a{color:inherit;text-decoration:none}
        .back-home{display:inline-flex;align-items:center;gap:7px;margin-bottom:18px;padding:8px 14px;border:1px solid var(--line);border-radius:999px;background:#fff;color:var(--brand);font:700 12px 'Plus Jakarta Sans';cursor:pointer}
        .back-home:hover{background:#edf4ee;border-color:#cfe1d3}
        .auth-shell{width:min(1160px,100%);display:grid;grid-template-columns:1fr 1.05fr;overflow:hidden;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:0 20px 48px -8px rgba(27,37,32,.14)}.auth-panel{padding:clamp(28px,4vw,48px)}.brand{display:flex;align-items:center;gap:10px;color:var(--brand);font-weight:800;margin-bottom:28px}.brand img{width:34px;height:34px;object-fit:contain}.eyebrow{color:var(--accent);font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.auth-panel h1{font:600 clamp(32px,4vw,44px)/1.05 'EB Garamond',serif;margin:9px 0}.lead{color:var(--muted);font-size:13px;line-height:1.65;margin:0 0 22px}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:0 14px}.field{margin-bottom:13px}.field.full{grid-column:1/-1}.field label{display:block;font-size:11px;font-weight:700;margin-bottom:6px}.field input,.field select{width:100%;min-height:43px;padding:10px 11px;border:1px solid var(--line);border-radius:8px;background:var(--surface);font:13px 'Plus Jakarta Sans';color:var(--ink)}.field input:focus,.field select:focus{outline:0;border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}.password-wrap{position:relative}.password-wrap input{padding-right:84px}.toggle-password{position:absolute;right:6px;top:50%;border:0;background:none;color:var(--muted);font-size:11px;font-weight:700;cursor:pointer;padding:9px 8px;border-radius:6px;transform:translateY(-50%)}.toggle-password:hover{color:var(--brand);background:rgba(30,75,56,.06)}.btn{display:inline-flex;justify-content:center;align-items:center;width:100%;min-height:45px;padding:10px 18px;border:1px solid var(--brand);border-radius:8px;background:var(--brand);color:#fff;font:700 13px 'Plus Jakarta Sans';cursor:pointer;margin-top:5px}.btn:hover{background:#163729}.auth-footer{text-align:center;color:var(--muted);font-size:12px;margin-top:18px}.auth-footer a{color:var(--brand);font-weight:800}.error{color:#a53c27;font-size:11px;margin-top:4px}.alert{padding:11px 13px;border-radius:8px;background:#ffebe6;color:#8b351f;font-size:12px;margin-bottom:18px}.alert ul{margin:0;padding-left:18px}.auth-art{min-height:620px;display:flex;align-items:flex-end;padding:34px;background:linear-gradient(180deg,rgba(2,52,35,.1),rgba(2,52,35,.82)),url('{{ asset('assets/images/collection/banner welcome.png') }}') center/cover}.auth-art h2{color:#fff;font:600 42px/1 'EB Garamond',serif;margin:0 0 8px}.auth-art p{color:rgba(255,255,255,.82);font-size:13px;line-height:1.6;max-width:300px;margin:0}.helper{color:var(--muted);font-size:11px;margin-top:8px;line-height:1.5}@media(max-width:850px){body{padding:12px}.auth-shell{grid-template-columns:1fr}.auth-art{min-height:168px;order:-1;padding:22px}.auth-art h2{font-size:30px}.auth-art p{font-size:12px;max-width:100%}.auth-panel{padding:26px 20px}.lead{font-size:14px;margin-bottom:20px}.field{margin-bottom:15px}.field label{font-size:12px}.field input,.field select{font-size:16px;min-height:46px;padding:11px 12px}.password-wrap input{padding-right:100px}.toggle-password{top:1px;bottom:1px;right:4px;transform:none;display:inline-flex;align-items:center;font-size:12px;padding:0 10px}.back-home{min-height:40px;padding:10px 15px}.btn{min-height:48px;font-size:14px}.helper{font-size:12px}.auth-footer{font-size:13px}.auth-footer a{display:inline-block;padding:6px 3px}}@media(max-width:520px){.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.auth-art{min-height:150px}.auth-art h2{font-size:26px}.auth-panel{padding:24px 18px}.password-wrap input{padding-right:96px}}
    </style>
    <style>
        .tnc{display:flex;gap:10px;align-items:flex-start;margin-top:4px;color:var(--muted);font-size:12px;line-height:1.55;cursor:pointer}
        .tnc input{flex:0 0 auto;width:16px;height:16px;margin-top:1px;accent-color:var(--brand);cursor:pointer}
        .tnc a{color:var(--brand);font-weight:700;text-decoration:underline}
        .btn:disabled{opacity:.55;pointer-events:none}
        .policy-modal{position:fixed;inset:0;z-index:100;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(27,37,32,.48)}
        .policy-modal.open{display:flex}
        .policy-modal-card{position:relative;display:flex;flex-direction:column;width:min(720px,100%);max-height:86vh;padding:28px;background:#fff;border-radius:18px;box-shadow:0 24px 60px -12px rgba(27,37,32,.35)}
        .policy-modal-close{position:absolute;top:12px;right:14px;display:grid;place-items:center;width:34px;height:34px;border:0;border-radius:50%;background:var(--surface);color:var(--muted);font-size:20px;line-height:1;cursor:pointer}
        .policy-modal-close:hover{color:var(--brand);background:#edf4ee}
        .policy-modal-card h2{font:600 28px/1.15 'EB Garamond',Georgia,serif;margin:6px 0 10px}
        .policy-modal-updated{display:inline-block;align-self:flex-start;margin-bottom:12px;padding:5px 11px;border-radius:999px;background:var(--surface);color:var(--muted);font-size:11px;font-weight:700}
        .policy-modal-lead{color:var(--muted);font-size:13px;line-height:1.7;margin:0 0 16px}
        .policy-modal-body{overflow-y:auto;padding-right:6px}
        .policy-modal-section+.policy-modal-section{margin-top:20px}
        .policy-modal-section h3{font:700 15px/1.3 'Plus Jakarta Sans';margin:0 0 7px}
        .policy-modal-section p{margin:0 0 10px;color:#26342b;font-size:13px;line-height:1.7}
        .policy-modal-section ul{margin:8px 0 0;padding-left:18px;display:grid;gap:6px}
        .policy-modal-section li{color:#26342b;font-size:13px;line-height:1.65}
        .policy-modal-foot{margin-top:18px;padding-top:14px;border-top:1px solid var(--line)}
        .policy-modal-foot a{color:var(--brand);font-size:12.5px;font-weight:700;text-decoration:underline}
        @media(max-width:520px){.policy-modal{padding:12px}.policy-modal-card{padding:22px 18px;max-height:88vh}}
    </style>
</head>
<body>
<main class="auth-shell">
    <section class="auth-panel">
        <a class="back-home" href="{{ route('customer.dashboard') }}">&larr; Kembali ke beranda</a>
        <div class="eyebrow">Komunitas belanja sirkular</div>
        <h1>Buat akun EcoCraft</h1>
        <p class="lead">Simpan alamat, pantau pesanan, dan dapatkan pengalaman kurasi yang lebih personal.</p>
        @if($errors->any())<div class="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <div class="form-grid">
                <div class="field"><label for="name_customers">Nama lengkap</label><input id="name_customers" name="name_customers" value="{{ old('name_customers') }}" autocomplete="name" placeholder="Nama kamu" required>@error('name_customers')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="email">Email aktif</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" required>@error('email')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="phone_number">Nomor WhatsApp</label><input id="phone_number" name="phone_number" value="{{ old('phone_number') }}" autocomplete="tel" placeholder="08xxxxxxxxxx" required>@error('phone_number')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="dob">Tanggal lahir</label><input id="dob" type="date" name="dob" value="{{ old('dob') }}" required>@error('dob')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="gender">Jenis kelamin</label><select id="gender" name="gender" required><option value="">Pilih jenis kelamin</option><option value="male" @selected(old('gender') === 'male')>Laki-laki</option><option value="female" @selected(old('gender') === 'female')>Perempuan</option></select>@error('gender')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="province">Provinsi</label><input id="province" name="province" value="{{ old('province') }}" placeholder="Contoh: Jawa Barat" required>@error('province')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="city">Kota</label><input id="city" name="city" value="{{ old('city') }}" placeholder="Contoh: Bandung" required>@error('city')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field full"><label for="address">Alamat lengkap</label><input id="address" name="address" value="{{ old('address') }}" autocomplete="street-address" placeholder="Jalan, nomor rumah, kecamatan" required>@error('address')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="password">Kata sandi</label><div class="password-wrap"><input id="password" type="password" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter" required><button class="toggle-password" type="button" onclick="togglePassword('password',this)">Lihat</button></div>@error('password')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="password_confirmation">Konfirmasi kata sandi</label><div class="password-wrap"><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi kata sandi" required><button class="toggle-password" type="button" onclick="togglePassword('password_confirmation',this)">Lihat</button></div></div>
            </div>
            <label class="tnc" for="acceptTerms">
                <input id="acceptTerms" type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }}>
                <span>Saya menyetujui <a href="{{ route('policy.terms') }}" data-policy-open="terms">Ketentuan Layanan</a> dan <a href="{{ route('policy.privacy') }}" data-policy-open="privacy">Kebijakan Privasi</a> EcoCraft.</span>
            </label>
            @error('terms')<div class="error">{{ $message }}</div>@enderror
            <button class="btn" type="submit" id="registerSubmit" {{ old('terms') ? '' : 'disabled' }}>Buat akun EcoCraft</button>
        </form>
        <div class="auth-footer">Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></div>
    </section>
    <aside class="auth-art"><div><h2>Karya baik dimulai dari pilihan kecil.</h2><p>Jelajahi produk daur ulang, kenali pengrajinnya, dan ikut menggerakkan ekonomi yang lebih berkelanjutan.</p></div></aside>
</main>
@foreach($policies ?? [] as $key => $doc)
<div class="policy-modal" data-policy-modal="{{ $key }}" aria-hidden="true">
    <div class="policy-modal-card" role="dialog" aria-modal="true" aria-labelledby="policy-title-{{ $key }}">
        <button type="button" class="policy-modal-close" data-policy-close aria-label="Tutup">&times;</button>
        <div class="eyebrow">{{ $doc['eyebrow'] ?? 'Kebijakan' }}</div>
        <h2 id="policy-title-{{ $key }}">{{ $doc['title'] }}</h2>
        <span class="policy-modal-updated">Terakhir diperbarui: {{ $doc['updated'] }}</span>
        <p class="policy-modal-lead">{{ $doc['lead'] }}</p>
        <div class="policy-modal-body">
            @foreach($doc['sections'] as $section)
                <section class="policy-modal-section">
                    <h3>{{ $section['heading'] }}</h3>
                    @foreach($section['paragraphs'] ?? [] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                    @if(! empty($section['bullets']))
                        <ul>
                            @foreach($section['bullets'] as $bullet)
                                <li>{{ $bullet }}</li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            @endforeach
        </div>
        <div class="policy-modal-foot">
            @if($key === 'privacy')
                <a href="{{ route('policy.terms') }}" data-policy-open="terms">Baca juga: Ketentuan Layanan &rarr;</a>
            @else
                <a href="{{ route('policy.privacy') }}" data-policy-open="privacy">Baca juga: Kebijakan Privasi &rarr;</a>
            @endif
        </div>
    </div>
</div>
@endforeach
<script>function togglePassword(id,button){const input=document.getElementById(id);const visible=input.type==='text';input.type=visible?'password':'text';button.textContent=visible?'Lihat':'Sembunyikan';}</script>
<script>
(function () {
    var checkbox = document.getElementById('acceptTerms');
    var submit = document.getElementById('registerSubmit');
    if (!checkbox || !submit) return;

    function syncSubmit() {
        submit.disabled = !checkbox.checked;
    }

    checkbox.addEventListener('change', syncSubmit);
    syncSubmit();
})();
</script>
<script>
(function () {
    var modals = document.querySelectorAll('[data-policy-modal]');
    if (!modals.length) return;

    function closeModals() {
        modals.forEach(function (modal) {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        });
        document.body.style.overflow = '';
    }

    function openModal(key) {
        closeModals();
        var modal = document.querySelector('[data-policy-modal="' + key + '"]');
        if (!modal) return;
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        var close = modal.querySelector('[data-policy-close]');
        if (close) close.focus();
    }

    document.querySelectorAll('[data-policy-open]').forEach(function (trigger) {
        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            openModal(trigger.getAttribute('data-policy-open'));
        });
    });

    modals.forEach(function (modal) {
        modal.querySelectorAll('[data-policy-close]').forEach(function (button) {
            button.addEventListener('click', closeModals);
        });
        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeModals();
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeModals();
    });
})();
</script>
</body>
</html>
