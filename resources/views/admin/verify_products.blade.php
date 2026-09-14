@extends('layout.app')

@section('breadcrumb')
    <span class="current">Verifikasi Produk</span>
@endsection

@section('content')
<div class="page-heading"><div><div class="eyebrow">Catalog quality</div><h1>Verifikasi Produk</h1><p class="subtle mb-0">Jaga agar setiap karya yang tampil tetap layak, jelas, dan relevan.</p></div></div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form class="filter-bar" method="GET">
    <div class="filter-search">
        <i class="fas fa-magnifying-glass"></i>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama produk / seller / toko…">
    </div>
    <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
    @if(request('q'))<a class="filter-reset" href="{{ route('admin.products.verify') }}">Reset</a>@endif
</form>

<div class="stat-grid mb-4">
    <div class="stat-card"><div class="icon"><i class="fas fa-box-open"></i></div><strong>{{ $stats['total'] }}</strong><span>Total produk</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-hourglass-half"></i></div><strong>{{ $stats['pending'] }}</strong><span>Menunggu review</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-circle-check"></i></div><strong>{{ $stats['approved'] }}</strong><span>Disetujui</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-circle-xmark"></i></div><strong>{{ $stats['rejected'] }}</strong><span>Ditolak</span></div>
</div>

{{-- Produk Menunggu Verifikasi --}}
<div class="table-section-title"><div><h2>Produk Pending</h2><p>Produk yang menunggu keputusan kurasi.</p></div><span class="status pending">{{ $pendingProducts->total() }} menunggu</span></div>
<div class="admin-table-scroll"><table class="table">
    <thead>
        <tr>
            <th>ID Produk</th>
            <th>Nama Produk</th>
            <th>Seller</th>
            <th>Harga</th>
            <th>Gambar</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pendingProducts as $product)
        <tr>
            <td>{{ $product->id_products }}</td>
            <td>{{ $product->name }}</td>
            <td>
                <div class="cell-customer">
                    <strong>{{ optional($product->seller)->store_name ?? '—' }}</strong>
                    <small>{{ optional($product->seller)->name_sellers ?? 'Seller tidak diketahui' }}</small>
                </div>
            </td>
            <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
            <td>
                @if($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" width="100" alt="Gambar Produk">
                @else
                    Tidak ada gambar
                @endif
            </td>
            <td>{{ Str::limit($product->description, 50) }}</td>
            <td>
                <div class="row-actions">
                    <form action="{{ route('admin.products.approve', $product->id_products) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Setujui</button>
                    </form>
                    <form action="{{ route('admin.products.reject', $product->id_products) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menolak produk ini?')"><i class="fas fa-xmark"></i> Tolak</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Tidak ada produk yang menunggu verifikasi.</td>
        </tr>
        @endforelse
    </tbody>
</table></div>
{{ $pendingProducts->links() }}

{{-- Produk Sudah Diverifikasi --}}
<div class="table-section-title"><div><h2>Riwayat Produk</h2><p>Produk yang sudah diproses oleh admin.</p></div></div>
<div class="admin-table-scroll"><table class="table">
    <thead>
        <tr>
            <th>ID Produk</th>
            <th>Nama Produk</th>
            <th>Seller</th>
            <th>Harga</th>
            <th>Gambar</th>
            <th>Deskripsi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($approvedProducts as $product)
        <tr>
            <td>{{ $product->id_products }}</td>
            <td>{{ $product->name }}</td>
            <td>
                <div class="cell-customer">
                    <strong>{{ optional($product->seller)->store_name ?? '—' }}</strong>
                    <small>{{ optional($product->seller)->name_sellers ?? 'Seller tidak diketahui' }}</small>
                </div>
            </td>
            <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
            <td>
                @if($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" width="100" alt="Gambar Produk">
                @else
                    Tidak ada gambar
                @endif
            </td>
            <td>{{ Str::limit($product->description, 50) }}</td>
            <td>
                @if($product->status === 'approved')
                    <span class="badge bg-success">Disetujui</span>
                @elseif($product->status === 'rejected')
                    <span class="badge bg-danger">Ditolak</span>
                @else
                    <span class="badge bg-warning">Pending</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Belum ada produk yang diproses.</td>
        </tr>
        @endforelse
    </tbody>
</table></div>
{{ $approvedProducts->links() }}
@endsection
