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
    .shipment-hint{margin:8px 0 0;font-size:10.5px;line-height:1.6;color:var(--muted)}
    .shipment-timeline{list-style:none;margin:12px 0 0;padding:0;position:relative}
    .shipment-timeline:before{content:'';position:absolute;left:5px;top:5px;bottom:5px;width:2px;background:var(--line)}
    .shipment-timeline li{position:relative;padding:0 0 12px 22px}
    .shipment-timeline li:last-child{padding-bottom:0}
    .shipment-timeline li:before{content:'';position:absolute;left:0;top:4px;width:12px;height:12px;border-radius:50%;background:#fff;border:2px solid var(--brand)}
    .shipment-timeline li:first-child:before{background:var(--brand)}
    .st-status{display:block;font-size:11px;font-weight:800;color:var(--ink)}
    .st-desc{display:block;font-size:11px;color:var(--muted);margin-top:1px}
    .st-meta{display:block;font-size:10px;color:var(--muted);margin-top:3px}
    .shipment-proof{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:10px;padding:10px 12px;border:1px solid var(--line);border-radius:8px;background:#f4f8f5}
    .shipment-proof-label{display:block;font-size:9px;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)}
    .shipment-proof strong{display:block;font-size:12px;color:var(--ink);margin-top:2px}
    .shipment-proof-time{display:block;font-size:10px;color:var(--muted);margin-top:2px}
    .shipment-proof img{width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--line)}
    .shipment-confirm{margin-top:10px}
    .shipment-confirm summary{cursor:pointer;font-size:11px;font-weight:700;color:var(--brand);padding:6px 0}
    .shipment-confirm form{display:grid;gap:8px;margin-top:8px;padding:12px;border:1px solid var(--line);border-radius:8px;background:#fff}
    .shipment-confirm label{font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted)}
    .shipment-confirm input[type=text]{border:1px solid var(--line);border-radius:6px;padding:8px 10px;font-size:12px;width:100%}
    .shipment-confirm input[type=file]{font-size:11px}
    .shipment-confirm button{justify-self:start;border:0;border-radius:6px;background:var(--brand);color:#fff;font-size:11px;font-weight:700;padding:8px 14px;cursor:pointer}
    /* `d-flex` berasal dari Bootstrap 5 dan tidak ada di halaman ini, jadi
       pengaturan flex dibuat eksplisit di sini. */
    .ohc-badges{display:flex;align-items:center;gap:6px;flex-wrap:wrap;justify-content:flex-end}
    .ohc-actions{display:flex;align-items:center;gap:12px;flex-wrap:wrap;justify-content:flex-end}
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
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

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
                    <div class="ohc-badges">
                        <span class="badge-status {{ $order->paymentClass() }}">{{ $order->paymentLabel() }}</span>
                        <span class="badge-status {{ $statusClass }}">{{ $order->status }}</span>
                    </div>
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
                                            <a class="shipment-track" href="{{ $shipment->trackingUrl() }}" target="_blank" rel="noopener" data-resi="{{ $shipment->tracking_number }}">
                                                Lacak di {{ $shipment->courier?->name }}
                                            </a>
                                        @endif
                                    </div>
                                    <p class="shipment-hint">Situs ekspedisi tidak menerima nomor resi lewat tautan, jadi resinya otomatis tersalin — tinggal tempel di kolom pencarian.</p>
                                @endif

                                @if($shipment->isDelivered())
                                    <div class="shipment-proof">
                                        <div>
                                            <span class="shipment-proof-label">Diterima oleh</span>
                                            <strong>{{ $shipment->receiver_name ?: 'Penerima' }}</strong>
                                            @if($shipment->delivered_at)
                                                <span class="shipment-proof-time">{{ $shipment->delivered_at->translatedFormat('d M Y, H:i') }}</span>
                                            @endif
                                        </div>
                                        @if($shipment->proof_photo)
                                            <a href="{{ asset('storage/'.$shipment->proof_photo) }}" data-lightbox-trigger data-alt="Bukti penerimaan paket">
                                                <img src="{{ asset('storage/'.$shipment->proof_photo) }}" alt="Bukti penerimaan paket">
                                            </a>
                                        @endif
                                    </div>
                                @elseif(in_array($shipment->status, ['Shipped', 'In Transit'], true))
                                    <details class="shipment-confirm">
                                        <summary>Paket sudah saya terima</summary>
                                        <form action="{{ route('customer.shipments.confirm', $shipment) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div>
                                                <label for="receiver-{{ $shipment->id_shipments }}">Nama penerima</label>
                                                <input id="receiver-{{ $shipment->id_shipments }}" type="text" name="receiver_name" value="{{ old('receiver_name', Auth::guard('customer')->user()->name_customers) }}" required>
                                            </div>
                                            <div>
                                                <label for="photo-{{ $shipment->id_shipments }}">Foto bukti (opsional)</label>
                                                <input id="photo-{{ $shipment->id_shipments }}" type="file" name="proof_photo" accept="image/*">
                                            </div>
                                            <button type="submit">Konfirmasi diterima</button>
                                        </form>
                                    </details>
                                @endif

                                @if($shipment->events->isNotEmpty())
                                    <ul class="shipment-timeline">
                                        @foreach($shipment->events->reverse() as $event)
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
                    <div class="ohc-actions">
                        @if($order->requiresPayment() && ! $order->isPaid())
                            <a class="btn btn-sm btn-brand" href="{{ route('payment.show', $order) }}"><i class="fa fa-credit-card"></i> Bayar sekarang</a>
                        @endif
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

    // Situs ekspedisi tidak bisa menerima nomor resi lewat URL, jadi resinya
    // disalin lebih dulu agar pembeli tinggal menempelkannya.
    document.querySelectorAll('.shipment-track').forEach((link) => {
        link.addEventListener('click', () => {
            if (link.dataset.resi && navigator.clipboard) {
                navigator.clipboard.writeText(link.dataset.resi).catch(() => {});
            }
        });
    });
</script>

@include('partials.image-lightbox')
@endsection
