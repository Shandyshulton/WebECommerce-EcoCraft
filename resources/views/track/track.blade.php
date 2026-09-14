@extends('layouts.customer')

@section('title', 'Riwayat Pembelian | EcoCraft')

@section('content')
<main class="section history-page">
    <div class="page-wrap" style="max-width:920px">
        <div class="history-head">
            <div>
                <div class="eyebrow">Riwayat pembelian</div>
                <h1>Pesanan kamu</h1>
                <p class="text-muted mb-0">Semua transaksi belanja sirkularmu di EcoCraft.</p>
            </div>
            <a class="btn btn-outline-brand" href="{{ route('catalog.index') }}"><i class="fa fa-bag-shopping"></i> Belanja lagi</a>
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="history-stats">
            <div class="history-stat"><span class="hs-label">Total pesanan</span><strong>{{ $stats['total'] }}</strong></div>
            <div class="history-stat"><span class="hs-label">Sedang berjalan</span><strong>{{ $stats['active'] }}</strong></div>
            <div class="history-stat"><span class="hs-label">Total belanja</span><strong>Rp {{ number_format($stats['spent'],0,',','.') }}</strong></div>
        </div>

        @forelse($orders as $order)
            @php($status = strtolower($order->status))
            @php($statusClass = in_array($status, ['completed','delivered','selesai']) ? 'ok' : (in_array($status, ['shipped','dikirim']) ? 'info' : (in_array($status, ['cancelled','canceled','dibatalkan']) ? 'off' : 'warn')))
            <article class="order-history-card">
                <div class="ohc-top">
                    <div>
                        <strong class="ohc-number">#{{ $order->order_number }}</strong>
                        <span class="ohc-date">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    <span class="badge-status {{ $statusClass }}">{{ $order->status }}</span>
                </div>

                <div class="ohc-items">
                    @foreach($order->items as $item)
                        <div class="ohc-item">
                            <img src="{{ optional($item->product)->image_url ? asset('storage/'.$item->product->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $item->product_name }}">
                            <div class="ohc-item-body">
                                <span class="ohc-item-name">{{ $item->product_name }}</span>
                                <span class="ohc-item-meta">{{ $item->quantity }} × Rp {{ number_format($item->unit_price,0,',','.') }}</span>
                            </div>
                            <span class="ohc-item-sub">Rp {{ number_format($item->subtotal,0,',','.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="ohc-foot">
                    <span class="text-muted small">{{ $order->shipping_method ?? '—' }} · {{ $order->payment_method ?? '—' }}</span>
                    <div class="ohc-total">Total <strong class="price">Rp {{ number_format($order->total,0,',','.') }}</strong></div>
                </div>
            </article>
        @empty
            <div class="history-empty">
                <i class="fa fa-box-open"></i>
                <h2>Belum ada pesanan</h2>
                <p class="text-muted">Yuk mulai belanja karya pengrajin lokal.</p>
                <a class="btn btn-brand" href="{{ route('catalog.index') }}">Jelajahi katalog</a>
            </div>
        @endforelse
    </div>
</main>
@endsection
