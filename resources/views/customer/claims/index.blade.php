@extends('layouts.customer')
@section('title', 'Klaim Garansi | EcoCraft')

@push('styles')
<style>
    .claims-page { padding:34px 0 64px; }
    .claims-head { display:flex; align-items:end; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:22px; }
    .claims-head h1 { font:600 clamp(30px,4vw,44px)/1.05 'EB Garamond',serif; margin:6px 0 6px; }
    .claim-list { display:grid; gap:12px; }
    .claim-item { display:flex; align-items:center; gap:14px; padding:16px; border:1px solid var(--line); border-radius:14px; background:#fff; color:inherit; text-decoration:none; transition:box-shadow .18s,transform .18s; }
    .claim-item:hover { color:inherit; box-shadow:0 8px 24px -6px rgba(27,37,32,.14); transform:translateY(-1px); }
    .claim-thumb { width:58px; height:58px; flex:0 0 58px; border-radius:12px; object-fit:cover; background:#e9e4dc; }
    .claim-body { min-width:0; flex:1; }
    .claim-body strong { display:block; font-size:14px; }
    .claim-body p { margin:4px 0 0; color:var(--muted); font-size:12px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .claim-body .claim-meta { margin:2px 0 0; color:var(--muted); font-size:11px; }
    .claim-side { display:flex; flex-direction:column; align-items:flex-end; gap:6px; flex:0 0 auto; }
    .claim-side small { color:var(--muted); font-size:11px; }
    @media (max-width:640px) {
        .claim-item { align-items:flex-start; }
        .claim-thumb { width:46px; height:46px; flex-basis:46px; }
    }
</style>
@endpush

@section('content')
<section class="claims-page">
    <div class="page-wrap" style="max-width:820px">
        <div class="claims-head">
            <div>
                <div class="eyebrow">Layanan purna jual</div>
                <h1>Klaim Garansi</h1>
                <p class="text-muted mb-0">Ajukan perbaikan atau keluhan untuk produk yang sudah dikirim atau kamu terima.</p>
            </div>
            <a class="btn btn-brand" href="{{ route('customer.claims.create') }}"><i class="fa fa-plus"></i> Ajukan klaim</a>
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="claim-list">
            @forelse($claims as $claim)
                <a class="claim-item" href="{{ route('customer.claims.show', $claim->id_claims) }}">
                    <img class="claim-thumb" src="{{ optional($claim->product)->image_url ? asset('storage/'.$claim->product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ optional($claim->product)->name ?? 'Produk' }}">
                    <div class="claim-body">
                        <strong>{{ optional($claim->product)->name ?? optional($claim->item)->product_name ?? 'Produk EcoCraft' }}</strong>
                        <p class="claim-meta">#{{ optional($claim->order)->order_number }} · {{ $claim->categoryLabel() }}</p>
                        <p>{{ \Illuminate\Support\Str::limit($claim->description, 70) }}</p>
                    </div>
                    <div class="claim-side">
                        <span class="badge-status {{ $claim->statusTone() }}">{{ $claim->statusLabel() }}</span>
                        <small>{{ $claim->created_at->translatedFormat('d M Y') }}</small>
                    </div>
                </a>
            @empty
                <div class="history-empty">
                    <i class="fa fa-shield-heart" aria-hidden="true"></i>
                    <h2>Belum ada klaim</h2>
                    <p class="text-muted">Klaim garansi bisa diajukan untuk produk dari pesanan yang sudah dikirim atau diterima.</p>
                    <a class="btn btn-outline-brand" href="{{ route('track.track') }}">Lihat riwayat pembelian</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
