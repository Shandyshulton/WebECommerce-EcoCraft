@extends('layout.app')

@section('breadcrumb')<span class="current">Customer Terdaftar</span>@endsection

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">Komunitas</div>
        <h1>Customer Terdaftar</h1>
        <p class="subtle mb-0">Daftar akun customer yang berhasil mendaftar di EcoCraft.</p>
    </div>
</div>

<div class="stat-grid mb-4" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-card"><div class="icon"><i class="fas fa-user-group"></i></div><strong>{{ $stats['total'] }}</strong><span>Total customer</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-user-plus"></i></div><strong>{{ $stats['newMonth'] }}</strong><span>Baru bulan ini</span></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-bag-shopping"></i></div><strong>{{ $stats['withOrders'] }}</strong><span>Pernah belanja</span></div>
</div>

<form class="filter-bar" method="GET">
    <div class="filter-search">
        <i class="fas fa-magnifying-glass"></i>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama / email / no. telepon…">
    </div>
    <select name="city" class="filter-select">
        <option value="">Semua kota</option>
        @foreach($cities as $c)
            <option value="{{ $c }}" @selected(request('city')===$c)>{{ $c }}</option>
        @endforeach
    </select>
    <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
    @if(request()->hasAny(['q','city']))<a class="filter-reset" href="{{ route('admin.customers') }}">Reset</a>@endif
</form>

<div class="admin-table-scroll">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Kontak</th>
                <th>Kota</th>
                <th>Gender</th>
                <th>Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $c)
                <tr>
                    <td>{{ $c->id_customers }}</td>
                    <td>
                        <div class="cell-customer">
                            <strong>{{ $c->name_customers }}</strong>
                            <small>{{ $c->email }}</small>
                        </div>
                    </td>
                    <td>{{ $c->phone_number ?: '—' }}</td>
                    <td>{{ $c->city ?: '—' }}{{ $c->province ? ', '.$c->province : '' }}</td>
                    <td>{{ $c->gender ? ucfirst($c->gender) : '—' }}</td>
                    <td>{{ optional($c->created_at)->translatedFormat('d M Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada customer terdaftar.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $customers->links() }}</div>
@endsection
