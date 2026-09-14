@extends('seller.dashboard')

@section('content')
@php($seller = Auth::guard('seller')->user())
<div class="page-heading"><div><div class="eyebrow">Ringkasan toko</div><h1>Halo, {{ $seller->name_sellers }}.</h1><p class="subtle mb-0">Mari buat katalog yang punya dampak hari ini.</p></div><a class="btn-brand" href="{{ route('products.create') }}"><i class="fas fa-plus"></i> Tambah produk</a></div>
<div class="stat-grid mb-4"><div class="stat-card"><div class="icon"><i class="fas fa-box-open"></i></div><strong>{{ $productsCount ?? 0 }}</strong><span>Total produk</span></div><div class="stat-card"><div class="icon"><i class="fas fa-circle-check"></i></div><strong>{{ $activeProducts ?? 0 }}</strong><span>Produk aktif</span></div><div class="stat-card"><div class="icon"><i class="fas fa-hourglass-half"></i></div><strong>{{ $pendingProducts ?? 0 }}</strong><span>Menunggu kurasi</span></div><div class="stat-card"><div class="icon"><i class="fas fa-receipt"></i></div><strong>{{ $ordersCount ?? 0 }}</strong><span>Total pesanan</span></div></div>

<section class="panel mb-4">
    <div class="panel-heading">
        <div><div class="eyebrow">Performa toko</div><h2>Tren Penjualan</h2></div>
        <span class="subtle">6 bulan terakhir</span>
    </div>
    @php($trend = $salesTrend ?? collect())
    @if($trend->sum('count') > 0)
        <div class="sales-chart">
            @foreach($trend as $point)
                <div class="sales-col">
                    <div class="sales-bar-wrap">
                        <span class="sales-val">{{ $point['count'] }}</span>
                        <div class="sales-bar" style="height:{{ max(4, (int) round(($point['revenue'] / ($trendMaxRevenue ?? 1)) * 100)) }}%" title="Rp {{ number_format($point['revenue'],0,',','.') }}"></div>
                    </div>
                    <div class="sales-label">{{ $point['label'] }}</div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-between subtle" style="margin-top:12px">
            <span>Total pesanan 6 bln: <strong style="color:var(--brand)">{{ $trend->sum('count') }}</strong></span>
            <span>Total nilai: <strong style="color:var(--brand)">Rp {{ number_format($trend->sum('revenue'),0,',','.') }}</strong></span>
        </div>
    @else
        <p class="subtle mb-0">Belum ada penjualan dalam 6 bulan terakhir. Grafik akan muncul setelah ada pesanan masuk.</p>
    @endif
</section>

<div class="seller-grid"><section class="panel"><div class="panel-heading"><div><div class="eyebrow">Kesehatan katalog</div><h2>Etalase tokomu</h2></div><i class="fas fa-leaf" style="color:var(--brand)"></i></div><p class="subtle">Jaga katalog tetap aktif, lengkap, dan mudah ditemukan oleh customer EcoCraft.</p><div class="progress-track"><span style="width:{{ $completeness ?? 0 }}%"></span></div><div class="d-flex justify-content-between subtle"><span>Kelengkapan etalase</span><strong style="color:var(--brand)">{{ $completeness ?? 0 }}%</strong></div></section><section class="panel"><div class="panel-heading"><div><div class="eyebrow">Langkah cepat</div><h2>Mulai dari sini</h2></div></div><div class="quick-actions" style="grid-template-columns:1fr"><a class="quick-action" href="{{ route('products.create') }}"><i class="fas fa-camera d-block"></i><strong>Tambahkan karya baru</strong><span>Lengkapi foto, harga, dan cerita produk.</span></a><a class="quick-action" href="{{ route('order.index') }}"><i class="fas fa-truck d-block"></i><strong>Periksa pesanan</strong><span>Pastikan setiap pesanan diproses tepat waktu.</span></a></div></section></div>
@endsection
