@extends('seller.dashboard')

@section('breadcrumb')<a href="{{ route('seller.inquiries.index') }}">Pertanyaan</a><span class="current">Percakapan</span>@endsection

@section('content')
<style>
    .chat-log { display:flex; flex-direction:column; gap:12px; padding:20px; border:1px solid var(--line); border-radius:14px; background:#fff; min-height:220px; }
    .chat-row { display:flex; align-items:flex-end; gap:8px; }
    .chat-row.mine { justify-content:flex-end; }
    .chat-avatar { width:30px; height:30px; flex:0 0 30px; border-radius:50%; object-fit:cover; background:#e9e4dc; }
    .bubble { max-width:72%; padding:10px 14px; border-radius:14px; font-size:13px; line-height:1.5; }
    .bubble small { display:block; margin-top:5px; font-size:10px; opacity:.7; }
    .bubble.me { align-self:flex-end; background:var(--brand); color:#fff; border-bottom-right-radius:4px; }
    .bubble.them { align-self:flex-start; background:var(--soft); border-bottom-left-radius:4px; }
    .thread-peer { display:flex; align-items:center; gap:12px; }
    .thread-peer img { width:46px; height:46px; border-radius:50%; object-fit:cover; background:#e9e4dc; }
    .chat-form { display:flex; gap:10px; margin-top:14px; }
    .chat-form textarea { flex:1; min-height:46px; max-height:140px; padding:11px 13px; border:1px solid var(--line); border-radius:10px; background:#fff; font:13px 'Plus Jakarta Sans'; resize:vertical; }
    .chat-form textarea:focus { outline:0; border-color:var(--brand); box-shadow:0 0 0 3px rgba(30,75,56,.12); }
</style>

@php($customerAvatar = optional($inquiry->customer)->profile_image ? asset('storage/'.$inquiry->customer->profile_image) : asset('images/default-profile.png'))
@php($sellerAvatar = optional(Auth::guard('seller')->user())->profile_image ? asset('storage/'.Auth::guard('seller')->user()->profile_image) : asset('images/default-profile.png'))

<div class="page-heading">
    <div class="thread-peer">
        <img src="{{ $customerAvatar }}" alt="{{ optional($inquiry->customer)->name_customers }}">
        <div>
            <div class="eyebrow">Percakapan · {{ optional($inquiry->product)->name ?? $inquiry->subject ?? 'Produk EcoCraft' }}</div>
            <h1 style="font-size:30px;margin:2px 0 0">{{ optional($inquiry->customer)->name_customers ?? 'Customer' }}</h1>
            <p class="subtle mb-0">@if(optional($inquiry->customer)->email){{ $inquiry->customer->email }}@endif</p>
        </div>
    </div>
    <a class="btn-brand" href="{{ route('seller.inquiries.index') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<section class="panel">
    <div class="chat-log">
        @forelse($inquiry->messages as $message)
            <div class="chat-row {{ $message->sender_type === 'seller' ? 'mine' : 'theirs' }}">
                @if($message->sender_type !== 'seller')
                    <img class="chat-avatar" src="{{ $customerAvatar }}" alt="{{ optional($inquiry->customer)->name_customers }}">
                @endif
                <div class="bubble {{ $message->sender_type === 'seller' ? 'me' : 'them' }}">
                    {{ $message->body }}
                    <small>{{ $message->sender_type === 'seller' ? 'Kamu (Seller)' : (optional($inquiry->customer)->name_customers ?? 'Customer') }} · {{ $message->created_at->diffForHumans() }}</small>
                </div>
                @if($message->sender_type === 'seller')
                    <img class="chat-avatar" src="{{ $sellerAvatar }}" alt="Kamu">
                @endif
            </div>
        @empty
            <p class="subtle m-auto">Belum ada pesan.</p>
        @endforelse
    </div>

    <form class="chat-form" action="{{ route('seller.inquiries.reply', $inquiry->id_inquiries) }}" method="POST">
        @csrf
        <textarea name="body" placeholder="Tulis balasan untuk customer…" required>{{ old('body') }}</textarea>
        <button class="btn-brand" type="submit"><i class="fas fa-paper-plane"></i> Kirim</button>
    </form>
    @error('body')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
</section>
@endsection
