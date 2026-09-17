@extends('layouts.customer')

@section('title', 'Pembayaran | EcoCraft')

@push('styles')
<style>
    .pay-page { padding:34px 0 72px; }
    .pay-wrap { max-width:560px; margin:0 auto; }
    .pay-card { background:#fff; border:1px solid var(--line); border-radius:16px; padding:28px; }
    .pay-eyebrow { color:var(--accent); font-size:11px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
    .pay-title { font:600 clamp(26px,5vw,36px)/1.1 'EB Garamond',serif; margin:8px 0 6px; }
    .pay-sub { color:var(--muted); font-size:13px; line-height:1.7; margin:0 0 22px; }

    .pay-amount { display:flex; justify-content:space-between; align-items:baseline; gap:14px; padding:16px 0; border-top:1px solid var(--line); border-bottom:1px solid var(--line); margin-bottom:22px; }
    .pay-amount span { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--muted); }
    .pay-amount strong { font:600 30px 'EB Garamond',serif; color:var(--brand); white-space:nowrap; }

    .pay-label { display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:var(--muted); margin-bottom:8px; }
    .pay-va { display:flex; align-items:center; gap:10px; flex-wrap:wrap; padding:14px; border:1px dashed var(--line); border-radius:12px; background:#f8faf8; }
    .pay-va code { font:700 19px ui-monospace,SFMono-Regular,Menlo,monospace; letter-spacing:.08em; color:var(--ink); overflow-wrap:anywhere; }
    .pay-copy { border:0; border-radius:8px; background:var(--brand); color:#fff; font:800 11px 'Plus Jakarta Sans'; padding:9px 15px; cursor:pointer; }

    .pay-qr { display:grid; place-items:center; padding:18px; border:1px solid var(--line); border-radius:12px; background:#fff; min-height:256px; }
    .pay-qr img, .pay-qr canvas { display:block; }
    .pay-qr-fallback { color:var(--muted); font-size:11px; text-align:center; overflow-wrap:anywhere; }

    .pay-steps { margin:20px 0 0; padding-left:18px; color:var(--muted); font-size:12px; line-height:1.95; }

    .pay-cta { display:block; width:100%; margin-top:24px; border:0; border-radius:10px; background:var(--brand); color:#fff; font:800 13px 'Plus Jakarta Sans'; padding:14px; cursor:pointer; text-align:center; text-decoration:none; }
    .pay-note { color:var(--muted); font-size:11px; line-height:1.7; margin:16px 0 0; }

    .pay-paid { text-align:center; padding:6px 0 22px; }
    .pay-paid-icon { display:grid; place-items:center; width:64px; height:64px; margin:0 auto 16px; border-radius:50%; background:#e6f2ea; color:var(--brand); font-size:26px; }
    .pay-meta { list-style:none; margin:0; padding:0; font-size:12px; }
    .pay-meta li { display:flex; justify-content:space-between; gap:12px; padding:9px 0; border-bottom:1px solid var(--line); }
    .pay-meta li:last-child { border-bottom:0; }
    .pay-meta li strong { text-align:right; }

    .pay-back { display:inline-block; margin-top:18px; font-size:12px; color:var(--muted); }
</style>
@endpush

@section('content')
<main class="section pay-page">
    <div class="page-wrap">
        <div class="pay-wrap">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if(session('info'))<div class="alert alert-info">{{ session('info') }}</div>@endif
            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

            @if($order->isPaid())
                <div class="pay-card">
                    <div class="pay-paid">
                        <div class="pay-paid-icon"><i class="fa fa-check"></i></div>
                        <h1 class="pay-title">Pembayaran berhasil</h1>
                        <p class="pay-sub">Pesananmu sedang disiapkan oleh pengrajin.</p>
                    </div>
                    <ul class="pay-meta">
                        <li><span>Nomor pesanan</span><strong>#{{ $order->order_number }}</strong></li>
                        <li><span>Metode</span><strong>{{ $order->payment_method }}</strong></li>
                        <li><span>Waktu bayar</span><strong>{{ $order->paid_at?->translatedFormat('d M Y, H:i') ?? '—' }}</strong></li>
                        <li><span>Jumlah</span><strong>Rp {{ number_format($order->total,0,',','.') }}</strong></li>
                    </ul>
                    <a class="pay-cta" href="{{ route('track.track') }}">Lihat pesanan saya</a>
                </div>
            @else
                <div class="pay-card">
                    <div class="pay-eyebrow">Pembayaran</div>
                    <h1 class="pay-title">
                        @if($order->payment_method === \App\Models\Order::PAYMENT_TRANSFER)
                            Transfer ke Virtual Account
                        @else
                            Pindai kode QRIS
                        @endif
                    </h1>
                    <p class="pay-sub">Selesaikan pembayaran untuk pesanan #{{ $order->order_number }}.</p>

                    <div class="pay-amount">
                        <span>Total pembayaran</span>
                        <strong>Rp {{ number_format($order->total,0,',','.') }}</strong>
                    </div>

                    @if($virtualAccount)
                        <span class="pay-label">Nomor Virtual Account</span>
                        <div class="pay-va">
                            <code id="vaNumber">{{ $virtualAccount }}</code>
                            <button type="button" class="pay-copy" id="copyVa">Salin</button>
                        </div>
                        <ol class="pay-steps">
                            <li>Buka aplikasi m-banking atau ATM.</li>
                            <li>Pilih menu Transfer &rarr; Virtual Account.</li>
                            <li>Masukkan nomor di atas, lalu pastikan nominalnya sesuai.</li>
                            <li>Selesaikan transaksi, lalu tekan tombol di bawah.</li>
                        </ol>
                    @else
                        <span class="pay-label">Kode QRIS</span>
                        <div class="pay-qr" id="qrisBox"></div>
                        <ol class="pay-steps">
                            <li>Buka aplikasi e-wallet atau m-banking yang mendukung QRIS.</li>
                            <li>Pindai kode di atas.</li>
                            <li>Pastikan nama merchant dan nominalnya sesuai.</li>
                            <li>Selesaikan pembayaran, lalu tekan tombol di bawah.</li>
                        </ol>
                    @endif

                    <form action="{{ route('payment.confirm', $order) }}" method="POST">
                        @csrf
                        <button class="pay-cta" type="submit">Saya sudah bayar</button>
                    </form>
                    <p class="pay-note">
                        Ini simulasi: tidak ada verifikasi otomatis dari penyedia pembayaran.
                        Status berpindah setelah kamu menekan tombol di atas.
                    </p>
                </div>
            @endif

            <a class="pay-back" href="{{ route('track.track') }}">&larr; Kembali ke pesanan saya</a>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
(function () {
    var copy = document.getElementById('copyVa');
    if (copy) {
        copy.addEventListener('click', function () {
            var value = document.getElementById('vaNumber').textContent.trim();
            navigator.clipboard.writeText(value).then(function () {
                copy.textContent = 'Tersalin';
            }).catch(function () {
                copy.textContent = value;
            });
        });
    }

    var box = document.getElementById('qrisBox');
    if (!box) return;

    var payload = @json($qrisPayload ?? '');

    if (typeof QRCode === 'undefined') {
        box.innerHTML = '<p class="pay-qr-fallback">Kode QR gagal dimuat.<br>Payload: ' + payload + '</p>';
        return;
    }

    new QRCode(box, { text: payload, width: 220, height: 220, correctLevel: QRCode.CorrectLevel.M });
})();
</script>
@endpush
