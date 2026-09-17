<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Kurir | EcoCraft</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root{--ink:#1b2520;--muted:#717e77;--brand:#1e4b38;--line:#e5dfd5;--canvas:#fbf9f5}
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:var(--canvas);color:var(--ink);font:14px/1.5 'Plus Jakarta Sans',system-ui,sans-serif;padding:20px}
        .login-card{width:100%;max-width:380px;background:#fff;border:1px solid var(--line);border-radius:16px;padding:28px}
        .login-card h1{margin:0 0 6px;font-size:20px;font-weight:800}
        .login-card p.sub{margin:0 0 22px;font-size:12px;color:var(--muted)}
        .badge-role{display:inline-block;background:#e6f2ea;color:var(--brand);font-size:10px;font-weight:800;padding:4px 10px;border-radius:99px;text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px}
        .form-label{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:6px}
        .form-control{min-height:46px;border:1px solid var(--line);border-radius:9px;background:#f8faf8;font-size:13px}
        .form-control:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}
        .btn-brand{width:100%;border:0;border-radius:9px;background:var(--brand);color:#fff;font-size:13px;font-weight:800;padding:12px;cursor:pointer}
        .back-link{display:block;text-align:center;margin-top:16px;font-size:12px;color:var(--muted);text-decoration:none}
    </style>
</head>
<body>
<div class="login-card">
    <div class="badge-role">Area Kurir</div>
    <h1>Masuk sebagai Kurir</h1>
    <p class="sub">Untuk petugas Kurir Lokal EcoCraft.</p>

    @if(session('status'))<div class="alert alert-success py-2" style="font-size:12px">{{ session('status') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger py-2" style="font-size:12px">{{ session('error') }}</div>@endif

    <form action="{{ route('courier.login.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="form-label" for="password">Password</label>
            <input id="password" type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-brand">Masuk</button>
    </form>

    <a class="back-link" href="{{ route('customer.dashboard') }}">&larr; Kembali ke EcoCraft</a>
</div>
</body>
</html>
