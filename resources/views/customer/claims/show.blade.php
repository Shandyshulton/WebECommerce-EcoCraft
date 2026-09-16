@extends('layouts.customer')
@section('title', 'Detail Klaim Garansi | EcoCraft')

@push('styles')
<style>
    .claim-page { padding:34px 0 64px; }
    .claim-back { display:inline-flex; align-items:center; gap:8px; margin-bottom:18px; color:var(--muted); font-size:12px; font-weight:700; }
    .claim-back:hover { color:var(--brand); }

    .claim-hero { display:flex; align-items:flex-start; gap:16px; padding:20px; border:1px solid var(--line); border-radius:16px; background:#fff; }
    .claim-hero img { width:76px; height:76px; flex:0 0 76px; border-radius:14px; object-fit:cover; background:#e9e4dc; }
    .claim-hero-body { min-width:0; flex:1; }
    .claim-hero-body h1 { font:600 clamp(24px,3vw,34px)/1.1 'EB Garamond',serif; margin:6px 0 6px; }
    .claim-hero-body .claim-meta { margin:0; color:var(--muted); font-size:12px; }

    .claim-panel { margin-top:16px; padding:20px; border:1px solid var(--line); border-radius:16px; background:#fff; }
    .claim-panel h2 { font:600 20px/1.2 'EB Garamond',serif; margin:0 0 12px; }
    .claim-facts { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .claim-fact { padding:13px; border-radius:10px; background:var(--surface); }
    .claim-fact small { display:block; color:var(--muted); font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .claim-fact strong { display:block; margin-top:5px; font-size:13px; }
    .claim-desc { margin:0; color:#26342b; font-size:14.5px; line-height:1.85; }
    .claim-photo { display:block; margin-top:14px; width:100%; max-width:340px; border:1px solid var(--line); border-radius:12px; }

    .claim-timeline { display:grid; gap:0; margin:0; padding:0; list-style:none; }
    .claim-timeline li { position:relative; display:flex; gap:14px; padding:0 0 18px 0; }
    .claim-timeline li::before { content:''; position:absolute; left:7px; top:18px; bottom:0; width:2px; background:var(--line); }
    .claim-timeline li:last-child::before { display:none; }
    .claim-timeline .dot { position:relative; z-index:1; flex:0 0 16px; width:16px; height:16px; margin-top:2px; border:2px solid var(--line); border-radius:50%; background:#fff; }
    .claim-timeline li.done .dot { border-color:var(--brand); background:var(--brand); }
    .claim-timeline strong { display:block; font-size:13px; }
    .claim-timeline small { color:var(--muted); font-size:11px; }

    .claim-resolution { padding:16px; border-radius:12px; background:#edf4ee; border:1px solid #cfe1d3; }
    .claim-resolution strong { display:block; margin-bottom:6px; color:var(--brand); font-size:13px; }
    .claim-resolution p { margin:0; color:#26342b; font-size:13.5px; line-height:1.8; }
    .claim-awaiting { color:var(--muted); font-size:13px; margin:0; }

    @media (max-width:640px) {
        .claim-facts { grid-template-columns:1fr; }
        .claim-hero { display:block; }
        .claim-hero img { width:64px; height:64px; margin-bottom:12px; }
    }
</style>
@endpush

@section('content')
@php
    $steps = [
        'Submitted' => 'Klaim diajukan',
        'Reviewing' => 'Sedang diperiksa seller',
        'Approved' => 'Klaim disetujui',
        'Completed' => 'Selesai ditangani',
    ];
    $keys = array_keys($steps);
    $current = array_search($claim->status, $keys, true);
    $rejected = $claim->status === 'Rejected';
@endphp

<section class="claim-page">
    <div class="page-wrap" style="max-width:820px">
        <a class="claim-back" href="{{ route('customer.claims.index') }}">&larr; Kembali ke daftar klaim</a>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="claim-hero">
            <img src="{{ optional($claim->product)->image_url ? asset('storage/'.$claim->product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ optional($claim->product)->name ?? 'Produk' }}">
            <div class="claim-hero-body">
                <div class="eyebrow">Klaim garansi #{{ $claim->id_claims }}</div>
                <h1>{{ optional($claim->product)->name ?? optional($claim->item)->product_name ?? 'Produk EcoCraft' }}</h1>
                <p class="claim-meta">Diajukan {{ $claim->created_at->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <span class="badge-status {{ $claim->statusTone() }}">{{ $claim->statusLabel() }}</span>
        </div>

        <div class="claim-panel">
            <h2>Detail klaim</h2>
            <div class="claim-facts">
                <div class="claim-fact"><small>Pesanan</small><strong>#{{ optional($claim->order)->order_number ?? '—' }}</strong></div>
                <div class="claim-fact"><small>Kategori</small><strong>{{ $claim->categoryLabel() }}</strong></div>
                <div class="claim-fact"><small>Penjual</small><strong>{{ optional($claim->seller)->store_name ?? 'Mitra EcoCraft' }}</strong></div>
            </div>
            <p class="claim-desc" style="margin-top:16px">{{ $claim->description }}</p>
            @if($claim->photo)
                <a href="{{ asset('storage/'.$claim->photo) }}" target="_blank" rel="noopener">
                    <img class="claim-photo" src="{{ asset('storage/'.$claim->photo) }}" alt="Foto bukti klaim">
                </a>
            @endif
        </div>

        <div class="claim-panel">
            <h2>Status penanganan</h2>
            @if($rejected)
                <div class="claim-resolution" style="background:#ffe6e1;border-color:#f0c2b7">
                    <strong style="color:#a53c27">Klaim ditolak</strong>
                    <p>{{ $claim->resolution ?: 'Seller belum menuliskan alasan penolakan.' }}</p>
                </div>
            @else
                <ol class="claim-timeline">
                    @foreach($steps as $key => $label)
                        @php($index = array_search($key, $keys, true))
                        <li class="{{ ($current !== false && $index <= $current) ? 'done' : '' }}">
                            <span class="dot" aria-hidden="true"></span>
                            <div>
                                <strong>{{ $label }}</strong>
                                <small>{{ $claim->statusLabel() === $label ? 'Status saat ini' : ($index <= ($current === false ? -1 : $current) ? 'Selesai' : 'Menunggu') }}</small>
                            </div>
                        </li>
                    @endforeach
                </ol>
                @if($claim->responded_at && $claim->resolution)
                    <div class="claim-resolution" style="margin-top:16px">
                        <strong>Catatan seller</strong>
                        <p>{{ $claim->resolution }}</p>
                    </div>
                @else
                    <p class="claim-awaiting">Seller belum memberi tanggapan. Kami akan memperbarui status begitu ada kabar.</p>
                @endif
            @endif
        </div>
    </div>
</section>
@endsection
