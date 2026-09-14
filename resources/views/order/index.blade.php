@extends('seller.dashboard')

@section('breadcrumb')<span class="current">Pesanan</span>@endsection

@section('content')
<div class="seller-page">
    <div class="seller-toolbar">
        <div>
            <div class="eyebrow">Operasional toko</div>
            <h1>Pesanan</h1>
            <p class="subtle mb-0">Pantau dan proses pesanan customer EcoCraft.</p>
        </div>
        <a href="{{ route('order.create') }}" class="btn-brand"><i class="fas fa-plus"></i> Buat pesanan</a>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="stat-grid mb-4">
        <div class="stat-card"><div class="icon"><i class="fas fa-receipt"></i></div><strong>{{ $stats['total'] }}</strong><span>Total pesanan</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-hourglass-half"></i></div><strong>{{ $stats['processing'] }}</strong><span>Sedang diproses</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-truck"></i></div><strong>{{ $stats['shipped'] }}</strong><span>Dikirim</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-coins"></i></div><strong style="font-size:20px">Rp {{ number_format($stats['revenue'],0,',','.') }}</strong><span>Total nilai</span></div>
    </div>

    <form class="filter-bar" method="GET">
        <div class="filter-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari no. order / nama / email customer…">
        </div>
        <select name="status" class="filter-select">
            <option value="">Semua status</option>
            @foreach(['Processing','Shipped','Completed','Cancelled'] as $st)
                <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
            @endforeach
        </select>
        <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
        @if(request()->hasAny(['q','status']))
            <a class="filter-reset" href="{{ route('order.index') }}">Reset</a>
        @endif
    </form>

    <div class="data-card">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php($item = $order->items->first())
                        @php($status = strtolower($order->status))
                        @php($statusClass = in_array($status, ['completed','delivered','selesai','shipped']) ? 'ok' : (in_array($status, ['processing','pending','diproses']) ? 'warn' : (in_array($status, ['cancelled','canceled','dibatalkan']) ? 'off' : 'info')))
                        <tr>
                            <td class="cell-price" style="color:var(--ink)">#{{ $order->order_number }}</td>
                            <td>
                                <div class="cell-customer">
                                    <strong>{{ $order->customer_name }}</strong>
                                    <small>{{ $order->customer_email }}</small>
                                </div>
                            </td>
                            <td>{{ $item?->product_name ?? 'Produk dihapus' }}</td>
                            <td>{{ $item?->quantity ?? 0 }}</td>
                            <td class="cell-price">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td><span class="badge-status {{ $statusClass }}">{{ $order->status }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('order.edit', $order) }}" class="btn-icon edit"><i class="fas fa-pen"></i> Edit</a>
                                    <form action="{{ route('order.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus order ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete"><i class="fas fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="7">Belum ada pesanan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $orders->links() }}</div>
</div>
@endsection
