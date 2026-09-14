@extends('layouts.customer')

@section('title', $story->title . ' | EcoCraft')
@push('styles')
<style>
    .story-page { padding:34px 0 60px; }
    .story-back { display:inline-flex; align-items:center; gap:8px; margin-bottom:18px; color:var(--muted); font-size:12px; font-weight:700; }
    .story-back:hover { color:var(--brand); }

    .story-hero { overflow:hidden; border:1px solid var(--line); border-radius:20px; background:var(--surface); }
    .story-hero img { display:block; width:100%; aspect-ratio:16/7; object-fit:cover; }

    .story-body-wrap { display:grid; grid-template-columns:minmax(0,1fr) 320px; gap:56px; margin-top:38px; align-items:start; }

    .story-kicker { display:flex; flex-wrap:wrap; align-items:center; gap:10px; margin-bottom:14px; color:var(--accent); font-size:11px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
    .story-kicker span { color:var(--muted); font-weight:600; letter-spacing:0; text-transform:none; }
    .story-title { margin:0 0 14px; font:600 clamp(32px,4.2vw,52px)/1.12 'EB Garamond',Georgia,serif; }
    .story-lead { max-width:64ch; margin:0; color:var(--muted); font-size:16px; line-height:1.75; }
    .story-rule { height:1px; margin:26px 0; border:0; background:linear-gradient(90deg,var(--line),transparent); }
    .story-text { max-width:66ch; }
    .story-text p { margin:0 0 18px; font-size:15.5px; line-height:1.9; color:#26342b; }
    .story-text p:last-child { margin-bottom:0; }

    .story-aside { position:sticky; top:96px; }
    .story-fact-card { padding:24px; border:1px solid var(--line); border-radius:18px; background:#fff; box-shadow:0 16px 36px -22px rgba(27,37,32,.22); }
    .story-fact-card h3 { margin:8px 0 0; font:600 22px/1.2 'EB Garamond',Georgia,serif; }
    .fact-list { display:grid; margin:16px 0 20px; }
    .fact-row { display:flex; justify-content:space-between; align-items:baseline; gap:16px; padding:11px 0; border-bottom:1px solid var(--line); }
    .fact-row:last-child { border-bottom:0; }
    .fact-row dt, .fact-row dd { margin:0; min-width:0; }
    .fact-row dd { display:flex; justify-content:flex-end; }
    .fact-row small { color:var(--muted); font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; }
    .fact-row strong { font-size:13.5px; text-align:right; }
    .story-cta { display:grid; gap:8px; }
    .story-cta .btn { width:100%; }

    .story-products { margin-top:72px; }
    .story-discussion { margin-top:64px; }

    .comment-form label { display:block; margin-bottom:8px; font-size:12px; font-weight:700; }
    .comment-form textarea { width:100%; min-height:108px; padding:12px 14px; border:1px solid var(--line); border-radius:12px; background:#fff; font:14px/1.6 'Plus Jakarta Sans'; color:var(--ink); resize:vertical; }
    .comment-form textarea:focus { outline:0; border-color:var(--brand); box-shadow:0 0 0 3px rgba(30,75,56,.12); }
    .comment-form-foot { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-top:10px; }
    .comment-form-foot small { color:var(--muted); font-size:11px; }

    .comment-guest { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px; padding:20px 22px; border:1px dashed var(--line); border-radius:14px; background:var(--surface); }
    .comment-guest p { margin:0; font-size:13px; color:var(--ink); }

    .comment-list { display:grid; gap:12px; margin-top:24px; }
    .comment-item { display:flex; gap:12px; padding:16px 18px; border:1px solid var(--line); border-radius:14px; background:#fff; }
    .comment-avatar { width:38px; height:38px; flex:0 0 38px; border-radius:50%; object-fit:cover; border:2px solid var(--line); background:var(--surface); }
    .comment-head { display:flex; align-items:baseline; flex-wrap:wrap; gap:4px 10px; }
    .comment-head strong { font-size:13px; }
    .comment-head span { color:var(--muted); font-size:11px; }
    .comment-body { min-width:0; flex:1; }
    .comment-body p { margin:6px 0 0; font-size:14px; line-height:1.65; color:#26342b; overflow-wrap:anywhere; }

    @media (max-width:900px) {
        .story-body-wrap { grid-template-columns:1fr; gap:30px; margin-top:28px; }
        .story-aside { position:static; }
    }
    @media (max-width:640px) {
        .story-page { padding:22px 0 44px; }
        .story-hero img { aspect-ratio:4/3; }
        .story-title { font-size:clamp(27px,7.4vw,36px); }
        .story-products { margin-top:52px; }
        .story-discussion { margin-top:44px; }
        .comment-form-foot { align-items:stretch; flex-direction:column; }
        .comment-form-foot .btn { width:100%; }
        .comment-guest .btn { width:100%; }
    }
</style>
@endpush

@section('content')
<main class="story-page">
    <div class="page-wrap">
        <a class="story-back" href="{{ route('community.index') }}">&larr; Kembali ke komunitas</a>

        <figure class="story-hero">
            <img src="{{ $story->image ? asset(str_replace(' ', '%20', $story->image)) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $story->title }}">
        </figure>

        <div class="story-body-wrap">
            <div class="story-copy">
                <div class="story-kicker">
                    {{ $story->topic ?? 'Sorotan Komunitas' }}
                    @if($story->label)<span>·</span>{{ $story->label }}@endif
                </div>
                <h1 class="story-title">{{ $story->title }}</h1>

                @if($story->excerpt)
                    <p class="story-lead">{{ $story->excerpt }}</p>
                @endif

                @if($story->body)
                    <hr class="story-rule">
                    <div class="story-text">
                        @foreach(preg_split('/\R{2,}/', trim($story->body)) as $paragraph)
                            @if(trim($paragraph))<p>{{ $paragraph }}</p>@endif
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="story-aside">
                <div class="story-fact-card">
                    <div class="eyebrow" style="color:var(--accent)">Sekilas sanggar</div>
                    <h3>{{ Str::contains($story->title, ',') ? trim(Str::before($story->title, ',')) : $story->title }}</h3>
                    <dl class="fact-list">
                        <div class="fact-row"><dt><small>Bahan</small></dt><dd><strong>{{ $story->material ? Str::ucfirst($story->material) : 'Beragam' }}</strong></dd></div>
                        <div class="fact-row"><dt><small>Kategori</small></dt><dd><strong>{{ $story->label ?: 'Kriya' }}</strong></dd></div>
                        <div class="fact-row"><dt><small>Kisah</small></dt><dd><strong>{{ $story->topic ?: 'Sorotan' }}</strong></dd></div>
                        <div class="fact-row"><dt><small>Lokasi</small></dt><dd><strong>{{ Str::contains($story->title, ',') ? trim(Str::after($story->title, ',')) : 'Nusantara' }}</strong></dd></div>
                    </dl>
                    <div class="story-cta">
                        <a class="btn btn-brand" href="#story-products">Lihat karya senada &darr;</a>
                        <a class="btn btn-outline-brand" href="{{ route('catalog.index') }}">Jelajahi katalog</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

<section id="story-products" class="member-section story-products">
    <div class="page-wrap">
        <div class="member-section-head">
            <div>
                <div class="eyebrow">Produk pendukung cerita</div>
                <h2>{{ $relatedLabel }}</h2>
            </div>
        </div>

        <div class="product-grid">
            @forelse($related as $product)
                <article class="product-card">
                    <a class="product-card-image" href="{{ route('product.show', $product->id_products) }}">
                        <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $product->name }}">
                    </a>
                    <div class="product-card-body">
                        <small class="eyebrow">{{ $product->category ?: 'Karya pilihan' }}</small>
                        <h3><a href="{{ route('product.show', $product->id_products) }}">{{ $product->name }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit($product->description, 70) }}</p>
                        <small class="d-block text-muted mb-2">Oleh {{ optional($product->seller)->store_name ?: 'Mitra EcoCraft' }}</small>
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <a class="btn btn-sm btn-outline-brand" href="{{ route('product.show', $product->id_products) }}">Lihat</a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-muted">Belum ada produk terkait.</p>
            @endforelse
        </div>
    </div>
</section>

<section id="diskusi" class="member-section story-discussion">
    <div class="page-wrap">
        <div class="member-section-head">
            <div>
                <div class="eyebrow">Tanya & berbagi</div>
                <h2>Diskusi komunitas</h2>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
        @endif

        @auth('customer')
            <form method="POST" action="{{ route('community.comments.store', $story->slug) }}" class="comment-form">
                @csrf
                <label for="comment">Tulis pertanyaan atau ceritamu untuk sanggar ini</label>
                <textarea id="comment" name="comment" rows="3" required maxlength="1000" placeholder="Misal: apakah bisa dipesan dengan ukuran khusus?">{{ old('comment') }}</textarea>
                <div class="comment-form-foot">
                    <small>Tanya seputar bahan, ukuran, atau cara merawat karya.</small>
                    <button class="btn btn-brand" type="submit">Kirim komentar</button>
                </div>
            </form>
        @else
            <div class="comment-guest">
                <p>Mau bertanya langsung kepada sanggar ini? Masuk sebagai Sahabat Pengrajin untuk ikut diskusi.</p>
                <a class="btn btn-brand" href="{{ route('login') }}">Masuk untuk bertanya</a>
            </div>
        @endauth

        <div class="comment-list">
            @forelse($comments as $comment)
                <article class="comment-item">
                    <img class="comment-avatar" src="{{ optional($comment->customer)->profile_image ? asset('storage/' . $comment->customer->profile_image) : asset('assets/logo/Logo_Eco-Craft-removebg-preview 1.png') }}" alt="">
                    <div class="comment-body">
                        <div class="comment-head">
                            <strong>{{ optional($comment->customer)->name_customers ?: 'Member EcoCraft' }}</strong>
                            <span>{{ $comment->created_at->locale('id')->diffForHumans() }}</span>
                        </div>
                        <p>{{ $comment->comment }}</p>
                    </div>
                </article>
            @empty
                <p class="text-muted">Belum ada pertanyaan. Jadilah yang pertama bertanya!</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
