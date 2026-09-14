@extends('layouts.customer')

@section('title', 'EcoCraft | Katalog')

@section('content')
<section class="section">
    <div class="page-wrap">
        <div class="section-head" style="flex-wrap:wrap">
            <div>
                <div class="eyebrow">Katalog EcoCraft</div>
                <h1>Semua karya terkurasi</h1>
                <p>Produk kerajinan dari pengrajin terverifikasi, bahan bertanggung jawab, siap menemani harimu.</p>
            </div>
            <form class="catalog-search" action="{{ route('catalog.index') }}" method="GET">
                <label class="sr-only" for="q">Cari produk</label>
                <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Cari nama, bahan, atau kategori…">
                <button class="btn btn-brand" type="submit">Cari</button>
            </form>
        </div>

        @if(request()->filled('q'))
            <p class="text-muted mb-4">Menampilkan hasil untuk <strong>"{{ e(request('q')) }}"</strong> ({{ $products->total() }} produk)</p>
        @endif

        <div class="product-grid">
            @forelse($products as $product)
                <article class="product-card">
                    <a class="product-card-image" href="{{ route('product.show', $product->id_products) }}">
                        <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $product->name }}">
                    </a>
                    <div class="product-card-body">
                        <small class="eyebrow">{{ $product->category ?: 'Karya pilihan' }}</small>
                        <h3><a href="{{ route('product.show', $product->id_products) }}">{{ $product->name }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit($product->description, 70) }}</p>
                        <small class="d-block text-muted mb-2">Oleh {{ optional($product->seller)->store_name ?: 'Mitra EcoCraft' }}</small>
                        @auth('customer')
                            <form class="card-buy" action="{{ route('cart.items.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id_products }}">
                                <div class="card-buy-row">
                                    <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <div class="qty-stepper" data-qty-stepper>
                                        <button type="button" class="qty-btn" data-qty-minus aria-label="Kurangi">&minus;</button>
                                        <input type="text" inputmode="numeric" name="quantity" value="1" class="qty-input" data-qty-input readonly>
                                        <button type="button" class="qty-btn" data-qty-plus aria-label="Tambah">+</button>
                                    </div>
                                </div>
                                <div class="card-buy-actions">
                                    <button class="btn btn-sm btn-outline-brand card-add-btn" title="Tambah ke keranjang" type="submit"><i class="fa fa-cart-plus"></i> Tambah</button>
                                    <button class="btn btn-sm btn-brand card-buy-btn" title="Beli langsung" type="submit" name="buy_now" value="1"><i class="fa fa-bolt"></i> Beli</button>
                                </div>
                            </form>
                        @else
                            <div class="card-buy">
                                <div class="card-buy-row"><span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span></div>
                                <div class="card-buy-actions">
                                    <a class="btn btn-sm btn-outline-brand card-buy-btn" href="{{ route('product.show', $product->id_products) }}"><i class="fa fa-eye"></i> Lihat</a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </article>
            @empty
                <p class="text-muted">Belum ada produk yang cocok dengan pencarianmu.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-qty-stepper]').forEach(function (stepper) {
    var input = stepper.querySelector('[data-qty-input]');
    var max = 999;
    stepper.querySelector('[data-qty-minus]')?.addEventListener('click', function () {
        var v = parseInt(input.value, 10) || 1;
        input.value = Math.max(1, v - 1);
    });
    stepper.querySelector('[data-qty-plus]')?.addEventListener('click', function () {
        var v = parseInt(input.value, 10) || 1;
        input.value = Math.min(max, v + 1);
    });
});
</script>
@endpush
