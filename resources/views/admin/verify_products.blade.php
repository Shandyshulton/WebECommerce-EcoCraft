@extends('layout.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Verifikasi Produk</li>
@endsection

@section('content')
<h1>Verifikasi Produk</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Produk Menunggu Verifikasi --}}
<h3 class="mt-4">Produk Pending</h3>
<table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th>ID Produk</th>
            <th>Nama Produk</th>
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
                <form action="{{ route('admin.products.approve', $product->id_products) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                </form>

                <form action="{{ route('admin.products.reject', $product->id_products) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menolak produk ini?')">Tolak</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada produk yang menunggu verifikasi.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $pendingProducts->links() }}

{{-- Produk Sudah Diverifikasi --}}
<h3 class="mt-5">Produk yang Sudah Diproses</h3>
<table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th>ID Produk</th>
            <th>Nama Produk</th>
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
            <td colspan="6" class="text-center">Belum ada produk yang diproses.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $approvedProducts->links() }}
@endsection
