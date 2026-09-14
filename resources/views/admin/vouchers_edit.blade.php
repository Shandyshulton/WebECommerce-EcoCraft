@extends('layout.app')

@section('breadcrumb')<a href="{{ route('admin.vouchers.index') }}">Voucher</a><span class="current">Edit</span>@endsection

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">Promo &amp; reward</div>
        <h1>Edit Voucher</h1>
        <p class="subtle mb-0">{{ $voucher->code }} · {{ $voucher->title }}</p>
    </div>
    <a class="btn-brand" href="{{ route('admin.vouchers.index') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

<section class="panel" style="max-width:640px">
    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST">
        @csrf @method('PUT')
        @include('admin._voucher_form', ['voucher' => $voucher])
        <button class="btn-brand" type="submit"><i class="fas fa-floppy-disk"></i> Simpan perubahan</button>
    </form>
</section>
@endsection
