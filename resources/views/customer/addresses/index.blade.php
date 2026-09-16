@extends('layouts.customer')
@section('title', 'Alamat Pengiriman | EcoCraft')

@push('styles')
<style>
    .addr-page { padding:34px 0 64px; }
    .addr-head { display:flex; align-items:end; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:22px; }
    .addr-head h1 { font:600 clamp(30px,4vw,44px)/1.05 'EB Garamond',serif; margin:6px 0 6px; }
    .addr-list { display:grid; gap:14px; }
    .addr-card { padding:20px; border:1px solid var(--line); border-radius:14px; background:#fff; }
    .addr-card.is-default { border-color:#cfe1d3; box-shadow:0 12px 28px -20px rgba(30,75,56,.4); }
    .addr-card-top { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:10px; }
    .addr-label { display:inline-flex; align-items:center; gap:8px; font-size:13px; font-weight:800; }
    .addr-label i { color:var(--brand); }
    .addr-name { font-size:14px; font-weight:700; }
    .addr-phone { margin:6px 0 0; color:var(--muted); font-size:12px; }
    .addr-text { margin:8px 0 0; color:var(--muted); font-size:12.5px; line-height:1.7; }
    .addr-actions { display:flex; flex-wrap:wrap; gap:8px; margin-top:14px; padding-top:14px; border-top:1px solid var(--line); }
    .addr-actions form { margin:0; }
    @media (max-width:640px) { .addr-actions .btn { flex:1 1 40%; } }
</style>
@endpush

@section('content')
<section class="addr-page">
    <div class="page-wrap" style="max-width:820px">
        <div class="addr-head">
            <div>
                <div class="eyebrow">Pengiriman</div>
                <h1>Alamat Pengiriman</h1>
                <p class="text-muted mb-0">Simpan beberapa alamat, lalu pilih salah satunya saat checkout.</p>
            </div>
            <a class="btn btn-brand" href="{{ route('customer.addresses.create') }}"><i class="fa fa-plus"></i> Tambah alamat</a>
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="addr-list">
            @forelse($addresses as $address)
                <article class="addr-card {{ $address->is_default ? 'is-default' : '' }}">
                    <div class="addr-card-top">
                        <span class="addr-label"><i class="fa fa-location-dot" aria-hidden="true"></i> {{ $address->labelText() }}</span>
                        @if($address->is_default)<span class="badge-status ok">Utama</span>@endif
                    </div>
                    <div class="addr-name">{{ $address->recipient_name }}</div>
                    <p class="addr-phone"><i class="fa fa-phone" aria-hidden="true"></i> {{ $address->phone }}</p>
                    <p class="addr-text">{{ $address->address }}<br>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>

                    <div class="addr-actions">
                        <a class="btn btn-sm btn-outline-brand" href="{{ route('customer.addresses.edit', $address->id_addresses) }}"><i class="fa fa-pen"></i> Edit</a>
                        @unless($address->is_default)
                            <form method="POST" action="{{ route('customer.addresses.default', $address->id_addresses) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-brand" type="submit"><i class="fa fa-star"></i> Jadikan utama</button>
                            </form>
                        @endunless
                        <form method="POST" action="{{ route('customer.addresses.destroy', $address->id_addresses) }}" onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-brand" type="submit" style="border-color:#a53c27;color:#a53c27"><i class="fa fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="history-empty">
                    <i class="fa fa-location-dot" aria-hidden="true"></i>
                    <h2>Belum ada alamat</h2>
                    <p class="text-muted">Tambahkan alamat pengiriman supaya checkout berikutnya lebih cepat.</p>
                    <a class="btn btn-brand" href="{{ route('customer.addresses.create') }}">Tambah alamat</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
