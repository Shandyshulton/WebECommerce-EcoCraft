@extends('layouts.customer')
@section('title', 'Ajukan Klaim Garansi | EcoCraft')

@push('styles')
<style>
    .claim-form-page { padding:34px 0 64px; }
    .claim-back { display:inline-flex; align-items:center; gap:8px; margin-bottom:16px; color:var(--muted); font-size:12px; font-weight:700; }
    .claim-back:hover { color:var(--brand); }
    .claim-form-page h1 { font:600 clamp(30px,4vw,42px)/1.05 'EB Garamond',serif; margin:6px 0 8px; }

    .claim-block { margin:0 0 20px; padding:20px; border:1px solid var(--line); border-radius:14px; background:#fff; }
    .claim-block legend { padding:0 8px; color:var(--brand); font-size:12px; font-weight:800; letter-spacing:.02em; }
    .claim-items { display:grid; gap:10px; }
    .claim-pick { display:flex; align-items:center; gap:12px; padding:12px; border:1px solid var(--line); border-radius:12px; background:var(--surface); cursor:pointer; }
    .claim-pick:has(input:checked) { border-color:var(--brand); background:#edf4ee; }
    .claim-pick input { flex:0 0 auto; width:17px; height:17px; accent-color:var(--brand); cursor:pointer; }
    .claim-pick img { width:52px; height:52px; flex:0 0 52px; border-radius:10px; object-fit:cover; background:#e9e4dc; }
    .claim-pick strong { display:block; font-size:13.5px; }
    .claim-pick small { display:block; margin-top:2px; color:var(--muted); font-size:11px; }

    .claim-input { width:100%; min-height:44px; padding:10px 12px; border:1px solid var(--line); border-radius:10px; background:var(--surface); font:13px 'Plus Jakarta Sans'; color:var(--ink); }
    .claim-input:focus { outline:0; border-color:var(--brand); box-shadow:0 0 0 3px rgba(30,75,56,.12); }
    textarea.claim-input { resize:vertical; min-height:120px; }
    .claim-hint { display:block; margin-top:8px; color:var(--muted); font-size:11px; }

    .claim-actions { display:flex; justify-content:flex-end; gap:10px; }
    @media (max-width:640px) { .claim-actions { flex-direction:column-reverse; } .claim-actions .btn { width:100%; } }
</style>
@endpush

@section('content')
<section class="claim-form-page">
    <div class="page-wrap" style="max-width:760px">
        <a class="claim-back" href="{{ route('customer.claims.index') }}">&larr; Kembali ke daftar klaim</a>
        <div class="eyebrow">Form klaim</div>
        <h1>Ajukan Klaim Garansi</h1>
        <p class="text-muted">Pilih produk, jelaskan masalahnya, dan lampirkan foto bila ada.</p>

        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        @if($items->isEmpty())
            <div class="history-empty">
                <i class="fa fa-circle-check" aria-hidden="true"></i>
                <h2>Tidak ada produk yang bisa diklaim</h2>
                <p class="text-muted">Klaim hanya tersedia untuk item dari pesanan yang sudah <strong>dikirim atau diterima</strong> dan belum pernah diklaim.</p>
                <a class="btn btn-outline-brand" href="{{ route('track.track') }}">Lihat riwayat pembelian</a>
            </div>
        @else
            <form method="POST" action="{{ route('customer.claims.store') }}" enctype="multipart/form-data">
                @csrf

                <fieldset class="claim-block">
                    <legend>1. Pilih produk</legend>
                    <div class="claim-items">
                        @foreach($items as $row)
                            <label class="claim-pick">
                                <input type="radio" name="order_item_id" value="{{ $row['item']->id }}" required @checked(old('order_item_id') == $row['item']->id)>
                                <img src="{{ optional($row['item']->product)->image_url ? asset('storage/'.$row['item']->product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $row['item']->product_name }}">
                                <span>
                                    <strong>{{ $row['item']->product_name }}</strong>
                                    <small>#{{ $row['order']->order_number }} · {{ $row['item']->quantity }} item · {{ $row['order']->created_at->translatedFormat('d M Y') }}</small>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="claim-block">
                    <legend>2. Kategori masalah</legend>
                    <select name="category" class="claim-input">
                        @foreach(\App\Models\WarrantyClaim::CATEGORIES as $value => $label)
                            <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </fieldset>

                <fieldset class="claim-block">
                    <legend>3. Detail masalah</legend>
                    <textarea name="description" class="claim-input" placeholder="Jelaskan kondisi produk dan masalah yang kamu temui…" required>{{ old('description') }}</textarea>
                </fieldset>

                <fieldset class="claim-block">
                    <legend>4. Foto bukti (opsional)</legend>
                    <input type="file" name="photo" class="claim-input" accept="image/*">
                    <small class="claim-hint">Format JPG, PNG, atau WebP. Maksimal 2 MB.</small>
                </fieldset>

                <div class="claim-actions">
                    <a class="btn btn-outline-brand" href="{{ route('customer.claims.index') }}">Batal</a>
                    <button class="btn btn-brand" type="submit"><i class="fa fa-paper-plane"></i> Kirim klaim</button>
                </div>
            </form>
        @endif
    </div>
</section>
@endsection
