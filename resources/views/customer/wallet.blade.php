@extends('layouts.customer')
@section('title', 'Dompet Sirkular | EcoCraft')

@push('styles')
<style>
.wallet-page{padding:34px 0 72px}
.wallet-grid{display:grid;grid-template-columns:1.35fr 1fr;gap:22px;align-items:start}
.wallet-card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;box-shadow:0 8px 24px rgba(27,37,32,.05)}
.wallet-card h2{font:600 24px/1.1 'EB Garamond',serif;margin:0 0 16px}
.wallet-voucher{display:flex;justify-content:space-between;gap:16px;align-items:center;padding:16px;border:1px dashed var(--line);border-radius:12px;background:#f8faf8;margin-bottom:12px}
.wallet-voucher strong{display:block;font-size:14px}
.wallet-voucher .code{display:inline-block;margin-top:4px;padding:2px 8px;border-radius:6px;background:#c6ebd7;color:var(--brand);font-size:11px;font-weight:800;letter-spacing:.08em}
.wallet-voucher small{color:var(--muted);font-size:11px}
.wallet-value{white-space:nowrap;font:600 22px/1 'EB Garamond',serif;color:var(--accent)}
.wallet-table{width:100%;border-collapse:collapse;font-size:12px}
.wallet-table th{text-align:left;color:var(--muted);font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;padding:8px 6px;border-bottom:1px solid var(--line)}
.wallet-table td{padding:10px 6px;border-bottom:1px solid var(--line)}
.wallet-empty{padding:18px;border:1px dashed var(--line);border-radius:12px;color:var(--muted);font-size:12px;text-align:center}
@media(max-width:900px){.wallet-grid{grid-template-columns:1fr}.member-stat-grid.wallet-stats{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<main class="section wallet-page">
    <div class="page-wrap">
        <div class="eyebrow">Dompet sirkular</div>
        <h1 class="mb-4">Koin &amp; Voucher</h1>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="member-stat-grid wallet-stats mb-4" style="grid-template-columns:repeat(3,minmax(0,1fr))">
            <div class="member-panel member-stat">
                <div>
                    <span class="member-stat-label">Saldo koin</span>
                    <span class="member-stat-icon">◉</span>
                    <div class="member-stat-value">{{ number_format($customer->coin_balance, 0, ',', '.') }} <small class="text-muted">koin</small></div>
                    <small class="text-muted">Senilai Rp {{ number_format($customer->coin_balance * $coinValue, 0, ',', '.') }} potongan.</small>
                </div>
            </div>
            <div class="member-panel member-stat">
                <div>
                    <span class="member-stat-label">Voucher tersedia</span>
                    <span class="member-stat-icon">▤</span>
                    <div class="member-stat-value">{{ $availableVouchers->count() }} <small class="text-muted">kupon</small></div>
                    <small class="text-muted">Siap dipakai saat checkout.</small>
                </div>
            </div>
            <div class="member-panel member-stat">
                <div>
                    <span class="member-stat-label">Sudah dipakai</span>
                    <span class="member-stat-icon">✓</span>
                    <div class="member-stat-value">{{ $usedVouchers->count() }} <small class="text-muted">kupon</small></div>
                    <small class="text-muted">Riwayat pemakaian terbaru.</small>
                </div>
            </div>
        </div>

        <div class="wallet-grid">
            <div class="d-grid gap-3">
                <section class="wallet-card">
                    <h2>Voucher tersedia</h2>
                    @forelse($availableVouchers as $cv)
                        @php($v = $cv->voucher)
                        <div class="wallet-voucher">
                            <div>
                                <strong>{{ $v->title }}</strong>
                                <span class="code">{{ $v->code }}</span>
                                <small>Min. belanja Rp {{ number_format((float) $v->min_spend, 0, ',', '.') }}@if($v->expires_at) · Berlaku sampai {{ $v->expires_at->format('d M Y') }}@endif</small>
                            </div>
                            <span class="wallet-value">
                                @if($v->type === 'percent'){{ (float) $v->value }}%@else Rp {{ number_format((float) $v->value, 0, ',', '.') }}@endif
                            </span>
                        </div>
                    @empty
                        <div class="wallet-empty">Belum ada voucher. Klaim promo di panel kanan.</div>
                    @endforelse
                </section>

                <section class="wallet-card">
                    <h2>Riwayat koin</h2>
                    @if($transactions->isEmpty())
                        <div class="wallet-empty">Belum ada transaksi koin. Koin cair otomatis setiap kali kamu checkout.</div>
                    @else
                        <table class="wallet-table">
                            <thead><tr><th>Tanggal</th><th>Keterangan</th><th>Koin</th><th>Saldo</th></tr></thead>
                            <tbody>
                                @foreach($transactions as $t)
                                    <tr>
                                        <td>{{ $t->created_at->format('d M Y') }}</td>
                                        <td>{{ $t->description }}</td>
                                        <td style="color:{{ $t->type === 'redeem' ? '#a53c27' : 'var(--brand)' }};font-weight:700">{{ $t->type === 'redeem' ? '-' : '+' }}{{ number_format($t->amount, 0, ',', '.') }}</td>
                                        <td>{{ number_format($t->balance_after, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </section>
            </div>

            <div class="d-grid gap-3">
                <section class="wallet-card">
                    <h2>Klaim voucher</h2>
                    @forelse($claimableVouchers as $v)
                        <div class="wallet-voucher">
                            <div>
                                <strong>{{ $v->title }}</strong>
                                <span class="code">{{ $v->code }}</span>
                                <small>Min. belanja Rp {{ number_format((float) $v->min_spend, 0, ',', '.') }}@if($v->expires_at) · s.d. {{ $v->expires_at->format('d M Y') }}@endif</small>
                            </div>
                            <form method="POST" action="{{ route('customer.wallet.claim', $v->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-brand btn-sm">Klaim</button>
                            </form>
                        </div>
                    @empty
                        <div class="wallet-empty">Tidak ada promo yang bisa diklaim saat ini.</div>
                    @endforelse
                </section>

                <section class="wallet-card">
                    <h2>Cara dapat koin</h2>
                    <p class="small text-muted mb-2">Setiap Rp {{ number_format((int) config('rewards.coin_earn_per'), 0, ',', '.') }} belanja kamu mendapat 1 koin sirkular. 1 koin bernilai Rp {{ number_format($coinValue, 0, ',', '.') }} potongan.</p>
                    <p class="small text-muted mb-0">Potongan voucher + koin dibatasi maksimal {{ (int) (config('rewards.max_discount_ratio') * 100) }}% dari subtotal pesanan.</p>
                </section>

                @if($usedVouchers->isNotEmpty())
                    <section class="wallet-card">
                        <h2>Voucher terpakai</h2>
                        @foreach($usedVouchers as $cv)
                            <div class="wallet-voucher">
                                <div>
                                    <strong>{{ optional($cv->voucher)->title }}</strong>
                                    <span class="code">{{ optional($cv->voucher)->code }}</span>
                                    <small>Dipakai {{ optional($cv->used_at)->format('d M Y') }}@if($cv->order) · Order #{{ $cv->order->order_number }}@endif</small>
                                </div>
                            </div>
                        @endforeach
                    </section>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
