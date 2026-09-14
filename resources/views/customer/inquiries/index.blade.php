@extends('layouts.customer')
@section('title', 'Pertanyaan Produk | EcoCraft')

@push('styles')
<style>
    .chat-wrap { padding:34px 0 64px; }
    .chat-head { margin-bottom:22px; }
    .chat-head h1 { font:600 clamp(30px,4vw,44px)/1 'EB Garamond',serif; margin:6px 0 6px; }
    .inquiry-list { display:grid; gap:12px; }
    .inquiry-item { display:flex; align-items:center; gap:14px; padding:16px; border:1px solid var(--line); border-radius:14px; background:#fff; transition:box-shadow .18s,transform .18s; }
    .inquiry-item:hover { box-shadow:0 8px 24px -6px rgba(27,37,32,.14); transform:translateY(-1px); }
    .inquiry-thumb { width:56px; height:56px; flex:0 0 56px; border-radius:50%; object-fit:cover; background:#e9e4dc; }
    .inquiry-body { min-width:0; flex:1; }
    .inquiry-body strong { display:block; font-size:14px; }
    .inquiry-body .inquiry-product { margin:2px 0 0; color:var(--brand); font-size:11px; font-weight:700; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .inquiry-body p { margin:3px 0 0; color:var(--muted); font-size:12px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .inquiry-meta { text-align:right; color:var(--muted); font-size:11px; white-space:nowrap; }
    .chat-empty { padding:40px; text-align:center; color:var(--muted); border:1px dashed var(--line); border-radius:14px; }
</style>
@endpush

@section('content')
<section class="chat-wrap">
    <div class="page-wrap" style="max-width:760px">
        <div class="chat-head">
            <div class="eyebrow">Bantuan pembelian</div>
            <h1>Pertanyaan Produk</h1>
            <p class="text-muted mb-0">Percakapanmu dengan seller EcoCraft seputar produk yang ingin kamu beli.</p>
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        @forelse($inquiries as $inquiry)
            <a class="inquiry-item text-decoration-none" href="{{ route('customer.inquiries.show', $inquiry->id_inquiries) }}" style="color:inherit">
                <img class="inquiry-thumb" src="{{ optional($inquiry->seller)->profile_image ? asset('storage/'.$inquiry->seller->profile_image) : asset('images/default-profile.png') }}" alt="{{ optional($inquiry->seller)->store_name }}">
                <div class="inquiry-body">
                    <strong>{{ optional($inquiry->seller)->store_name ?? 'Seller EcoCraft' }}</strong>
                    <p class="inquiry-product">{{ optional($inquiry->product)->name ?? $inquiry->subject ?? 'Produk EcoCraft' }}</p>
                    <p>{{ \Illuminate\Support\Str::limit(optional($inquiry->latestMessage)->body, 60) ?: 'Belum ada pesan.' }}</p>
                </div>
                <div class="inquiry-meta">{{ optional($inquiry->last_message_at)->diffForHumans() }}</div>
            </a>
        @empty
            <div class="inquiry-list"><div class="chat-empty">Belum ada pertanyaan. Buka halaman produk dan tekan "Tanya seller" untuk memulai.</div></div>
        @endforelse
    </div>
</section>
@endsection
