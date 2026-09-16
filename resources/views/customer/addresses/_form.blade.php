@push('styles')
<style>
    .addr-form-page { padding:34px 0 64px; }
    .addr-back { display:inline-flex; align-items:center; gap:8px; margin-bottom:16px; color:var(--muted); font-size:12px; font-weight:700; }
    .addr-back:hover { color:var(--brand); }
    .addr-form-page h1 { font:600 clamp(30px,4vw,42px)/1.05 'EB Garamond',serif; margin:6px 0 8px; }
    .addr-check { display:flex; align-items:center; gap:10px; margin-top:6px; font-size:12.5px; font-weight:600; cursor:pointer; }
    .addr-check input { width:16px; height:16px; accent-color:var(--brand); cursor:pointer; }
</style>
@endpush

@php($isEdit = $address->exists)

<div class="form-panel">
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $action }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif
        @if(! empty($redirect ?? null))
            <input type="hidden" name="redirect" value="{{ $redirect }}">
        @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="label">Label alamat <span class="text-muted">(opsional)</span></label>
                <input class="form-control" id="label" name="label" value="{{ old('label', $address->label) }}" placeholder="Rumah, Kantor, Kos…">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="recipient_name">Nama penerima</label>
                <input class="form-control" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="phone">Nomor WhatsApp</label>
                <input class="form-control" id="phone" name="phone" value="{{ old('phone', $address->phone) }}" placeholder="08xxxxxxxxxx" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="postal_code">Kode pos</label>
                <input class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label" for="address">Alamat lengkap</label>
                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Jalan, nomor rumah, kecamatan" required>{{ old('address', $address->address) }}</textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="city">Kota</label>
                <input class="form-control" id="city" name="city" value="{{ old('city', $address->city) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="province">Provinsi</label>
                <input class="form-control" id="province" name="province" value="{{ old('province', $address->province) }}" required>
            </div>
            <div class="col-md-12">
                <label class="addr-check">
                    <input type="checkbox" name="is_default" value="1" @checked(old('is_default', $address->is_default))>
                    <span>Jadikan alamat utama</span>
                </label>
            </div>
        </div>

        <div class="d-flex justify-content-end" style="gap:10px;margin-top:20px;flex-wrap:wrap">
            <a class="btn btn-outline-brand" href="{{ route('customer.addresses.index') }}">Batal</a>
            <button class="btn btn-brand" type="submit">{{ $isEdit ? 'Simpan perubahan' : 'Tambah alamat' }}</button>
        </div>
    </form>
</div>
