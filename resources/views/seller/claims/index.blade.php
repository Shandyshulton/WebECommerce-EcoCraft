@extends('seller.dashboard')

@section('breadcrumb')<span class="current">Klaim Garansi</span>@endsection

@section('content')
<div class="seller-page">
    <div class="seller-toolbar">
        <div>
            <div class="eyebrow">Layanan purna jual</div>
            <h1>Klaim Garansi</h1>
            <p class="subtle mb-0">Tangani klaim perbaikan dari customer untuk produk tokomu.</p>
        </div>
        @if($stats['pending'] > 0)
            <span class="badge-status warn">{{ $stats['pending'] }} belum ditanggapi</span>
        @endif
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="stat-grid mb-4" style="grid-template-columns:repeat(2,1fr)">
        <div class="stat-card"><div class="icon"><i class="fas fa-shield-heart"></i></div><strong>{{ $stats['total'] }}</strong><span>Total klaim</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-bell"></i></div><strong>{{ $stats['pending'] }}</strong><span>Belum ditanggapi</span></div>
    </div>

    <form class="filter-bar" method="GET">
        <div class="filter-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari customer / produk…">
        </div>
        <select name="status" class="filter-select">
            <option value="">Semua status</option>
            @foreach(\App\Models\WarrantyClaim::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
        @if(request()->hasAny(['q', 'status']))
            <a class="filter-reset" href="{{ route('seller.claims.index') }}">Reset</a>
        @endif
    </form>

    <div class="data-card">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Customer</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Diajukan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                        <tr>
                            <td><span class="cell-title">{{ optional($claim->product)->name ?? optional($claim->item)->product_name ?? 'Produk EcoCraft' }}</span></td>
                            <td>{{ optional($claim->customer)->name_customers ?? 'Customer' }}</td>
                            <td>{{ $claim->categoryLabel() }}</td>
                            <td><span class="badge-status {{ $claim->statusTone() }}">{{ $claim->statusLabel() }}</span></td>
                            <td>{{ $claim->created_at->diffForHumans() }}</td>
                            <td><a class="btn-icon edit" href="{{ route('seller.claims.show', $claim->id_claims) }}"><i class="fas fa-clipboard-check"></i> Buka</a></td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="6">Belum ada klaim garansi dari customer.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $claims->links() }}</div>
</div>
@endsection
