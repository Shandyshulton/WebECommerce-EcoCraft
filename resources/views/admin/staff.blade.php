@extends('layout.app')

@section('breadcrumb')<span class="current">Staff</span>@endsection

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">People & access</div>
        <h1>Staff EcoCraft</h1>
        <p class="subtle mb-0">Kelola akun admin dan peran aksesnya. Hanya Super Admin yang dapat mengubah bagian ini.</p>
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="stat-grid mb-4" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-card"><div class="icon"><i class="fas fa-users"></i></div><strong>{{ $stats['total'] }}</strong><span>Total admin</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-crown"></i></div><strong>{{ $stats['super'] }}</strong><span>Super admin</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-user-shield"></i></div><strong>{{ $stats['admin'] }}</strong><span>Admin</span></div>
</div>

<form class="filter-bar" method="GET">
    <div class="filter-search">
        <i class="fas fa-magnifying-glass"></i>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / email admin…">
    </div>
    <select name="role" class="filter-select">
        <option value="">Semua peran</option>
        <option value="super_admin" @selected(request('role')==='super_admin')>Super Admin</option>
        <option value="admin" @selected(request('role')==='admin')>Admin</option>
    </select>
    <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
    @if(request()->hasAny(['q','role']))<a class="filter-reset" href="{{ route('admin.staff') }}">Reset</a>@endif
</form>

<div class="admin-grid" style="grid-template-columns:1.4fr 1fr; align-items:start">
    {{-- Daftar admin --}}
    <div class="admin-table-scroll">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $row)
                    <tr>
                        <td>{{ $row->name }} @if($row->id_admins === $admin->id_admins)<span class="subtle">(kamu)</span>@endif</td>
                        <td>{{ $row->email }}</td>
                        <td>
                            @if($row->role === 'super_admin')
                                <span class="status approved"><i class="fas fa-crown"></i> Super Admin</span>
                            @else
                                <span class="status" style="background:var(--soft);color:var(--brand)"><i class="fas fa-user-shield"></i> Admin</span>
                            @endif
                        </td>
                        <td>
                            @if($row->id_admins === $admin->id_admins)
                                <span class="subtle">—</span>
                            @else
                                <div class="row-actions">
                                    <form action="{{ route('admin.staff.role', $row->id_admins) }}" method="POST" style="display:flex;gap:6px;align-items:center">
                                        @csrf @method('PUT')
                                        <select name="role" class="form-select form-select-sm" style="width:auto;min-height:34px;font-size:11px">
                                            <option value="admin" @selected($row->role === 'admin')>Admin</option>
                                            <option value="super_admin" @selected($row->role === 'super_admin')>Super Admin</option>
                                        </select>
                                        <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
                                    </form>
                                    <form action="{{ route('admin.staff.destroy', $row->id_admins) }}" method="POST" onsubmit="return confirm('Hapus admin ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Form tambah admin --}}
    <section class="panel">
        <div class="panel-heading"><div><div class="eyebrow">Tambah akun</div><h2>Admin baru</h2></div></div>
        <form action="{{ route('admin.staff.store') }}" method="POST">
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
                <input class="form-control" name="phone_number" value="{{ old('phone_number') }}" required>
            </div>
            <div class="mb-2">
                <label class="form-label" style="font-size:11px;font-weight:800">Peran</label>
                <select class="form-select" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" style="font-size:11px;font-weight:800">Kata sandi</label>
                <input class="form-control" type="password" name="password" minlength="8" required>
            </div>
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <button class="btn-brand" type="submit"><i class="fas fa-user-plus"></i> Tambah admin</button>
        </form>
    </section>
</div>
@endsection
