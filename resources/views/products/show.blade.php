@extends('layouts.customer')
@section('title', $product->name . ' | EcoCraft')
@push('styles')
<style>.detail-page{padding:34px 0 72px}.detail-back{display:inline-flex;gap:8px;color:var(--muted);font-size:12px;margin-bottom:24px}.detail-grid{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(320px,.95fr);gap:48px}.detail-media{position:relative;aspect-ratio:1/1;overflow:hidden;border-radius:16px;background:#f0ebe4;outline:0}.detail-media:focus-visible{box-shadow:0 0 0 3px rgba(30,75,56,.28)}.detail-track{display:flex;height:100%;touch-action:pan-y;transition:transform .35s cubic-bezier(.22,.61,.36,1)}.detail-track.is-dragging{transition:none}.detail-track img{flex:0 0 100%;width:100%;height:100%;object-fit:cover;user-select:none;-webkit-user-drag:none}.detail-nav{position:absolute;top:50%;transform:translateY(-50%);width:40px;height:40px;display:flex;align-items:center;justify-content:center;border:0;border-radius:50%;background:rgba(255,255,255,.92);color:var(--brand);font-size:14px;cursor:pointer;box-shadow:0 4px 14px rgba(27,37,32,.18);transition:background .2s,opacity .2s}.detail-nav:hover:not(:disabled){background:#fff}.detail-nav:disabled{opacity:.35;cursor:default}.detail-nav-prev{left:12px}.detail-nav-next{right:12px}.detail-count{position:absolute;right:12px;bottom:12px;padding:4px 11px;border-radius:999px;background:rgba(27,37,32,.62);color:#fff;font-size:11px;font-weight:700;letter-spacing:.02em}.detail-info{padding-top:8px}.detail-info h1{font:600 clamp(40px,5vw,64px)/.98 'EB Garamond',serif;margin:10px 0 18px}.detail-description{color:var(--muted);font-size:14px;line-height:1.8}.detail-meta{display:grid;grid-template-columns:1fr 1fr;gap:1px;margin:26px 0;background:var(--line);border:1px solid var(--line);border-radius:12px;overflow:hidden}.detail-meta div{padding:14px;background:#fff}.detail-meta small{display:block;color:var(--muted);font-size:10px;text-transform:uppercase;letter-spacing:.08em}.detail-meta strong{display:block;margin-top:5px;font-size:12px}.detail-buy{display:flex;gap:10px}.detail-buy input{width:88px;border:1px solid var(--line);border-radius:8px;padding:10px}.detail-buy .btn{flex:1}.detail-thumbs{display:flex;gap:8px;margin-top:10px;padding:2px 0;overflow-x:auto;scrollbar-width:none}.detail-thumbs::-webkit-scrollbar{display:none}.detail-thumb{flex:0 0 auto;width:64px;height:64px;padding:0;border:1px solid var(--line);border-radius:10px;overflow:hidden;background:#f0ebe4;cursor:pointer;transition:border-color .2s,box-shadow .2s}.detail-thumb img{width:100%;height:100%;object-fit:cover}.detail-thumb.is-active{border-color:var(--brand);box-shadow:0 0 0 2px rgba(30,75,56,.2)}.detail-inquiry{margin-top:22px;padding-top:22px;border-top:1px solid var(--line)}.detail-inquiry-head{display:flex;align-items:center;gap:8px;font-weight:700;font-size:13px;margin-bottom:12px}.detail-inquiry-head i{color:var(--brand)}.detail-inquiry textarea{width:100%;padding:11px 13px;border:1px solid var(--line);border-radius:10px;background:#fff;font:13px 'Plus Jakarta Sans';resize:vertical}.detail-inquiry textarea:focus{outline:0;border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}.detail-inquiry .btn{margin-top:10px}.detail-inquiry-note{color:var(--muted);font-size:12px;margin:0 0 10px}@media(max-width:760px){.detail-page{padding:22px 0 48px}.detail-grid{grid-template-columns:1fr;gap:24px}.detail-info h1{font-size:44px}}
.detail-impact{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:0 0 26px}
.detail-impact-item{display:flex;align-items:center;gap:11px;padding:13px 15px;border:1px solid var(--line);border-radius:12px;background:#f6faf7}
.detail-impact-item i{font-size:16px;color:var(--brand)}
.detail-impact-item small{display:block;font-size:9px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted)}
.detail-impact-item strong{display:block;margin-top:3px;font-size:13px;color:var(--ink)}
@media(max-width:760px){.detail-impact{grid-template-columns:1fr}}
</style>
@endpush
@section('content')
<main class="section detail-page"><div class="page-wrap"><a class="detail-back" href="{{ route('customer.dashboard') }}">&larr; Kembali ke katalog</a><div class="detail-grid"><div class="detail-media-wrap">@php($gallery = is_array($product->image_gallery) ? $product->image_gallery : (json_decode($product->image_gallery ?? '[]', true) ?: []))@php($mainImg = $product->image_url ? asset('storage/'.$product->image_url) : asset('assets/images/collection/arrivals1.png'))@php($images = array_merge([$mainImg], array_map(fn ($g) => asset('storage/'.$g), $gallery)))<div class="detail-media" id="detailMedia" tabindex="0" role="group" aria-label="Galeri produk"><div class="detail-track" id="detailTrack">@foreach($images as $src)<img src="{{ $src }}" alt="{{ $product->name }}" draggable="false">@endforeach</div>@if(count($images) > 1)<button type="button" class="detail-nav detail-nav-prev" data-detail-prev aria-label="Gambar sebelumnya"><i class="fa fa-chevron-left"></i></button><button type="button" class="detail-nav detail-nav-next" data-detail-next aria-label="Gambar berikutnya"><i class="fa fa-chevron-right"></i></button><span class="detail-count"><b data-detail-index>1</b>/{{ count($images) }}</span>@endif</div>@if(count($images) > 1)<div class="detail-thumbs" data-detail-thumbs>@foreach($images as $i => $src)<button type="button" class="detail-thumb{{ $i === 0 ? ' is-active' : '' }}" data-detail-thumb="{{ $i }}" aria-label="Lihat gambar {{ $i + 1 }}"><img src="{{ $src }}" alt="" draggable="false"></button>@endforeach</div>@endif</div><div class="detail-info"><div class="eyebrow">{{ $product->category ?: 'Karya pilihan' }}</div><h1>{{ $product->name }}</h1><div class="price h3 mb-3">Rp {{ number_format($product->price,0,',','.') }}</div><p class="detail-description">{{ $product->description ?: 'Produk handmade pilihan dari EcoCraft.' }}</p><div class="detail-meta"><div><small>Material</small><strong>{{ $product->material_type }}</strong></div><div><small>Stok</small><strong>{{ $product->quantity }} item</strong></div><div><small>Seller</small><strong>{{ $product->seller?->store_name ?? 'EcoCraft seller' }}</strong></div><div><small>Status</small><strong>Siap dikirim</strong></div></div><div class="detail-impact"><div class="detail-impact-item"><i class="fa fa-recycle" aria-hidden="true"></i><div><small>Limbah dialihkan</small><strong>{{ number_format((float) $product->waste_factor, 2, ',', '.') }} kg / pcs</strong></div></div><div class="detail-impact-item"><i class="fa fa-leaf" aria-hidden="true"></i><div><small>Emisi dihindari</small><strong>{{ number_format((float) $product->carbon_factor, 2, ',', '.') }} kg CO<sub>2</sub>e / pcs</strong></div></div></div>@auth('customer')
<form action="{{ route('cart.items.store') }}" method="POST" class="detail-buy">@csrf<input type="hidden" name="product_id" value="{{ $product->id_products }}"><input type="number" name="quantity" value="1" min="1" max="{{ max(1,$product->quantity) }}" aria-label="Jumlah"><button class="btn btn-brand" type="submit">Tambah ke keranjang</button></form>
@else
<p class="detail-inquiry-note">Masuk atau daftar akun dulu untuk menambahkan produk ini ke keranjang.</p>
<div class="detail-buy"><a class="btn btn-outline-brand" href="{{ route('login') }}"><i class="fa fa-sign-in-alt"></i> Masuk</a><a class="btn btn-brand" href="{{ route('register') }}"><i class="fa fa-user-plus"></i> Daftar Akun</a></div>
@endauth
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
(function () {
    var media = document.getElementById('detailMedia');
    var track = document.getElementById('detailTrack');
    if (!media || !track) return;

    var total = track.children.length;
    if (total < 2) return;

    var thumbsWrap = document.querySelector('[data-detail-thumbs]');
    var thumbs = Array.prototype.slice.call(document.querySelectorAll('[data-detail-thumb]'));
    var counter = document.querySelector('[data-detail-index]');
    var prevBtn = document.querySelector('[data-detail-prev]');
    var nextBtn = document.querySelector('[data-detail-next]');
    var index = 0;
    var startX = 0;
    var dx = 0;
    var dragging = false;

    function paint(offset) {
        track.style.transform = 'translateX(calc(' + (-index * 100) + '% + ' + offset + 'px))';
    }

    function goTo(target) {
        index = Math.max(0, Math.min(total - 1, target));
        dx = 0;
        track.classList.remove('is-dragging');
        paint(0);
        if (counter) counter.textContent = index + 1;
        if (prevBtn) prevBtn.disabled = index === 0;
        if (nextBtn) nextBtn.disabled = index === total - 1;
        thumbs.forEach(function (thumb, i) {
            thumb.classList.toggle('is-active', i === index);
        });
        if (thumbsWrap && thumbs[index]) {
            thumbsWrap.scrollTo({
                left: thumbs[index].offsetLeft - (thumbsWrap.clientWidth - thumbs[index].offsetWidth) / 2,
                behavior: 'smooth'
            });
        }
    }

    if (prevBtn) prevBtn.addEventListener('click', function () { goTo(index - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goTo(index + 1); });
    thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            goTo(parseInt(thumb.getAttribute('data-detail-thumb'), 10));
        });
    });

    media.addEventListener('pointerdown', function (e) {
        if (e.target.closest('.detail-nav')) return;
        dragging = true;
        startX = e.clientX;
        dx = 0;
        track.classList.add('is-dragging');
    });
    media.addEventListener('pointermove', function (e) {
        if (!dragging) return;
        dx = e.clientX - startX;
        paint(dx);
    });
    function endDrag() {
        if (!dragging) return;
        dragging = false;
        var threshold = Math.max(40, Math.min(90, media.clientWidth * 0.15));
        if (dx <= -threshold) goTo(index + 1);
        else if (dx >= threshold) goTo(index - 1);
        else goTo(index);
    }
    media.addEventListener('pointerup', endDrag);
    media.addEventListener('pointercancel', endDrag);
    media.addEventListener('pointerleave', endDrag);

    media.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') { e.preventDefault(); goTo(index - 1); }
        if (e.key === 'ArrowRight') { e.preventDefault(); goTo(index + 1); }
    });

    goTo(0);
})();
</script>
@endsection
