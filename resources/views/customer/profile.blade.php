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
            <div class="mb-3"><label class="form-label">Nomor WhatsApp</label>
                @php($phoneLocal = preg_replace('/^(\+?62|0)/', '', old('phone_number', $customer->phone_number ?? '')))
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input class="form-control" id="phone_display" type="tel" value="{{ $phoneLocal }}" inputmode="numeric" maxlength="13" placeholder="81234567890" required>
                </div>
                <input type="hidden" id="phone_number" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}">
                <small class="text-muted">Tanpa angka 0 di depan. Contoh: 81234567890</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Provinsi</label>
                <select class="form-select" id="province" name="province" required>
                    <option value="">Pilih provinsi</option>
                    @foreach(array_keys($regions ?? []) as $prov)
                        <option value="{{ $prov }}" @selected(old('province', $customer->province) === $prov)>{{ $prov }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kota / Kabupaten</label>
                    <select class="form-select" id="city" name="city" required>
                        <option value="">Pilih provinsi dulu</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode pos</label>
                    <input class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" placeholder="40123" required>
                </div>
            </div>
            <div class="mb-3"><label class="form-label">Alamat lengkap</label><input class="form-control" name="address" value="{{ old('address', $customer->address) }}" placeholder="Jalan, nomor rumah, kecamatan" required></div>
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

@push('scripts')
<script>
(function () {
    var REGIONS = @json($regions ?? [], JSON_UNESCAPED_UNICODE);
    var POSTAL = @json($postalCodes ?? [], JSON_UNESCAPED_UNICODE);
    var currentCity = @json(old('city', $customer->city ?? ''));

    var provinceSelect = document.getElementById('province');
    var citySelect = document.getElementById('city');
    var postalInput = document.getElementById('postal_code');

    function fillPostal() {
        if (!postalInput) return;
        var code = (POSTAL[provinceSelect.value] || {})[citySelect.value];
        if (code) postalInput.value = code;
    }

    function populateCities(selectedCity) {
        var province = provinceSelect.value;
        var cities = REGIONS[province] || [];
        citySelect.innerHTML = '';

        var placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = province ? 'Pilih kota / kabupaten' : 'Pilih provinsi dulu';
        citySelect.appendChild(placeholder);

        cities.forEach(function (city) {
            var opt = document.createElement('option');
            opt.value = city;
            opt.textContent = city;
            if (selectedCity && selectedCity === city) opt.selected = true;
            citySelect.appendChild(opt);
        });
    }

    if (provinceSelect && citySelect) {
        provinceSelect.addEventListener('change', function () { populateCities(null); });
        citySelect.addEventListener('change', fillPostal);
        if (provinceSelect.value) populateCities(currentCity);
    }

    function digitsOnly(el) {
        if (!el) return;
        el.addEventListener('input', function () {
            el.value = el.value.replace(/[^0-9]/g, '');
        });
        el.addEventListener('keypress', function (e) {
            if (e.key.length === 1 && !/[0-9]/.test(e.key)) e.preventDefault();
        });
    }

    // Field telepon dengan prefix +62: kirim sebagai 0 + angka.
    var phoneDisplay = document.getElementById('phone_display');
    var phoneHidden = document.getElementById('phone_number');
    function syncPhone() {
        if (!phoneDisplay || !phoneHidden) return;
        var local = phoneDisplay.value.replace(/[^0-9]/g, '').replace(/^0+/, '');
        phoneDisplay.value = local;
        phoneHidden.value = local ? '0' + local : '';
    }
    if (phoneDisplay) {
        digitsOnly(phoneDisplay);
        phoneDisplay.addEventListener('input', syncPhone);
        var phoneForm = phoneDisplay.closest('form');
        if (phoneForm) phoneForm.addEventListener('submit', syncPhone);
        syncPhone();
    }
    digitsOnly(document.getElementById('postal_code'));
})();
</script>
@endpush

