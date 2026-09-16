@extends('seller.dashboard')

@section('breadcrumb')
    <a href="{{ route('seller.shipments.index') }}">Pengiriman</a>
    <span class="current">#{{ $shipment->order?->order_number }}</span>
@endsection

@section('content')
<style>
    .ship-grid{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,1fr);gap:20px;align-items:start}
    .ship-card{padding:22px;background:#fff;border:1px solid var(--line);border-radius:14px}
    .ship-card h2{margin:0 0 4px;font-size:15px;font-weight:800;color:var(--ink)}
    .ship-card .hint{margin:0 0 18px;font-size:11px;color:var(--muted)}
    .ship-card .form-label{color:var(--ink);font-size:11px;font-weight:800;margin-bottom:7px}
    .ship-card .form-control,.ship-card .form-select{min-height:44px;border:1px solid var(--line);border-radius:8px;background:#f8faf8;font-size:12px}
    .ship-card .form-control:focus,.ship-card .form-select:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}
    .ship-card textarea{min-height:80px}
    .ship-card .btn{border-radius:7px;font-size:11px;font-weight:800;padding:9px 14px}
    .ship-card .btn-primary{background:var(--brand);border-color:var(--brand)}
    .ship-card .btn-secondary{background:#f1f4f1;border-color:var(--line);color:var(--ink)}
    .resi-box{display:flex;gap:8px;align-items:center;flex-wrap:wrap;padding:14px;border:1px dashed var(--line);border-radius:10px;background:#f8faf8;margin-bottom:18px}
    .resi-box .resi-value{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:14px;font-weight:700;color:var(--ink);letter-spacing:.4px}
    .ship-meta{list-style:none;margin:0 0 16px;padding:0;font-size:12px;color:var(--muted)}
    .ship-meta li{display:flex;justify-content:space-between;gap:12px;padding:7px 0;border-bottom:1px solid var(--line)}
    .ship-meta li strong{color:var(--ink);font-weight:700;text-align:right}
    .timeline{list-style:none;margin:0;padding:0;position:relative}
    .timeline:before{content:'';position:absolute;left:7px;top:6px;bottom:6px;width:2px;background:var(--line)}
    .timeline li{position:relative;padding:0 0 18px 28px}
    .timeline li:last-child{padding-bottom:0}
    .timeline li:before{content:'';position:absolute;left:0;top:4px;width:16px;height:16px;border-radius:50%;background:#fff;border:2px solid var(--brand)}
    .timeline li:first-child:before{background:var(--brand)}
    .tl-title{display:block;font-size:12px;font-weight:800;color:var(--ink)}
    .tl-desc{display:block;font-size:12px;color:var(--muted);margin-top:2px}
    .tl-meta{display:block;font-size:10px;color:var(--muted);margin-top:4px;text-transform:uppercase;letter-spacing:.5px}
    .ship-items{list-style:none;margin:0;padding:0;font-size:12px}
    .ship-items li{display:flex;justify-content:space-between;gap:12px;padding:9px 0;border-bottom:1px solid var(--line)}
    .ship-items li:last-child{border-bottom:0}
    @media(max-width:900px){.ship-grid{grid-template-columns:1fr}}
</style>

<div class="seller-page">
    <div class="seller-toolbar">
        <div>
            <div class="eyebrow">Pengiriman paket</div>
            <h1>Pesanan #{{ $shipment->order?->order_number }}</h1>
            <p class="subtle mb-0">Kelola ekspedisi, nomor resi, dan riwayat perjalanan paket.</p>
        </div>
        <span class="badge-status {{ $shipment->statusClass() }}" style="align-self:center">{{ $shipment->status }}</span>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="ship-grid">
        <div class="ship-card">
            <h2>Data pengiriman</h2>
            <p class="hint">Nomor resi wajib diisi sebelum paket ditandai dikirim.</p>

            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            @if($shipment->tracking_number)
                <div class="resi-box">
                    <span class="resi-value" id="resiValue">{{ $shipment->tracking_number }}</span>
                    <button type="button" class="btn btn-secondary" id="copyResi"><i class="fas fa-copy"></i> Salin resi</button>
                    @if($trackingUrl)
                        <a href="{{ $trackingUrl }}" target="_blank" rel="noopener" class="btn btn-secondary">
                            <i class="fas fa-arrow-up-right-from-square"></i> Lacak di {{ $shipment->courier?->name }}
                        </a>
                    @endif
                </div>
            @endif

            <form action="{{ route('seller.shipments.update', $shipment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Ekspedisi</label>
                        <select name="courier_id" class="form-select">
                            <option value="">— Pilih ekspedisi —</option>
                            @foreach($couriers as $courier)
                                <option value="{{ $courier->id_couriers }}" @selected(old('courier_id', $shipment->courier_id) == $courier->id_couriers)>{{ $courier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor resi</label>
                        <input name="tracking_number" class="form-control" value="{{ old('tracking_number', $shipment->tracking_number) }}" placeholder="mis. JNE1234567890">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status paket</label>
                        <select name="status" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', $shipment->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi (opsional)</label>
                        <input name="location" class="form-control" value="{{ old('location') }}" placeholder="mis. Sortir Bandung">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan untuk pembeli (opsional)</label>
                        <textarea name="note" class="form-control">{{ old('note', $shipment->note) }}</textarea>
                    </div>
                </div>

                <button class="btn btn-primary mt-4">Simpan</button>
                <a href="{{ route('seller.shipments.index') }}" class="btn btn-secondary mt-4">Kembali</a>
            </form>
        </div>

        <div>
            <div class="ship-card mb-3">
                <h2>Rincian pesanan</h2>
                <p class="hint">Paket ini hanya memuat barang dari toko kamu.</p>
                <ul class="ship-meta">
                    <li><span>Penerima</span><strong>{{ $shipment->order?->customer_name ?? '—' }}</strong></li>
                    <li><span>Telepon</span><strong>{{ $shipment->order?->customer_phone ?? '—' }}</strong></li>
                    <li><span>Alamat</span><strong>{{ $shipment->order?->shipping_address ?? '—' }}</strong></li>
                    <li><span>Kota</span><strong>{{ $shipment->order?->shipping_city ?? '—' }}, {{ $shipment->order?->shipping_province ?? '' }}</strong></li>
                    <li><span>Metode kirim</span><strong>{{ $shipment->order?->shipping_method ?? '—' }}</strong></li>
                    <li><span>Pembayaran</span><strong>{{ $shipment->order?->payment_method ?? '—' }}</strong></li>
                </ul>
                @if($shipment->note)
                    <div class="alert alert-info mb-0" style="font-size:12px">{{ $shipment->note }}</div>
                @endif
            </div>

            <div class="ship-card mb-3">
                <h2>Barang dalam paket</h2>
                <p class="hint">Total {{ $items->sum('quantity') }} item.</p>
                <ul class="ship-items">
                    @forelse($items as $item)
                        <li>
                            <span>{{ $item->product_name }} <span class="text-muted">× {{ $item->quantity }}</span></span>
                            <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                        </li>
                    @empty
                        <li><span class="text-muted">Tidak ada barang.</span></li>
                    @endforelse
                </ul>
            </div>

            <div class="ship-card mb-3">
                <h2>Riwayat perjalanan</h2>
                <p class="hint">Tercatat otomatis saat status berubah.</p>
                <ul class="timeline">
                    @forelse($shipment->events as $event)
                        <li>
                            <span class="tl-title">{{ $event->status }}</span>
                            <span class="tl-desc">{{ $event->description }}</span>
                            <span class="tl-meta">
                                {{ $event->happened_at->translatedFormat('d M Y, H:i') }}
                                @if($event->location) · {{ $event->location }} @endif
                                · {{ $event->sourceLabel() }}
                            </span>
                        </li>
                    @empty
                        <li><span class="tl-desc">Belum ada riwayat perjalanan.</span></li>
                    @endforelse
                </ul>
            </div>

            <div class="ship-card">
                <h2>Tambah titik perjalanan</h2>
                <p class="hint">Catat posisi paket, mis. tiba di kota transit.</p>
                <form action="{{ route('seller.shipments.events.store', $shipment) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected($shipment->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi</label>
                            <input name="location" class="form-control" placeholder="mis. Hub Jakarta">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <input name="description" class="form-control" required placeholder="mis. Paket tiba di hub transit">
                        </div>
                    </div>
                    <button class="btn btn-primary mt-4"><i class="fas fa-plus"></i> Tambah riwayat</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const copyButton = document.getElementById('copyResi');
    if (copyButton) {
        copyButton.addEventListener('click', async () => {
            const value = document.getElementById('resiValue').textContent.trim();
            try {
                await navigator.clipboard.writeText(value);
                copyButton.innerHTML = '<i class="fas fa-check"></i> Tersalin';
            } catch (error) {
                copyButton.innerHTML = value;
            }
        });
    }
</script>
@endsection
