@extends('layout.app')

@section('breadcrumb')<span class="current">Kurir</span>@endsection

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">Operasional pengiriman</div>
        <h1>Petugas Kurir</h1>
        <p class="subtle mb-0">Kelola akun petugas yang mengantar paket pengiriman lokal EcoCraft.</p>
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="stat-grid mb-4" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-card"><div class="icon"><i class="fas fa-people-carry-box"></i></div><strong>{{ $stats['total'] }}</strong><span>Total petugas</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-circle-check"></i></div><strong>{{ $stats['active'] }}</strong><span>Aktif</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-circle-pause"></i></div><strong>{{ $stats['inactive'] }}</strong><span>Nonaktif</span></div>
</div>

<form class="filter-bar" method="GET">
    <div class="filter-search">
        <i class="fas fa-magnifying-glass"></i>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / email petugas…">
    </div>
    <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
    @if(request()->filled('q'))<a class="filter-reset" href="{{ route('admin.couriers') }}">Reset</a>@endif
</form>

<div class="admin-grid" style="grid-template-columns:1.4fr 1fr; align-items:start">
    {{-- Daftar petugas kurir --}}
    <div class="admin-table-scroll">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jasa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($couriers as $row)
                    <tr>
                        <td>{{ $row->name }}<br><span class="subtle">{{ $row->phone_number ?: '—' }}</span></td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->courier?->name ?? '—' }}</td>
                        <td>
                            @if($row->is_active)
                                <span class="status approved">Aktif</span>
                            @else
                                <span class="status" style="background:var(--soft);color:var(--muted)">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.couriers.toggle', $row->id_courier_users) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm {{ $row->is_active ? 'btn-danger' : 'btn-primary' }}" type="submit">
                                    {{ $row->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="subtle">Belum ada akun petugas kurir.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form tambah petugas --}}
    <section class="panel">
        <div class="panel-heading"><div><div class="eyebrow">Tambah akun</div><h2>Petugas baru</h2></div></div>
        <form action="{{ route('admin.couriers.store') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label class="form-label" style="font-size:11px;font-weight:800">Nama</label>
                <input class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-2">
                <label class="form-label" style="font-size:11px;font-weight:800">Email</label>
                <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-2">
                <label class="form-label" style="font-size:11px;font-weight:800">No. Telepon</label>
                <input class="form-control" name="phone_number" value="{{ old('phone_number') }}">
            </div>
            <div class="mb-2">
                <label class="form-label" style="font-size:11px;font-weight:800">Jasa kurir</label>
                <select class="form-select" name="courier_id" required>
                    @forelse($services as $service)
                        <option value="{{ $service->id_couriers }}" @selected(old('courier_id') == $service->id_couriers)>{{ $service->name }}</option>
                    @empty
                        <option value="">Belum ada jasa kurir lokal</option>
                    @endforelse
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" style="font-size:11px;font-weight:800">Kata sandi</label>
                <input class="form-control" type="password" name="password" minlength="8" required>
            </div>
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <button class="btn-brand" type="submit"><i class="fas fa-user-plus"></i> Tambah petugas</button>
        </form>
    </section>
</div>
@endsection
