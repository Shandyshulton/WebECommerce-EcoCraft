@extends('layouts.customer')
@section('title', 'Chat Produk | EcoCraft')

@push('styles')
<style>
    .thread-wrap { padding:28px 0 64px; }
    .thread-top { display:flex; align-items:center; gap:14px; padding:16px; margin-bottom:18px; border:1px solid var(--line); border-radius:14px; background:#fff; }
    .thread-top .thread-avatar { width:52px; height:52px; flex:0 0 52px; border-radius:50%; object-fit:cover; background:#e9e4dc; }
    .thread-top strong { display:block; font-size:15px; }
    .thread-top small { color:var(--muted); }
    .thread-back { display:inline-flex; gap:8px; color:var(--muted); font-size:12px; margin-bottom:18px; }
    .chat-log { display:flex; flex-direction:column; gap:12px; padding:20px; border:1px solid var(--line); border-radius:14px; background:var(--surface); min-height:220px; }
    .chat-row { display:flex; align-items:flex-end; gap:8px; }
    .chat-row.mine { justify-content:flex-end; }
    .chat-avatar { width:30px; height:30px; flex:0 0 30px; border-radius:50%; object-fit:cover; background:#e9e4dc; }
    .bubble { max-width:72%; padding:10px 14px; border-radius:14px; font-size:13px; line-height:1.5; }
    .bubble small { display:block; margin-top:5px; font-size:10px; opacity:.7; }
    .bubble.me { align-self:flex-end; background:var(--brand); color:#fff; border-bottom-right-radius:4px; }
    .bubble.them { align-self:flex-start; background:#fff; border:1px solid var(--line); border-bottom-left-radius:4px; }
    .chat-form { display:flex; gap:10px; margin-top:14px; }
    .chat-form textarea { flex:1; min-height:46px; max-height:140px; padding:11px 13px; border:1px solid var(--line); border-radius:10px; background:#fff; font:13px 'Plus Jakarta Sans'; resize:vertical; }
    .chat-form textarea:focus { outline:0; border-color:var(--brand); box-shadow:0 0 0 3px rgba(30,75,56,.12); }
    .chat-empty-log { margin:auto; color:var(--muted); font-size:13px; }
</style>
@endpush

@section('content')
<section class="thread-wrap">
    <div class="page-wrap" style="max-width:760px">
        <a class="thread-back" href="{{ route('customer.inquiries.index') }}">&larr; Semua pertanyaan</a>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="thread-top">
            @php($sellerAvatar = optional($inquiry->seller)->profile_image ? asset('storage/'.$inquiry->seller->profile_image) : asset('images/default-profile.png'))
            <img class="thread-avatar" src="{{ $sellerAvatar }}" alt="{{ optional($inquiry->seller)->store_name }}">
            <div class="thread-top-copy">
                <strong>{{ optional($inquiry->seller)->store_name ?? 'Seller EcoCraft' }}</strong>
                <small>{{ optional($inquiry->seller)->name_sellers ? 'oleh '.$inquiry->seller->name_sellers.' · ' : '' }}{{ optional($inquiry->product)->name ?? $inquiry->subject ?? 'Produk EcoCraft' }}</small>
            </div>
            @if($inquiry->product)
                <a class="btn btn-sm btn-outline-brand ms-auto" href="{{ route('product.show', $inquiry->product->id_products) }}">Lihat produk</a>
            @endif
        </div>

        <div class="chat-log">
            @php($sellerAvatarSm = optional($inquiry->seller)->profile_image ? asset('storage/'.$inquiry->seller->profile_image) : asset('images/default-profile.png'))
            @php($customerAvatarSm = optional($navCustomer ?? Auth::guard('customer')->user())->profile_image ? asset('storage/'.(($navCustomer ?? Auth::guard('customer')->user())->profile_image)) : asset('images/default-profile.png'))
            @forelse($inquiry->messages as $message)
                <div class="chat-row {{ $message->sender_type === 'customer' ? 'mine' : 'theirs' }}">
                    @if($message->sender_type !== 'customer')
                        <img class="chat-avatar" src="{{ $sellerAvatarSm }}" alt="{{ optional($inquiry->seller)->store_name }}">
                    @endif
                    <div class="bubble {{ $message->sender_type === 'customer' ? 'me' : 'them' }}">
                        {{ $message->body }}
                        <small>{{ $message->sender_type === 'customer' ? 'Kamu' : (optional($inquiry->seller)->store_name ?? 'Seller') }} · {{ $message->created_at->diffForHumans() }}</small>
                    </div>
                    @if($message->sender_type === 'customer')
                        <img class="chat-avatar" src="{{ $customerAvatarSm }}" alt="Kamu">
                    @endif
                </div>
            @empty
                <p class="chat-empty-log">Belum ada pesan.</p>
            @endforelse
        </div>

        <form class="chat-form" action="{{ route('customer.inquiries.reply', $inquiry->id_inquiries) }}" method="POST">
            @csrf
            <textarea name="body" placeholder="Tulis pesan untuk seller…" required>{{ old('body') }}</textarea>
            <button class="btn btn-brand" type="submit"><i class="fa fa-paper-plane"></i></button>
        </form>
        @error('body')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
    </div>
</section>
@endsection
