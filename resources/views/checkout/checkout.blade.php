@extends('layouts.customer')
@section('title','Checkout | EcoCraft')
@push('styles')
<style>.checkout-page{padding:34px 0 72px}.checkout-heading h1{font:600 46px/1 'EB Garamond',serif;margin:8px 0 26px}.checkout-layout{display:grid;grid-template-columns:minmax(0,1.2fr) 360px;gap:20px}.checkout-panel,.checkout-summary{padding:24px;background:#fff;border:1px solid var(--line);border-radius:14px}.checkout-panel h2,.checkout-summary h2{font:600 27px/1 'EB Garamond',serif;margin:0 0 22px}.checkout-page .form-label{font-size:11px;font-weight:800}.checkout-page .form-control,.checkout-page .form-select{min-height:44px;border:1px solid var(--line);border-radius:8px;background:#f8faf8;font-size:12px}.checkout-page .form-control:focus,.checkout-page .form-select:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}.checkout-summary{position:sticky;top:96px;align-self:start}.checkout-item{display:flex;justify-content:space-between;gap:16px;padding:12px 0;border-bottom:1px solid var(--line);font-size:12px}.checkout-total{display:flex;justify-content:space-between;padding-top:18px;font-size:14px}.checkout-reward{padding:14px 0;border-bottom:1px solid var(--line)}.checkout-reward .form-label{margin-bottom:6px}.checkout-reward small{color:var(--muted);font-size:11px}@media(max-width:800px){.checkout-layout{grid-template-columns:1fr}.checkout-summary{position:static}.checkout-heading h1{font-size:40px}}</style>
@endpush
@section('content')
<main class="section checkout-page">
    <div class="page-wrap">
        <div class="eyebrow">Checkout aman</div>
        <div class="checkout-heading"><h1>Selesaikan pesananmu.</h1></div>
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="checkout-layout">
                <section class="checkout-panel">
                    <h2>Detail pengiriman</h2>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nama penerima</label><input class="form-control" value="{{ Auth::guard('customer')->user()->name_customers }}" readonly></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" value="{{ Auth::guard('customer')->user()->email }}" readonly></div>
                        <div class="col-md-6"><label class="form-label">Nomor WhatsApp</label><input class="form-control" name="customer_phone" value="{{ old('customer_phone',Auth::guard('customer')->user()->phone_number) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Kode pos</label><input class="form-control" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required></div>
                        <div class="col-12"><label class="form-label">Alamat lengkap</label><textarea class="form-control" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Kota</label><input class="form-control" name="shipping_city" value="{{ old('shipping_city',Auth::guard('customer')->user()->city) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Provinsi</label><input class="form-control" name="shipping_province" value="{{ old('shipping_province',Auth::guard('customer')->user()->province) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Metode pengiriman</label><select class="form-select" name="shipping_method" required>@foreach(['Reguler','Express','Sameday'] as $method)<option value="{{ $method }}">{{ $method }}</option>@endforeach</select></div>
                        <div class="col-md-6"><label class="form-label">Metode pembayaran</label><select class="form-select" name="payment_method" required>@foreach(['COD','Transfer Bank','QRIS'] as $method)<option value="{{ $method }}">{{ $method }}</option>@endforeach</select></div>
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
                        <div class="d-flex gap-2 align-items-center">
                            <input type="number" class="form-control form-control-sm" id="coinsUsed" name="coins_used" min="0" max="{{ $coinBalance }}" value="{{ (int) old('coins_used', 0) }}" style="max-width:120px">
                            <button type="button" class="btn btn-outline-brand btn-sm" id="useMaxCoins">Pakai maks</button>
                        </div>
                        <small>Saldo kamu {{ number_format($coinBalance,0,',','.') }} koin.</small>
                    </div>

                    <div class="checkout-item"><span>Subtotal</span><strong id="sumSubtotal">Rp {{ number_format($subtotal,0,',','.') }}</strong></div>
                    <div class="checkout-item"><span>Potongan voucher</span><strong id="sumVoucher">- Rp 0</strong></div>
                    <div class="checkout-item"><span>Potongan koin</span><strong id="sumCoin">- Rp 0</strong></div>
                    <div class="checkout-total"><strong>Total pembayaran</strong><strong class="price" id="sumTotal">Rp {{ number_format($total,0,',','.') }}</strong></div>
                    <p class="small text-muted mt-2 mb-0" id="rewardNote">Maksimal potongan 50% dari subtotal.</p>

                    <button class="btn btn-brand w-100 mt-4" type="submit">Buat pesanan</button>
                    <p class="small text-muted mt-3 mb-0">Dengan melanjutkan, kamu menyetujui detail pengiriman dan pembayaran.</p>
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
@endpush
