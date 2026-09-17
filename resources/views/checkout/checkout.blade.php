@extends('layouts.customer')
@section('title','Checkout | EcoCraft')
@push('styles')
<style>
    .checkout-page { padding:34px 0 72px; }
    .checkout-heading h1 { font:600 clamp(30px,5.5vw,46px)/1.05 'EB Garamond',serif; margin:8px 0 26px; }

    /* Dua kolom hanya saat benar-benar muat; sidebar memakai rentang, bukan 360px kaku. */
    .checkout-layout { display:grid; grid-template-columns:minmax(0,1.2fr) minmax(300px,360px); gap:20px; align-items:start; }

    .checkout-panel, .checkout-summary { padding:24px; background:#fff; border:1px solid var(--line); border-radius:14px; }
    .checkout-panel { min-width:0; }
    .checkout-panel h2, .checkout-summary h2 { font:600 27px/1 'EB Garamond',serif; margin:0 0 22px; }
    .checkout-page .form-label { font-size:11px; font-weight:800; }
    .checkout-page .form-control, .checkout-page .form-select { min-height:44px; border:1px solid var(--line); border-radius:8px; background-color:#f8faf8; font-size:12px; }
    .checkout-page .form-control:focus, .checkout-page .form-select:focus { border-color:var(--brand); box-shadow:0 0 0 3px rgba(30,75,56,.12); }
    .checkout-page textarea.form-control { min-height:88px; }

    /* ===== Sistem form checkout (menggantikan grid Bootstrap) ===== */
    .co-section + .co-section { margin-top:20px; padding-top:20px; border-top:1px solid var(--line); }
    .co-section-title { margin:0 0 12px; font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:var(--muted); }

    .co-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px 14px; }
    .co-field { min-width:0; }
    .co-field.co-full { grid-column:1/-1; }
    /* Checkbox "Simpan alamat" punya gayanya sendiri, jadi dikecualikan. */
    .co-field > label:not(.save-address-check) { display:block; font-size:11px; font-weight:800; margin-bottom:8px; color:var(--ink); }

    .co-field input:not([type=checkbox]):not([type=radio]),
    .co-field select,
    .co-field textarea {
        width:100%; min-height:44px; padding:10px 12px;
        border:1px solid var(--line); border-radius:9px;
        /* background-color, bukan shorthand `background` — shorthand akan
           menghapus background-image panah kustom pada select. */
        background-color:#f8faf8; color:var(--ink);
        font:13px 'Plus Jakarta Sans',system-ui,sans-serif;
    }
    /* Ruang untuk panah kustom agar teks tidak menabraknya. */
    .co-field select { padding-right:38px; }
    .co-field textarea { min-height:88px; resize:vertical; }
    .co-field input:not([type=checkbox]):not([type=radio]):focus,
    .co-field select:focus,
    .co-field textarea:focus {
        outline:0; border-color:var(--brand); box-shadow:0 0 0 3px rgba(30,75,56,.12);
    }

    /* Field yang tidak bisa diubah harus terlihat berbeda, bukan seperti input biasa.
       Selector `:not()` diulang agar spesifisitasnya setara dengan aturan dasar di atas —
       tanpa itu, aturan dasar yang lebih spesifik akan menimpanya. */
    .co-field input[readonly]:not([type=checkbox]):not([type=radio]) {
        background:var(--surface); color:var(--muted); border-style:dashed; cursor:not-allowed;
    }
    .co-field input[readonly]:not([type=checkbox]):not([type=radio]):focus {
        border-color:var(--line); box-shadow:none;
    }

    /* Ringkasan menempel, tetapi tingginya dibatasi supaya tombol "Buat pesanan"
       tidak pernah terjebak di bawah lipatan pada layar pendek. */
    .checkout-summary { position:sticky; top:96px; max-height:calc(100vh - 120px); overflow-y:auto; overscroll-behavior:contain; }

    .checkout-item { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; padding:12px 0; border-bottom:1px solid var(--line); font-size:12px; }
    .checkout-item > span { min-width:0; overflow-wrap:anywhere; }
    .checkout-item > strong { flex:0 0 auto; white-space:nowrap; }

    .checkout-total { display:flex; justify-content:space-between; align-items:baseline; gap:16px; padding-top:18px; font-size:14px; }
    .checkout-total > strong:last-child { flex:0 0 auto; white-space:nowrap; }

    .checkout-reward { padding:14px 0; border-bottom:1px solid var(--line); }
    .checkout-reward .form-label { margin-bottom:8px; }
    /* Jarak eksplisit antar kontrol di blok ini — jangan bergantung pada utility
       Bootstrap, yang spesifisitasnya kalah dan membuat kontrol saling menempel. */
    .checkout-reward select { padding-right:36px; margin-bottom:12px; }
    /* Teks bantuan harus punya jarak dari kontrol di atasnya, jangan menempel. */
    .checkout-reward small { display:block; margin-top:10px; color:var(--muted); font-size:11px; line-height:1.6; }

    .checkout-coins { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
    .checkout-coins input { flex:0 1 120px; min-width:0; }

    /* Halaman customer memuat Bootstrap 3.3.6, yang tidak punya utility Bootstrap 5
       (w-100, mt-*, mb-*). Lebar dan jarak di blok ini diatur sendiri di sini. */
    .checkout-cta { display:block; width:100%; margin-top:20px; }
    .checkout-note { display:block; margin:14px 0 0; color:var(--muted); font-size:11px; line-height:1.6; }
    .checkout-page .alert ul { margin:0; }

    /* Di bawah 1000px sidebar sudah terlalu sempit untuk berdampingan. */
    @media (max-width:1000px) {
        .checkout-layout { grid-template-columns:minmax(0,1fr); gap:16px; }
        .checkout-summary { position:static; max-height:none; overflow:visible; }
    }

    /* Di bawah 560px dua kolom jadi terlalu sempit untuk field. */
    @media (max-width:560px) {
        .co-grid { grid-template-columns:minmax(0,1fr); }
    }

    @media (max-width:640px) {
        .checkout-page { padding:22px 0 48px; }
        .checkout-panel, .checkout-summary { padding:16px; }
        .checkout-panel h2, .checkout-summary h2 { font-size:22px; margin-bottom:16px; }
        .checkout-heading h1 { margin:6px 0 18px; }
    }
</style>
<style>
    .address-picker { display:grid; gap:10px; margin-bottom:18px; }
    .address-option { display:flex; gap:12px; padding:13px; border:1px solid var(--line); border-radius:10px; background:#f8faf8; cursor:pointer; }
    .address-option:has(input:checked) { border-color:var(--brand); background:#edf4ee; }
    .address-option input { flex:0 0 auto; width:16px; height:16px; margin-top:2px; accent-color:var(--brand); cursor:pointer; }
    .address-option strong { display:block; font-size:12.5px; }
    .address-option > span { min-width:0; }
    .address-option small { display:block; margin-top:4px; color:var(--muted); font-size:11.5px; line-height:1.55; overflow-wrap:anywhere; }
    .save-address-check { display:flex; align-items:center; gap:10px; font-size:12.5px; font-weight:600; cursor:pointer; }
    .save-address-check input { width:16px; height:16px; accent-color:var(--brand); cursor:pointer; }
</style>
@endpush
@section('content')
<main class="section checkout-page">
    <div class="page-wrap">
        <div class="eyebrow">Checkout aman</div>
        <div class="checkout-heading"><h1>Selesaikan pesananmu.</h1></div>
        @if($errors->any())
            <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="checkout-layout">
                <section class="checkout-panel">
                    <h2>Detail pengiriman</h2>

                    @php($addressChoice = old('address_choice', $addresses->first()->id_addresses))

                    <div class="address-picker">
                        @foreach($addresses as $address)
                            <label class="address-option">
                                <input type="radio" name="address_choice" value="{{ $address->id_addresses }}"
                                    data-address="{{ $address->address }}"
                                    data-city="{{ $address->city }}"
                                    data-province="{{ $address->province }}"
                                    data-postal="{{ $address->postal_code }}"
                                    @checked((string) $addressChoice === (string) $address->id_addresses)>
                                <span>
                                    <strong>{{ $address->labelText() }}@if($address->is_default) · Utama @endif</strong>
                                    <small>{{ $address->recipient_name }} · {{ $address->phone }}</small>
                                    <small>{{ $address->address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</small>
                                </span>
                            </label>
                        @endforeach
                        <label class="address-option">
                            <input type="radio" name="address_choice" value="new" @checked((string) $addressChoice === 'new')>
                            <span><strong>Alamat baru</strong><small>Isi alamat pengiriman lain di bawah.</small></span>
                        </label>
                    </div>

                    <div class="co-section">
                        <h3 class="co-section-title">Penerima</h3>
                        <div class="co-grid">
                            <div class="co-field">
                                <label for="co-name">Nama penerima</label>
                                <input id="co-name" value="{{ Auth::guard('customer')->user()->name_customers }}" readonly>
                            </div>
                            <div class="co-field">
                                <label for="co-email">Email</label>
                                <input id="co-email" value="{{ Auth::guard('customer')->user()->email }}" readonly>
                            </div>
                            <div class="co-field co-full">
                                <label for="co-phone">Nomor WhatsApp</label>
                                <input id="co-phone" name="customer_phone" value="{{ old('customer_phone', Auth::guard('customer')->user()->phone_number) }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- Blok ini hanya tampil saat memilih "Alamat baru"; JS menyembunyikannya
                         sebagai satu kesatuan lewat data-manual-field. --}}
                    <div class="co-section" data-manual-field>
                        <h3 class="co-section-title">Alamat pengiriman</h3>
                        <div class="co-grid">
                            <div class="co-field co-full">
                                <label for="co-address">Alamat lengkap</label>
                                <textarea id="co-address" name="shipping_address" rows="3">{{ old('shipping_address') }}</textarea>
                            </div>
                            <div class="co-field">
                                <label for="co-city">Kota</label>
                                <input id="co-city" name="shipping_city" value="{{ old('shipping_city', Auth::guard('customer')->user()->city) }}">
                            </div>
                            <div class="co-field">
                                <label for="co-province">Provinsi</label>
                                <input id="co-province" name="shipping_province" value="{{ old('shipping_province', Auth::guard('customer')->user()->province) }}">
                            </div>
                            <div class="co-field">
                                <label for="co-postal">Kode pos</label>
                                <input id="co-postal" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}">
                            </div>
                            <div class="co-field">
                                <label for="co-label">Label alamat (opsional)</label>
                                <input id="co-label" name="address_label" value="{{ old('address_label') }}" placeholder="Rumah, Kantor…">
                            </div>
                            <div class="co-field co-full">
                                <label class="save-address-check"><input type="checkbox" name="save_address" value="1" @checked(old('save_address'))><span>Simpan alamat ini ke daftar alamat</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="co-section">
                        <h3 class="co-section-title">Pengiriman &amp; pembayaran</h3>
                        <div class="co-grid">
                            <div class="co-field">
                                <label for="co-shipping">Metode pengiriman</label>
                                <select id="co-shipping" name="shipping_method" required>@foreach(['Reguler','Express','Sameday'] as $method)<option value="{{ $method }}">{{ $method }}</option>@endforeach</select>
                            </div>
                            <div class="co-field">
                                <label for="co-payment">Metode pembayaran</label>
                                <select id="co-payment" name="payment_method" required>@foreach(['COD','Transfer Bank','QRIS'] as $method)<option value="{{ $method }}">{{ $method }}</option>@endforeach</select>
                            </div>
                        </div>
                    </div>
                </section>

                <aside class="checkout-summary">
                    <h2>Ringkasan order</h2>
                    @foreach($items as $item)
                        <div class="checkout-item"><span>{{ $item['product']->name }} x {{ $item['quantity'] }}</span><strong>Rp {{ number_format($item['subtotal'],0,',','.') }}</strong></div>
                    @endforeach

                    <div class="checkout-reward">
                        <label class="form-label" for="voucherCode">Voucher</label>
                        @if($availableVouchers->isNotEmpty())
                            <select class="form-select form-select-sm mb-2" id="voucherSelect">
                                <option value="">Tidak pakai voucher</option>
                                @foreach($availableVouchers as $cv)
                                    <option value="{{ $cv->id }}" @selected((int) old('customer_voucher_id') === $cv->id)>{{ $cv->voucher->title }} · {{ $cv->voucher->code }}</option>
                                @endforeach
                            </select>
                        @endif
                        <input type="text" class="form-control form-control-sm" id="voucherCode" name="voucher_code" value="{{ old('voucher_code') }}" placeholder="Kode voucher">
                        <input type="hidden" name="customer_voucher_id" id="customerVoucherId" value="{{ old('customer_voucher_id') }}">
                        <small>Masukkan kode voucher atau pilih dari <a href="{{ route('customer.wallet') }}">dompet</a>.</small>
                    </div>

                    <div class="checkout-reward">
                        <label class="form-label" for="coinsUsed">Koin sirkular · 1 koin = Rp {{ number_format($coinValue,0,',','.') }}</label>
                        <div class="checkout-coins">
                            <input type="number" class="form-control form-control-sm" id="coinsUsed" name="coins_used" min="0" max="{{ $coinBalance }}" value="{{ (int) old('coins_used', 0) }}">
                            <button type="button" class="btn btn-outline-brand btn-sm" id="useMaxCoins">Pakai maks</button>
                        </div>
                        <small>Saldo kamu {{ number_format($coinBalance,0,',','.') }} koin.</small>
                    </div>

                    <div class="checkout-item"><span>Subtotal</span><strong id="sumSubtotal">Rp {{ number_format($subtotal,0,',','.') }}</strong></div>
                    <div class="checkout-item"><span>Potongan voucher</span><strong id="sumVoucher">- Rp 0</strong></div>
                    <div class="checkout-item"><span>Potongan koin</span><strong id="sumCoin">- Rp 0</strong></div>
                    <div class="checkout-total"><strong>Total pembayaran</strong><strong class="price" id="sumTotal">Rp {{ number_format($total,0,',','.') }}</strong></div>
                    <p class="small text-muted checkout-note" id="rewardNote">Maksimal potongan 50% dari subtotal.</p>

                    <button class="btn btn-brand checkout-cta" type="submit">Buat pesanan</button>
                    <p class="small text-muted checkout-note">Dengan melanjutkan, kamu menyetujui detail pengiriman dan pembayaran.</p>
                </aside>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
(function () {
    var coinBalance = {{ (int) $coinBalance }};
    var previewUrl = "{{ route('checkout.preview') }}";
    var csrf = "{{ csrf_token() }}";

    var select = document.getElementById('voucherSelect');
    var codeInput = document.getElementById('voucherCode');
    var voucherIdInput = document.getElementById('customerVoucherId');
    var coinsInput = document.getElementById('coinsUsed');
    var maxBtn = document.getElementById('useMaxCoins');
    var note = document.getElementById('rewardNote');

    function rupiah(n) { return 'Rp ' + Number(n || 0).toLocaleString('id-ID'); }

    function payload() {
        var id = voucherIdInput.value ? parseInt(voucherIdInput.value, 10) : null;
        return {
            customer_voucher_id: id,
            voucher_code: id ? '' : (codeInput.value || ''),
            coins_used: parseInt(coinsInput.value || '0', 10) || 0
        };
    }

    function render(data) {
        document.getElementById('sumSubtotal').textContent = rupiah(data.subtotal);
        document.getElementById('sumVoucher').textContent = '- ' + rupiah(data.voucher_discount);
        document.getElementById('sumCoin').textContent = '- ' + rupiah(data.coin_discount);
        document.getElementById('sumTotal').textContent = rupiah(data.total);
        coinsInput.value = data.coins_used;

        if (data.voucher_error) {
            note.style.color = '#a53c27';
            note.textContent = data.voucher_error;
            return;
        }
        note.style.color = '';
        var text = 'Kamu akan mendapat ' + data.coins_earned + ' koin dari pesanan ini.';
        if (data.max_coins >= 0 && data.coins_used >= data.max_coins && data.max_coins > 0) {
            text += ' Maksimal koin yang bisa dipakai sekarang: ' + data.max_coins + '.';
        }
        note.textContent = text;
    }

    var timer = null;
    function refresh() {
        fetch(previewUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify(payload())
        }).then(function (r) { return r.json(); }).then(render).catch(function () {});
    }
    function schedule() { clearTimeout(timer); timer = setTimeout(refresh, 350); }

    if (select) {
        select.addEventListener('change', function () {
            voucherIdInput.value = select.value || '';
            if (select.value) codeInput.value = '';
            refresh();
        });
    }
    if (codeInput) {
        codeInput.addEventListener('input', function () {
            if (codeInput.value && select) { select.value = ''; voucherIdInput.value = ''; }
            schedule();
        });
    }
    coinsInput.addEventListener('input', schedule);
    if (maxBtn) {
        maxBtn.addEventListener('click', function () { coinsInput.value = coinBalance; refresh(); });
    }

    if (voucherIdInput.value && codeInput) codeInput.value = '';
    refresh();
})();
</script>
<script>
(function () {
    var fields = document.querySelectorAll('[data-manual-field]');
    if (!fields.length) return;

    var radios = document.querySelectorAll('input[name=address_choice]');
    var addressInput = document.querySelector('textarea[name=shipping_address]');
    var cityInput = document.querySelector('input[name=shipping_city]');
    var provinceInput = document.querySelector('input[name=shipping_province]');
    var postalInput = document.querySelector('input[name=shipping_postal_code]');
    var manualInputs = [addressInput, cityInput, provinceInput, postalInput];

    function apply() {
        var checked = document.querySelector('input[name=address_choice]:checked');
        var isNew = !checked || checked.value === 'new';

        fields.forEach(function (field) { field.style.display = isNew ? '' : 'none'; });

        manualInputs.forEach(function (input) {
            if (!input) return;
            if (isNew) { input.setAttribute('required', 'required'); }
            else { input.removeAttribute('required'); }
        });

        if (!isNew && checked) {
            if (addressInput) addressInput.value = checked.getAttribute('data-address') || '';
            if (cityInput) cityInput.value = checked.getAttribute('data-city') || '';
            if (provinceInput) provinceInput.value = checked.getAttribute('data-province') || '';
            if (postalInput) postalInput.value = checked.getAttribute('data-postal') || '';
        }
    }

    radios.forEach(function (radio) { radio.addEventListener('change', apply); });
    apply();
})();
</script>
@endpush
