@extends('seller.dashboard')

@section('breadcrumb')<a href="{{ route('seller.claims.index') }}">Klaim Garansi</a><span class="current">Detail</span>@endsection

@section('content')
<style>
    .claim-seller-grid { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:16px; align-items:start; }
    .claim-seller-panel { padding:20px; border:1px solid var(--line); border-radius:14px; background:#fff; }
    .claim-seller-panel h2 { margin:0 0 14px; font:600 20px/1.2 'EB Garamond',serif; }
    .claim-seller-facts { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .claim-seller-fact { padding:12px; border-radius:10px; background:var(--soft); }
    .claim-seller-fact small { display:block; color:var(--muted); font-size:10px; font-weight:800; letter-spacing:.06em; text-transform:uppercase; }
    .claim-seller-fact strong { display:block; margin-top:4px; font-size:13px; }
    .claim-seller-desc { margin:16px 0 0; color:var(--ink); font-size:13.5px; line-height:1.8; }
    .claim-seller-photo { display:block; margin-top:14px; width:100%; max-width:320px; border:1px solid var(--line); border-radius:12px; }
    .claim-seller-form { display:grid; gap:12px; }
    .claim-seller-form textarea { width:100%; min-height:120px; padding:11px 13px; border:1px solid var(--line); border-radius:10px; font:13px 'Plus Jakarta Sans'; resize:vertical; }
    .claim-seller-form textarea:focus { outline:0; border-color:var(--brand); box-shadow:0 0 0 3px rgba(30,75,56,.12); }
    .claim-seller-form select { width:100%; min-height:42px; padding:8px 12px; border:1px solid var(--line); border-radius:10px; background:#fff; font:13px 'Plus Jakarta Sans'; }
    .claim-seller-form .btn-brand { justify-content:center; }
    .claim-seller-note { margin:0; color:var(--muted); font-size:12.5px; line-height:1.75; }
    @media (max-width:900px) { .claim-seller-grid { grid-template-columns:1fr; } .claim-seller-facts { grid-template-columns:1fr; } }
</style>

<div class="page-heading">
    <div>
        <div class="eyebrow">Klaim #{{ $claim->id_claims }} · #{{ optional($claim->order)->order_number }}</div>
        <h1 style="font-size:30px;margin:2px 0 0">{{ optional($claim->product)->name ?? optional($claim->item)->product_name ?? 'Produk EcoCraft' }}</h1>
        <p class="subtle mb-0">Diajukan {{ $claim->created_at->translatedFormat('d F Y, H:i') }}</p>
    </div>
    <a class="btn-brand" href="{{ route('seller.claims.index') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="claim-seller-grid">
    <div class="claim-seller-panel">
        <h2>Detail klaim</h2>
        <div class="claim-seller-facts">
            <div class="claim-seller-fact"><small>Customer</small><strong>{{ optional($claim->customer)->name_customers ?? 'Customer' }}</strong></div>
            <div class="claim-seller-fact"><small>Email</small><strong>{{ optional($claim->customer)->email ?? '—' }}</strong></div>
            <div class="claim-seller-fact"><small>Kategori</small><strong>{{ $claim->categoryLabel() }}</strong></div>
            <div class="claim-seller-fact"><small>Jumlah</small><strong>{{ optional($claim->item)->quantity ?? 1 }} item</strong></div>
        </div>
        <p class="claim-seller-desc">{{ $claim->description }}</p>
        @if($claim->photo)
            <a href="{{ asset('storage/'.$claim->photo) }}" target="_blank" rel="noopener">
                <img class="claim-seller-photo" src="{{ asset('storage/'.$claim->photo) }}" alt="Foto bukti klaim">
            </a>
        @endif
    </div>

    <div class="claim-seller-panel">
        <h2>Tanggapan</h2>
        <div style="margin-bottom:14px">
            <span class="badge-status {{ $claim->statusTone() }}">{{ $claim->statusLabel() }}</span>
            @if($claim->responded_at)
                <small class="subtle" style="display:block;margin-top:8px">Terakhir ditanggapi {{ $claim->responded_at->translatedFormat('d M Y, H:i') }}</small>
            @endif
        </div>

        <form class="claim-seller-form" method="POST" action="{{ route('seller.claims.respond', $claim->id_claims) }}">
            @csrf
            <select name="status" required>
                @foreach(\App\Models\WarrantyClaim::STATUSES as $value => $label)
                    @continue($value === 'Submitted')
                    <option value="{{ $value }}" @selected(old('status', $claim->status) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<div class="text-danger small">{{ $message }}</div>@enderror

            <textarea name="resolution" placeholder="Tulis catatan penanganan atau alasan penolakan…">{{ old('resolution', $claim->resolution) }}</textarea>
            @error('resolution')<div class="text-danger small">{{ $message }}</div>@enderror

            <button class="btn-brand" type="submit"><i class="fas fa-paper-plane"></i> Simpan tanggapan</button>
        </form>
    </div>
</div>
@endsection
