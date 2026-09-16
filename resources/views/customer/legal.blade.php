@extends('layouts.customer')

@section('title', $doc['title'] . ' | EcoCraft')

@push('styles')
<style>
    .legal-page { padding:36px 0 72px; }
    .legal-back { display:inline-flex; align-items:center; gap:8px; margin-bottom:20px; color:var(--muted); font-size:12px; font-weight:700; }
    .legal-back:hover { color:var(--brand); }

    .legal-head { max-width:70ch; padding-bottom:26px; border-bottom:1px solid var(--line); }
    .legal-head h1 { margin:10px 0 12px; font:600 clamp(34px,4.4vw,54px)/1.06 'EB Garamond',Georgia,serif; }
    .legal-updated { display:inline-flex; align-items:center; gap:8px; margin-bottom:14px; padding:6px 12px; border-radius:999px; background:var(--surface); color:var(--muted); font-size:11px; font-weight:700; }
    .legal-updated i { color:var(--brand); font-size:11px; }
    .legal-head .lead { margin:0; color:var(--muted); font-size:15.5px; line-height:1.8; }

    .legal-wrap { display:grid; grid-template-columns:minmax(0,1fr) 300px; gap:52px; margin-top:36px; align-items:start; }
    .legal-text { max-width:70ch; }
    .legal-section { scroll-margin-top:96px; }
    .legal-section + .legal-section { margin-top:38px; }
    .legal-section h2 { display:flex; gap:12px; align-items:baseline; margin:0 0 12px; font:600 24px/1.25 'EB Garamond',Georgia,serif; }
    .legal-section h2 span { flex:0 0 auto; color:var(--accent); font:700 13px 'Plus Jakarta Sans'; }
    .legal-section p { margin:0 0 14px; font-size:15px; line-height:1.85; color:#26342b; }
    .legal-section p:last-child { margin-bottom:0; }
    .legal-section ul { margin:12px 0 0; padding:0; list-style:none; display:grid; gap:9px; }
    .legal-section li { position:relative; padding-left:22px; font-size:14.5px; line-height:1.75; color:#26342b; }
    .legal-section li::before { content:''; position:absolute; left:3px; top:.62em; width:7px; height:7px; border-radius:50%; background:var(--accent); }

    .legal-aside { position:sticky; top:96px; display:grid; gap:16px; }
    .legal-card { padding:22px; background:#fff; border:1px solid var(--line); border-radius:16px; box-shadow:0 16px 36px -26px rgba(27,37,32,.24); }
    .legal-card h3 { margin:6px 0 12px; font:600 19px/1.2 'EB Garamond',Georgia,serif; }
    .legal-toc { display:grid; gap:2px; margin:0; padding:0; list-style:none; }
    .legal-toc a { display:block; padding:7px 10px; border-radius:8px; color:var(--muted); font-size:12.5px; font-weight:600; }
    .legal-toc a:hover { color:var(--brand); background:#edf4ee; }
    .legal-card .btn { width:100%; margin-top:4px; }

    @media (max-width:900px) {
        .legal-wrap { grid-template-columns:1fr; gap:28px; margin-top:28px; }
        .legal-aside { position:static; }
    }
    @media (max-width:640px) {
        .legal-page { padding:26px 0 56px; }
        .legal-head h1 { font-size:32px; }
        .legal-section { scroll-margin-top:80px; }
        .legal-section + .legal-section { margin-top:30px; }
    }
</style>
@endpush

@section('content')
<main class="legal-page">
    <div class="page-wrap">
        <a class="legal-back" href="{{ route('customer.dashboard') }}">&larr; Kembali ke beranda</a>

        <header class="legal-head">
            <div class="eyebrow">{{ $doc['eyebrow'] ?? 'Kebijakan' }}</div>
            <h1>{{ $doc['title'] }}</h1>
            <div class="legal-updated"><i class="fa fa-clock-rotate-left" aria-hidden="true"></i> Terakhir diperbarui: {{ $doc['updated'] }}</div>
            <p class="lead">{{ $doc['lead'] }}</p>
        </header>

        <div class="legal-wrap">
            <article class="legal-text">
                @foreach($doc['sections'] as $index => $section)
                    <section class="legal-section" id="{{ $section['id'] }}">
                        <h2><span>{{ sprintf('%02d', $index + 1) }}</span>{{ $section['heading'] }}</h2>
                        @foreach($section['paragraphs'] ?? [] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                        @if(! empty($section['bullets']))
                            <ul>
                                @foreach($section['bullets'] as $bullet)
                                    <li>{{ $bullet }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                @endforeach
            </article>

            <aside class="legal-aside">
                <div class="legal-card">
                    <div class="eyebrow" style="color:var(--accent)">Daftar isi</div>
                    <h3>Isi dokumen</h3>
                    <ul class="legal-toc">
                        @foreach($doc['sections'] as $section)
                            <li><a href="#{{ $section['id'] }}">{{ $section['heading'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="legal-card">
                    <div class="eyebrow" style="color:var(--accent)">Dokumen lain</div>
                    <h3>{{ request()->routeIs('policy.privacy') ? 'Ketentuan Layanan' : 'Kebijakan Privasi' }}</h3>
                    @if(request()->routeIs('policy.privacy'))
                        <a class="btn btn-outline-brand" href="{{ route('policy.terms') }}">Baca ketentuan layanan</a>
                    @else
                        <a class="btn btn-outline-brand" href="{{ route('policy.privacy') }}">Baca kebijakan privasi</a>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</main>
@endsection
