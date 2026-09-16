@extends('layouts.customer')

@section('title', 'Profile | EcoCraft')

@section('content')
<main class="section">
    <div class="page-wrap" style="max-width:720px">
        <div class="eyebrow">Account</div>
        <h1 class="mb-4">Profile</h1>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form class="form-panel" method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @php($customer = Auth::guard('customer')->user())
            <div class="mb-3"><label class="form-label">Profile photo</label><input class="form-control" type="file" name="profile_image" accept="image/*"></div>
            <div class="mb-3"><label class="form-label">Nama</label><input class="form-control" name="name_customers" value="{{ old('name_customers', $customer->name_customers) }}" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="{{ old('email', $customer->email) }}" required></div>
            <button class="btn btn-brand">Simpan perubahan</button>
        </form>

        <div class="form-panel mt-3 d-flex justify-content-between align-items-center" style="gap:16px;flex-wrap:wrap">
            <div>
                <strong style="display:block">Alamat pengiriman</strong>
                <span class="text-muted" style="font-size:12px">Simpan beberapa alamat untuk checkout yang lebih cepat.</span>
            </div>
            <a class="btn btn-outline-brand" href="{{ route('customer.addresses.index') }}"><i class="fa fa-location-dot"></i> Kelola alamat</a>
        </div>

        <div class="form-panel mt-3 d-flex justify-content-between align-items-center" style="gap:16px;flex-wrap:wrap">
            <div>
                <strong style="display:block">Keluar dari akun</strong>
                <span class="text-muted" style="font-size:12px">Akhiri sesi di perangkat ini.</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-brand" style="border-color:#a53c27;color:#a53c27">
                    <i class="fa fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>
</main>
@endsection

