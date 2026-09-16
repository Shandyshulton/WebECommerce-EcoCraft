@extends('layouts.customer')

@section('title', 'Sertifikat Dampak | EcoCraft')

@push('styles')
<style>
    @page{margin:14mm}
    .cert-page{padding:34px 0 72px}
    .cert-actions{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px}
    .cert-sheet{background:#fff;border:1px solid var(--line);border-radius:16px;padding:48px;max-width:880px;margin:0 auto}
    .cert-top{display:flex;justify-content:space-between;align-items:flex-start;gap:24px;border-bottom:2px solid var(--brand);padding-bottom:20px}
    .cert-brand{font:700 22px/1 'EB Garamond',serif;color:var(--brand);letter-spacing:.06em}
    .cert-brand small{display:block;font:600 10px 'Plus Jakarta Sans';letter-spacing:.18em;text-transform:uppercase;color:var(--muted);margin-top:6px}
    .cert-number{text-align:right;font-size:10px;text-transform:uppercase;letter-spacing:.12em;color:var(--muted)}
    .cert-number strong{display:block;font-size:12px;color:var(--ink);letter-spacing:.06em;margin-top:5px}
    .cert-title{text-align:center;margin:34px 0 8px;font:600 34px/1.1 'EB Garamond',serif;color:var(--ink)}
    .cert-sub{text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:.2em;color:var(--accent);margin-bottom:28px}
    .cert-recipient{text-align:center;font:600 26px/1.2 'EB Garamond',serif;color:var(--brand);margin-bottom:10px}
    .cert-body{text-align:center;color:var(--muted);font-size:13px;line-height:1.9;max-width:580px;margin:0 auto 34px}
    .cert-metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);border:1px solid var(--line);border-radius:12px;overflow:hidden}
    .cert-metric{background:#fff;padding:18px 14px;text-align:center}
    .cert-metric span{display:block;font-size:9px;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)}
    .cert-metric strong{display:block;margin-top:8px;font-size:20px;color:var(--brand);font-family:'EB Garamond',serif}
    .cert-metric small{display:block;margin-top:2px;font-size:10px;color:var(--muted)}
    .cert-foot{display:flex;justify-content:space-between;align-items:flex-end;gap:24px;margin-top:40px;padding-top:22px;border-top:1px solid var(--line)}
    .cert-meta{font-size:10px;color:var(--muted);line-height:1.9;text-transform:uppercase;letter-spacing:.08em}
    .cert-meta strong{display:block;font-size:11px;color:var(--ink);text-transform:none;letter-spacing:normal}
    .cert-sign{text-align:center}
    .cert-sign-line{width:190px;border-bottom:1px solid var(--ink);margin-bottom:8px}
    .cert-sign span{font-size:10px;text-transform:uppercase;letter-spacing:.1em;color:var(--muted)}
    .cert-empty{margin:0 0 26px;padding:16px;border:1px dashed var(--line);border-radius:10px;background:#f8faf8;font-size:12px;color:var(--muted);text-align:center}
    @media(max-width:760px){.cert-sheet{padding:26px}.cert-metrics{grid-template-columns:repeat(2,1fr)}.cert-foot{flex-direction:column;align-items:flex-start}.cert-sign-line{width:100%}}
    @media print{
        body *{visibility:hidden}
        .cert-sheet,.cert-sheet *{visibility:visible}
        .cert-sheet{position:absolute;left:0;top:0;width:100%;border:0;border-radius:0;padding:0;max-width:none}
        .cert-actions{display:none}
    }
</style>
@endpush

@section('content')
<main class="section cert-page">
    <div class="page-wrap">
        <div class="cert-actions">
            <a class="btn btn-outline-brand" href="{{ route('customer.dashboard') }}#impact">&larr; Kembali ke dashboard</a>
            <button class="btn btn-brand" type="button" onclick="window.print()"><i class="fa fa-download"></i> Unduh / Cetak PDF</button>
        </div>

        <div class="cert-sheet">
            <div class="cert-top">
                <div class="cert-brand">
                    EcoCraft
                    <small>Marketplace sirkular pengrajin Nusantara</small>
                </div>
                <div class="cert-number">
                    Nomor sertifikat
                    <strong>{{ $certificate['number'] }}</strong>
                </div>
            </div>

            <h1 class="cert-title">Sertifikat Dampak Lingkungan</h1>
            <div class="cert-sub">Paspor jejak lingkungan terverifikasi</div>

            <div class="cert-recipient">{{ $customer->name_customers }}</div>
            <p class="cert-body">
                Atas kontribusinya mengalihkan limbah material dan menekan emisi karbon
                melalui belanja sirkular di EcoCraft. Angka di bawah dihitung dari
                riwayat pesanan yang tercatat pada sistem.
            </p>

            @if($impact['orders'] === 0)
                <div class="cert-empty">
                    Belum ada pesanan tercatat. Sertifikat ini akan terisi otomatis setelah pesanan pertamamu selesai.
                </div>
            @endif

            <div class="cert-metrics">
                <div class="cert-metric">
                    <span>Limbah dialihkan</span>
                    <strong>{{ number_format($impact['waste'], 1, ',', '.') }}</strong>
                    <small>kg material</small>
                </div>
                <div class="cert-metric">
                    <span>Emisi dihindari</span>
                    <strong>{{ number_format($impact['carbon'], 1, ',', '.') }}</strong>
                    <small>kg CO₂e</small>
                </div>
                <div class="cert-metric">
                    <span>Setara penanaman</span>
                    <strong>{{ number_format($impact['trees']) }}</strong>
                    <small>pohon</small>
                </div>
                <div class="cert-metric">
                    <span>Pengrajin didukung</span>
                    <strong>{{ number_format($impact['artisans']) }}</strong>
                    <small>mitra lokal</small>
                </div>
            </div>

            <div class="cert-foot">
                <div class="cert-meta">
                    Periode kontribusi
                    <strong>{{ $certificate['period_start']->translatedFormat('d F Y') }} &ndash; {{ $certificate['period_end']->translatedFormat('d F Y') }}</strong>
                    Total pesanan tercatat
                    <strong>{{ number_format($impact['orders']) }} pesanan sirkular</strong>
                    Diterbitkan
                    <strong>{{ $certificate['issued_at']->translatedFormat('d F Y') }}</strong>
                </div>
                <div class="cert-sign">
                    <div class="cert-sign-line"></div>
                    <span>Tim EcoCraft</span>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
