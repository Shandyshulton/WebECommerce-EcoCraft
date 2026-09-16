@extends('layouts.customer')
@section('title', 'Tambah Alamat | EcoCraft')

@section('content')
<section class="addr-form-page">
    <div class="page-wrap" style="max-width:760px">
        <a class="addr-back" href="{{ route('customer.addresses.index') }}">&larr; Kembali ke daftar alamat</a>
        <div class="eyebrow">Alamat pengiriman</div>
        <h1>Tambah alamat</h1>
        <p class="text-muted">Lengkapi data penerima dan alamat pengiriman.</p>

        @if(session('info'))<div class="alert alert-info">{{ session('info') }}</div>@endif

        @include('customer.addresses._form', [
            'address' => $address,
            'action' => route('customer.addresses.store'),
            'redirect' => $redirect ?? null,
        ])
    </div>
</section>
@endsection
