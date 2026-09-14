@extends('seller.dashboard')

@section('breadcrumb')<span class="current">Katalog Produk</span>@endsection

@section('content')
<div class="seller-page">
    <div class="seller-toolbar">
        <div>
            <div class="eyebrow">Katalog toko</div>
            <h1>Produk saya</h1>
            <p class="subtle mb-0">Kelola karya, stok, dan status kurasi produkmu.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn-brand"><i class="fas fa-plus"></i> Tambah produk</a>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="stat-grid mb-4">
        <div class="stat-card"><div class="icon"><i class="fas fa-box-open"></i></div><strong>{{ $stats['total'] }}</strong><span>Total produk</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-circle-check"></i></div><strong>{{ $stats['active'] }}</strong><span>Produk aktif</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-hourglass-half"></i></div><strong>{{ $stats['pending'] }}</strong><span>Menunggu kurasi</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-triangle-exclamation"></i></div><strong>{{ $stats['out'] }}</strong><span>Stok habis</span></div>
    </div>

    <form class="filter-bar" method="GET">
        <div class="filter-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama produk…">
        </div>
        <select name="status" class="filter-select">
            <option value="">Semua status</option>
            <option value="pending" @selected(request('status')==='pending')>Pending</option>
            <option value="approved" @selected(request('status')==='approved')>Disetujui</option>
            <option value="rejected" @selected(request('status')==='rejected')>Ditolak</option>
        </select>
        <select name="category" class="filter-select">
            <option value="">Semua kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(request('category')===$cat)>{{ $cat }}</option>
            @endforeach
        </select>
        <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
        @if(request()->hasAny(['q','status','category']))
            <a class="filter-reset" href="{{ route('products.index') }}">Reset</a>
        @endif
    </form>

    <div class="data-card">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="cell-product">
                                    <img class="cell-thumb" src="{{ $product->image_url ? asset('storage/'.$product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $product->name }}">
                                    <div>
                                        <div class="cell-title">{{ $product->name }}</div>
                                        <span class="cell-sub">{{ $product->slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="cell-price">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->category ?: '—' }}</td>
                            <td>
                                @if($product->in_stock)
                                    <span class="badge-status ok"><i class="fas fa-circle-check"></i> Tersedia</span>
                                @else
                                    <span class="badge-status off"><i class="fas fa-circle-xmark"></i> Habis</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('products.edit', $product->id_products) }}" class="btn-icon edit"><i class="fas fa-pen"></i> Edit</a>
                                    <form action="{{ route('products.destroy', $product->id_products) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="6">Belum ada produk. Tekan "Tambah produk" untuk memulai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $products->links() }}</div>
</div>
@endsection
