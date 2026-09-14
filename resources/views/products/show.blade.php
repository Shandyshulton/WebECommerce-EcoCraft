@extends('layouts.customer')
@section('title', $product->name . ' | EcoCraft')
@push('styles')
<style>.detail-page{padding:34px 0 72px}.detail-back{display:inline-flex;gap:8px;color:var(--muted);font-size:12px;margin-bottom:24px}.detail-grid{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(320px,.95fr);gap:48px}.detail-media{aspect-ratio:1/1;overflow:hidden;border-radius:16px;background:#f0ebe4}.detail-media img{width:100%;height:100%;object-fit:cover}.detail-info{padding-top:8px}.detail-info h1{font:600 clamp(40px,5vw,64px)/.98 'EB Garamond',serif;margin:10px 0 18px}.detail-description{color:var(--muted);font-size:14px;line-height:1.8}.detail-meta{display:grid;grid-template-columns:1fr 1fr;gap:1px;margin:26px 0;background:var(--line);border:1px solid var(--line);border-radius:12px;overflow:hidden}.detail-meta div{padding:14px;background:#fff}.detail-meta small{display:block;color:var(--muted);font-size:10px;text-transform:uppercase;letter-spacing:.08em}.detail-meta strong{display:block;margin-top:5px;font-size:12px}.detail-buy{display:flex;gap:10px}.detail-buy input{width:88px;border:1px solid var(--line);border-radius:8px;padding:10px}.detail-buy .btn{flex:1}.detail-thumbs{display:flex;gap:8px;margin-top:10px;flex-wrap:wrap}.detail-thumb{width:64px;height:64px;padding:0;border:1px solid var(--line);border-radius:10px;overflow:hidden;background:#f0ebe4;cursor:pointer}.detail-thumb img{width:100%;height:100%;object-fit:cover}.detail-thumb.is-active{border-color:var(--brand);box-shadow:0 0 0 2px rgba(30,75,56,.2)}.detail-inquiry{margin-top:22px;padding-top:22px;border-top:1px solid var(--line)}.detail-inquiry-head{display:flex;align-items:center;gap:8px;font-weight:700;font-size:13px;margin-bottom:12px}.detail-inquiry-head i{color:var(--brand)}.detail-inquiry textarea{width:100%;padding:11px 13px;border:1px solid var(--line);border-radius:10px;background:#fff;font:13px 'Plus Jakarta Sans';resize:vertical}.detail-inquiry textarea:focus{outline:0;border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}.detail-inquiry .btn{margin-top:10px}.detail-inquiry-note{color:var(--muted);font-size:12px;margin:0 0 10px}@media(max-width:760px){.detail-page{padding:22px 0 48px}.detail-grid{grid-template-columns:1fr;gap:24px}.detail-info h1{font-size:44px}}
</style>
@endpush
@section('content')
<main class="section detail-page"><div class="page-wrap"><a class="detail-back" href="{{ route('customer.dashboard') }}">&larr; Kembali ke katalog</a><div class="detail-grid"><div class="detail-media-wrap">@php($gallery = is_array($product->image_gallery) ? $product->image_gallery : (json_decode($product->image_gallery ?? '[]', true) ?: []))@php($mainImg = $product->image_url ? asset('storage/'.$product->image_url) : asset('assets/images/collection/arrivals1.png'))<div class="detail-media"><img id="detailMainImg" src="{{ $mainImg }}" alt="{{ $product->name }}"></div>@if(count($gallery))<div class="detail-thumbs"><button type="button" class="detail-thumb is-active" onclick="swapDetailImg(this,'{{ $mainImg }}')"><img src="{{ $mainImg }}" alt=""></button>@foreach($gallery as $g)<button type="button" class="detail-thumb" onclick="swapDetailImg(this,'{{ asset('storage/'.$g) }}')"><img src="{{ asset('storage/'.$g) }}" alt=""></button>@endforeach</div>@endif</div><div class="detail-info"><div class="eyebrow">{{ $product->category ?: 'Karya pilihan' }}</div><h1>{{ $product->name }}</h1><div class="price h3 mb-3">Rp {{ number_format($product->price,0,',','.') }}</div><p class="detail-description">{{ $product->description ?: 'Produk handmade pilihan dari EcoCraft.' }}</p><div class="detail-meta"><div><small>Material</small><strong>{{ $product->material_type }}</strong></div><div><small>Stok</small><strong>{{ $product->quantity }} item</strong></div><div><small>Seller</small><strong>{{ $product->seller?->store_name ?? 'EcoCraft seller' }}</strong></div><div><small>Status</small><strong>Siap dikirim</strong></div></div><form action="{{ route('cart.items.store') }}" method="POST" class="detail-buy">@csrf<input type="hidden" name="product_id" value="{{ $product->id_products }}"><input type="number" name="quantity" value="1" min="1" max="{{ max(1,$product->quantity) }}" aria-label="Jumlah"><button class="btn btn-brand" type="submit">Tambah ke keranjang</button></form>
<div class="detail-inquiry">
    <div class="detail-inquiry-head"><i class="fa fa-comments"></i> Ada pertanyaan sebelum membeli?</div>
    @auth('customer')
        <form action="{{ route('customer.inquiries.store', $product->id_products) }}" method="POST">
            @csrf
            <textarea name="body" rows="3" placeholder="Tulis pertanyaanmu tentang produk ini untuk seller…" required>{{ old('body') }}</textarea>
            <button class="btn btn-outline-brand" type="submit"><i class="fa fa-paper-plane"></i> Tanya seller</button>
        </form>
        @error('body')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
    @else
        <p class="detail-inquiry-note">Masuk sebagai customer untuk menanyakan produk ini ke seller.</p>
        <a class="btn btn-outline-brand" href="{{ route('login') }}"><i class="fa fa-sign-in-alt"></i> Masuk untuk bertanya</a>
    @endauth
</div></div></div></div></main>
<script>
function swapDetailImg(btn, src){
    document.getElementById('detailMainImg').src = src;
    document.querySelectorAll('.detail-thumb').forEach(function(t){ t.classList.remove('is-active'); });
    btn.classList.add('is-active');
}
</script>
@endsection
