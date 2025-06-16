@extends('layout.app')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Verifikasi Seller</li>
@endsection

@section('content')
<h1>Verifikasi Seller</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Permintaan Baru --}}
<h3 class="mt-4">Permintaan Baru</h3>
<table class="table table-bordered table-responsive">
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
                    <img src="{{ asset('storage/ktp_sellers/' . basename($seller->ktp_image)) }}" alt="KTP" width="100" />
                @else
                    Tidak ada KTP
                @endif
            </td>
            <td>
                <form action="{{ route('admin.seller.approve', $seller->id_sellers) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </form>

                <form action="{{ route('admin.seller.reject', $seller->id_sellers) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menolak seller ini?')">Reject</button>
                </form>
            </td>
            <td>
                <a href="{{ route('admin.show', $seller->id_sellers) }}" class="btn btn-primary btn-sm">
                    View
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Tidak ada permintaan baru.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $pendingSellers->links() }}

{{-- Riwayat Permintaan Sudah Diproses --}}
<h3 class="mt-5">Riwayat Permintaan yang Sudah Diproses</h3>
<table class="table table-bordered table-responsive">
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
                    <img src="{{ asset('storage/ktp_sellers/' . basename($seller->ktp_image)) }}" alt="KTP" width="100" />
                @else
                    Tidak ada KTP
                @endif
            </td>
            <td>
                <a href="{{ route('admin.show', $seller->id_sellers) }}" class="btn btn-primary btn-sm">
                    View
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Belum ada seller yang diproses.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $processedSellers->links() }}
@endsection
