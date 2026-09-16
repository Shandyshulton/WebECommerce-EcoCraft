@extends('layouts.customer')
@section('title', 'Edit Alamat | EcoCraft')

@section('content')
<section class="addr-form-page">
    <div class="page-wrap" style="max-width:760px">
        <a class="addr-back" href="{{ route('customer.addresses.index') }}">&larr; Kembali ke daftar alamat</a>
        <div class="eyebrow">Alamat pengiriman</div>
        <h1>Edit alamat</h1>
        <p class="text-muted">Perbarui data penerima atau detail alamat pengiriman.</p>

        @include('customer.addresses._form', [
            'address' => $address,
            'action' => route('customer.addresses.update', $address->id_addresses),
        ])
    </div>
</section>
@endsection
