@extends('layouts.courier')

@section('title', 'Tugas #'.$shipment->order?->order_number.' | Kurir EcoCraft')

@section('content')
<div class="c-card">
    <div class="c-task-top">
        <span class="c-task-no">#{{ $shipment->order?->order_number }}</span>
        <span class="c-badge {{ $shipment->statusClass() }}">{{ $shipment->status }}</span>
    </div>
    <p class="hint mb-0">{{ $shipment->seller?->store_name }} &middot; {{ $shipment->courier?->name }}</p>
</div>

<div class="c-card">
    <h2>Alamat pengantaran</h2>
    <p class="hint">Hubungi penerima bila kesulitan menemukan lokasi.</p>
    <ul class="c-meta">
        <li><span>Penerima</span><strong>{{ $shipment->order?->customer_name }}</strong></li>
        <li><span>Telepon</span><strong>{{ $shipment->order?->customer_phone }}</strong></li>
        <li><span>Alamat</span><strong>{{ $shipment->order?->shipping_address }}</strong></li>
        <li><span>Kota</span><strong>{{ $shipment->order?->shipping_city }}, {{ $shipment->order?->shipping_province }}</strong></li>
        <li><span>Kode pos</span><strong>{{ $shipment->order?->shipping_postal_code }}</strong></li>
    </ul>
    @if($shipment->order?->customer_phone)
        <a class="c-btn ghost wide" href="tel:{{ $shipment->order->customer_phone }}"><i class="fas fa-phone"></i> Telepon penerima</a>
    @endif
</div>

<div class="c-card">
    <h2>Barang dalam paket</h2>
    <p class="hint">Total {{ $items->sum('quantity') }} item.</p>
    <ul class="c-items">
        @forelse($items as $item)
            <li><span>{{ $item->product_name }} <span class="text-muted">&times; {{ $item->quantity }}</span></span></li>
        @empty
            <li><span class="text-muted">Tidak ada barang.</span></li>
        @endforelse
    </ul>
</div>

@if($shipment->isDelivered())
    <div class="c-card">
        <h2>Sudah terkirim</h2>
        <p class="hint">Bukti tercatat dan bisa dilihat pembeli maupun pengrajin.</p>
        <div class="c-proof">
            <div>
                <span class="c-tl-meta">Diterima oleh</span>
                <strong>{{ $shipment->receiver_name ?: 'Penerima' }}</strong>
                <span class="c-tl-meta">{{ $shipment->delivered_at?->translatedFormat('d M Y, H:i') }}</span>
            </div>
            @if($shipment->proof_photo)
                <a href="{{ asset('storage/'.$shipment->proof_photo) }}" data-lightbox-trigger data-alt="Bukti penerimaan paket">
                    <img src="{{ asset('storage/'.$shipment->proof_photo) }}" alt="Bukti penerimaan paket">
                </a>
            @endif
        </div>
    </div>
@else
    <div class="c-card">
        <h2>Konfirmasi paket sampai</h2>
        <p class="hint">Ini satu-satunya langkah yang perlu kamu lakukan. Nama penerima dan foto bukti wajib diisi.</p>
        <form action="{{ route('courier.tasks.update', $shipment) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="c-field">
                <label class="c-label" for="receiver_name">Nama penerima</label>
                <input id="receiver_name" name="receiver_name" class="c-input" value="{{ old('receiver_name') }}" placeholder="Nama orang yang menerima paket" required>
            </div>
            <div class="c-field">
                <label class="c-label" for="proof_photo">Foto bukti</label>
                <input id="proof_photo" type="file" name="proof_photo" class="c-input" accept="image/*" capture="environment" required>
                <span class="c-tl-meta">Foto paket saat diserahkan ke penerima.</span>
            </div>
            <button class="c-btn wide" type="submit"><i class="fas fa-check"></i> Paket sudah sampai</button>
        </form>
    </div>
@endif

@if(!$shipment->isDelivered())
    {{-- Hanya kurir yang mencatat posisi paket: pengrajin tidak tahu lagi
         keberadaannya setelah paket diserahkan. --}}
    <div class="c-card">
        <h2>Tambah titik perjalanan</h2>
        <p class="hint">Opsional. Catat posisi paket di tengah perjalanan, mis. berhenti di suatu tempat.</p>
        <form action="{{ route('courier.tasks.events.store', $shipment) }}" method="POST">
            @csrf
            <div class="c-field">
                <label class="c-label" for="checkpoint_location">Lokasi</label>
                <input id="checkpoint_location" name="location" class="c-input" placeholder="mis. Jl. Malioboro">
            </div>
            <div class="c-field">
                <label class="c-label" for="checkpoint_description">Keterangan</label>
                <input id="checkpoint_description" name="description" class="c-input" required placeholder="mis. Paket sedang diantar">
            </div>
            <button class="c-btn wide" type="submit"><i class="fas fa-plus"></i> Tambah titik perjalanan</button>
        </form>
    </div>
@endif

<div class="c-card">
    <h2>Riwayat paket</h2>
    <p class="hint">Setiap perubahan status tercatat di sini.</p>
    <ul class="c-timeline">
        @forelse($shipment->events->reverse() as $event)
            <li>
                <span class="c-tl-status">{{ $event->status }}</span>
                <span class="c-tl-desc">{{ $event->description }}</span>
                <span class="c-tl-meta">{{ $event->happened_at->translatedFormat('d M Y, H:i') }} &middot; {{ $event->sourceLabel() }}</span>
            </li>
        @empty
            <li><span class="c-tl-desc">Belum ada riwayat.</span></li>
        @endforelse
    </ul>
</div>

<a class="c-btn ghost wide" href="{{ route('courier.tasks.index') }}">&larr; Kembali ke daftar tugas</a>

@include('partials.image-lightbox')
@endsection
