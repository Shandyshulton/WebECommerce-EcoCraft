@extends('layout.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.sellers.verify') }}">Verifikasi Seller</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Seller</li>
@endsection

@section('content')
<h1>Detail Seller: {{ $seller->name_sellers }}</h1>

<table class="table table-bordered">
    <tr>
        <th>ID Seller</th>
        <td>{{ $seller->id_sellers }}</td>
    </tr>
    <tr>
        <th>Nama Seller</th>
        <td>{{ $seller->name_sellers }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $seller->email }}</td>
    </tr>
    <tr>
        <th>Phone Number</th>
        <td>{{ $seller->phone_number }}</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>{{ $seller->address }}</td>
    </tr>
    <tr>
        <th>Province</th>
        <td>{{ $seller->province ?? '-' }}</td>
    </tr>
    <tr>
        <th>City</th>
        <td>{{ $seller->city ?? '-' }}</td>
    </tr>
    <tr>
        <th>Gender</th>
        <td>{{ ucfirst($seller->gender) }}</td>
    </tr>
    <tr>
        <th>Store Name</th>
        <td>{{ $seller->store_name }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            @if($seller->status === 'approved')
                <span class="badge bg-success">Disetujui</span>
            @elseif($seller->status === 'rejected')
                <span class="badge bg-danger">Ditolak</span>
            @else
                <span class="badge bg-secondary">Pending</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>KTP</th>
        <td>
            @if($seller->ktp_image)
                <img src="{{ asset('storage/ktp_sellers/' . basename($seller->ktp_image)) }}" alt="KTP" width="300" />
            @else
                Tidak ada KTP
            @endif
        </td>
    </tr>
</table>

<a href="{{ route('admin.sellers.verify') }}" class="btn btn-secondary">Kembali</a>

@endsection
