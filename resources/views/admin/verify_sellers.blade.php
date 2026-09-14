@extends('layout.app')

@section('breadcrumb')
    <span class="current">Verifikasi Seller</span>
@endsection

@section('content')
<div class="page-heading"><div><div class="eyebrow">People & access</div><h1>Verifikasi Seller</h1><p class="subtle mb-0">Pastikan setiap toko yang masuk memiliki identitas dan cerita yang jelas.</p></div></div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form class="filter-bar" method="GET">
    <div class="filter-search">
        <i class="fas fa-magnifying-glass"></i>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / toko / email seller…">
    </div>
    <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
    @if(request('q'))<a class="filter-reset" href="{{ route('admin.sellers.verify') }}">Reset</a>@endif
</form>

<div class="stat-grid mb-4">
    <div class="stat-card"><div class="icon"><i class="fas fa-store"></i></div><strong>{{ $stats['total'] }}</strong><span>Total seller</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-hourglass-half"></i></div><strong>{{ $stats['pending'] }}</strong><span>Menunggu review</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-circle-check"></i></div><strong>{{ $stats['approved'] }}</strong><span>Disetujui</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-circle-xmark"></i></div><strong>{{ $stats['rejected'] }}</strong><span>Ditolak</span></div>
</div>

{{-- Permintaan Baru --}}
<div class="table-section-title"><div><h2>Permintaan Baru</h2><p>Seller yang membutuhkan review admin.</p></div><span class="status pending">{{ $pendingSellers->total() }} menunggu</span></div>
<div class="admin-table-scroll"><table class="table">
    <thead>
        <tr>
            <th>ID Seller</th> {{-- Kolom ID --}}
            <th>Nama Seller</th>
            <th>Email</th>
            <th>Store Name</th>
            <th>KTP</th>
            <th>Aksi</th>
            <th>Action</th> {{-- Kolom tombol View --}}
        </tr>
    </thead>
    <tbody>
        @forelse($pendingSellers as $seller)
        <tr>
            <td>{{ $seller->id_sellers }}</td>
            <td>{{ $seller->name_sellers }}</td>
            <td>{{ $seller->email }}</td>
            <td>{{ $seller->store_name }}</td>
            <td>
                @if($seller->ktp_image)
                    <img src="{{ asset('storage/ktp_sellers/' . basename($seller->ktp_image)) }}" alt="KTP" width="100" data-zoomable data-full="{{ asset('storage/ktp_sellers/' . basename($seller->ktp_image)) }}" />
                @else
                    Tidak ada KTP
                @endif
            </td>
            <td>
                <div class="row-actions">
                    <form action="{{ route('admin.seller.approve', $seller->id_sellers) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Approve</button>
                    </form>
                    <form action="{{ route('admin.seller.reject', $seller->id_sellers) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menolak seller ini?')"><i class="fas fa-xmark"></i> Reject</button>
                    </form>
                </div>
            </td>
            <td>
                <a href="{{ route('admin.show', $seller->id_sellers) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Tidak ada permintaan baru.</td>
        </tr>
        @endforelse
    </tbody>
</table></div>
{{ $pendingSellers->links() }}

{{-- Riwayat Permintaan Sudah Diproses --}}
<div class="table-section-title"><div><h2>Riwayat Seller</h2><p>Seller yang sudah disetujui atau ditolak.</p></div></div>
<div class="admin-table-scroll"><table class="table">
    <thead>
        <tr>
            <th>ID Seller</th> {{-- Kolom ID --}}
            <th>Nama Seller</th>
            <th>Email</th>
            <th>Store Name</th>
            <th>Status</th>
            <th>KTP</th>
            <th>Action</th> {{-- Kolom tombol View --}}
        </tr>
    </thead>
    <tbody>
        @forelse($processedSellers as $seller)
        <tr>
            <td>{{ $seller->id_sellers }}</td>
            <td>{{ $seller->name_sellers }}</td>
            <td>{{ $seller->email }}</td>
            <td>{{ $seller->store_name }}</td>
            <td>
                @if($seller->status === 'approved')
                    <span class="badge bg-success">Disetujui</span>
                @else
                    <span class="badge bg-danger">Ditolak</span>
                @endif
            </td>
            <td>
                @if($seller->ktp_image)
                    <img src="{{ asset('storage/ktp_sellers/' . basename($seller->ktp_image)) }}" alt="KTP" width="100" data-zoomable data-full="{{ asset('storage/ktp_sellers/' . basename($seller->ktp_image)) }}" />
                @else
                    Tidak ada KTP
                @endif
            </td>
            <td>
                <a href="{{ route('admin.show', $seller->id_sellers) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Belum ada seller yang diproses.</td>
        </tr>
        @endforelse
    </tbody>
</table></div>
{{ $processedSellers->links() }}
@endsection
