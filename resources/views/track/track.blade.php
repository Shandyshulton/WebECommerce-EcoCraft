@extends('layouts.customer')

@section('title', 'Riwayat Pembelian | EcoCraft')

@section('content')
<style>
    .shipment-wrap{margin-top:14px;border-top:1px dashed var(--line);padding-top:14px;display:grid;gap:12px}
    .shipment-item{background:#fbfaf8;border:1px solid var(--line);border-radius:10px;padding:14px}
    .shipment-head{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap}
    .shipment-who{display:flex;flex-direction:column;gap:2px}
    .shipment-store{font-size:12px;font-weight:800;color:var(--ink)}
    .shipment-courier{font-size:11px;color:var(--muted)}
    .shipment-resi{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-top:10px;padding:10px 12px;border:1px dashed var(--line);border-radius:8px;background:#fff}
    .shipment-resi-label{font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:var(--muted)}
    .shipment-resi-value{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:13px;color:var(--ink);letter-spacing:.4px}
    .shipment-copy,.shipment-track{border:1px solid var(--line);background:#fff;border-radius:6px;padding:4px 10px;font-size:11px;font-weight:700;color:var(--brand);cursor:pointer;text-decoration:none}
    .shipment-track{background:var(--brand);border-color:var(--brand);color:#fff}
    .shipment-timeline{list-style:none;margin:12px 0 0;padding:0;position:relative}
    .shipment-timeline:before{content:'';position:absolute;left:5px;top:5px;bottom:5px;width:2px;background:var(--line)}
    .shipment-timeline li{position:relative;padding:0 0 12px 22px}
    .shipment-timeline li:last-child{padding-bottom:0}
    .shipment-timeline li:before{content:'';position:absolute;left:0;top:4px;width:12px;height:12px;border-radius:50%;background:#fff;border:2px solid var(--brand)}
    .shipment-timeline li:first-child:before{background:var(--brand)}
    .st-status{display:block;font-size:11px;font-weight:800;color:var(--ink)}
    .st-desc{display:block;font-size:11px;color:var(--muted);margin-top:1px}
    .st-meta{display:block;font-size:10px;color:var(--muted);margin-top:3px}
</style>
<main class="section history-page">
    <div class="page-wrap" style="max-width:920px">
        <div class="history-head">
            <div>
                <div class="eyebrow">Riwayat pembelian</div>
                <h1>Pesanan kamu</h1>
                <p class="text-muted mb-0">Semua transaksi belanja sirkularmu di EcoCraft.</p>
            </div>
            <div class="d-flex align-items-center" style="gap:10px;flex-wrap:wrap">
                <a class="btn btn-outline-brand" href="{{ route('customer.claims.index') }}"><i class="fa fa-shield-heart"></i> Klaim Garansi</a>
                <a class="btn btn-outline-brand" href="{{ route('catalog.index') }}"><i class="fa fa-bag-shopping"></i> Belanja lagi</a>
            </div>
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
                                @if(in_array($item->id, $claimedItemIds, true))
                                    <span class="badge-status info" style="margin-top:5px">Klaim diproses</span>
                                @endif
                            </div>
                            <span class="ohc-item-sub">Rp {{ number_format($item->subtotal,0,',','.') }}</span>
                        </div>
                    @endforeach
                </div>

                @if($order->shipments->isNotEmpty())
                    <div class="shipment-wrap">
                        @foreach($order->shipments as $shipment)
                            <div class="shipment-item">
                                <div class="shipment-head">
                                    <div class="shipment-who">
                                        <span class="shipment-store">{{ $shipment->seller?->store_name ?? 'Pengrajin EcoCraft' }}</span>
                                        <span class="shipment-courier">{{ $shipment->courier?->name ?? 'Ekspedisi belum dipilih' }}</span>
                                    </div>
                                    <span class="badge-status {{ $shipment->statusClass() }}">{{ $shipment->status }}</span>
                                </div>

                                @if($shipment->tracking_number)
                                    <div class="shipment-resi">
                                        <span class="shipment-resi-label">No. resi</span>
                                        <strong class="shipment-resi-value">{{ $shipment->tracking_number }}</strong>
                                        <button type="button" class="shipment-copy" data-resi="{{ $shipment->tracking_number }}">Salin</button>
                                        @if($shipment->trackingUrl())
                                            <a class="shipment-track" href="{{ $shipment->trackingUrl() }}" target="_blank" rel="noopener">
                                                Lacak di {{ $shipment->courier?->name }}
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @if($shipment->events->isNotEmpty())
                                    <ul class="shipment-timeline">
                                        @foreach($shipment->events as $event)
                                            <li>
                                                <span class="st-status">{{ $event->status }}</span>
                                                <span class="st-desc">{{ $event->description }}</span>
                                                <span class="st-meta">
                                                    {{ $event->happened_at->translatedFormat('d M Y, H:i') }}@if($event->location) · {{ $event->location }}@endif
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="ohc-foot">
                    <span class="text-muted small">{{ $order->shipping_method ?? '—' }} · {{ $order->payment_method ?? '—' }}</span>
                    <div class="d-flex align-items-center" style="gap:12px;flex-wrap:wrap;justify-content:flex-end">
                        @if(in_array($order->status, \App\Models\WarrantyClaim::ELIGIBLE_ORDER_STATUSES, true))
                            <a class="btn btn-sm btn-outline-brand" href="{{ route('customer.claims.create', ['order' => $order->id_orders]) }}"><i class="fa fa-shield-heart"></i> Klaim garansi</a>
                        @endif
                        <div class="ohc-total">Total <strong class="price">Rp {{ number_format($order->total,0,',','.') }}</strong></div>
                    </div>
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

<script>
    document.querySelectorAll('.shipment-copy').forEach((button) => {
        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(button.dataset.resi);
                button.textContent = 'Tersalin';
            } catch (error) {
                button.textContent = button.dataset.resi;
            }
        });
    });
</script>
@endsection
