@extends('seller.dashboard')

@section('breadcrumb')<span class="current">Pengiriman</span>@endsection

@section('content')
<div class="seller-page">
    <div class="seller-toolbar">
        <div>
            <div class="eyebrow">Operasional toko</div>
            <h1>Pengiriman</h1>
            <p class="subtle mb-0">Kelola resi dan perjalanan paket untuk tiap pengrajin di pesanan EcoCraft.</p>
        </div>
        <a href="{{ route('order.index') }}" class="btn-brand"><i class="fas fa-receipt"></i> Lihat pesanan</a>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="stat-grid mb-4">
        <div class="stat-card"><div class="icon"><i class="fas fa-boxes-stacked"></i></div><strong>{{ $stats['total'] }}</strong><span>Total paket</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-hourglass-half"></i></div><strong>{{ $stats['pending'] }}</strong><span>Perlu dikirim</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-truck"></i></div><strong>{{ $stats['shipped'] }}</strong><span>Sedang di jalan</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-circle-check"></i></div><strong>{{ $stats['delivered'] }}</strong><span>Sudah diterima</span></div>
    </div>

    <form class="filter-bar" method="GET">
        <div class="filter-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari no. resi / no. order / nama customer…">
        </div>
        <select name="status" class="filter-select">
            <option value="">Semua status</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
        @if(request()->hasAny(['q','status']))
            <a class="filter-reset" href="{{ route('seller.shipments.index') }}">Reset</a>
        @endif
    </form>

    <div class="data-card">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Ekspedisi</th>
                        <th>No. Resi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $shipment)
                        <tr>
                            <td class="cell-price" style="color:var(--ink)">#{{ $shipment->order?->order_number ?? '—' }}</td>
                            <td>
                                <div class="cell-customer">
                                    <strong>{{ $shipment->order?->customer_name ?? '—' }}</strong>
                                    <small>{{ $shipment->order?->shipping_city ?? '' }}</small>
                                </div>
                            </td>
                            <td>{{ $shipment->courier?->name ?? 'Belum dipilih' }}</td>
                            <td class="cell-price" style="color:var(--ink)">{{ $shipment->tracking_number ?: '—' }}</td>
                            <td><span class="badge-status {{ $shipment->statusClass() }}">{{ $shipment->status }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('seller.shipments.show', $shipment) }}" class="btn-icon edit"><i class="fas fa-truck-fast"></i> Kelola</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="6">Belum ada paket yang perlu dikirim.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $shipments->links() }}</div>
</div>
@endsection
