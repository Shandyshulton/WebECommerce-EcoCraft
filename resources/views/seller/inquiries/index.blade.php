@extends('seller.dashboard')

@section('breadcrumb')<span class="current">Pertanyaan</span>@endsection

@section('content')
<div class="seller-page">
    <div class="seller-toolbar">
        <div>
            <div class="eyebrow">Layanan customer</div>
            <h1>Pertanyaan Produk</h1>
            <p class="subtle mb-0">Balas pertanyaan customer seputar produk tokomu.</p>
        </div>
        @if($unreadThreads > 0)
            <span class="badge-status warn">{{ $unreadThreads }} belum dibaca</span>
        @endif
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="stat-grid mb-4" style="grid-template-columns:repeat(2,1fr)">
        <div class="stat-card"><div class="icon"><i class="fas fa-comments"></i></div><strong>{{ $totalThreads }}</strong><span>Total percakapan</span></div>
        <div class="stat-card"><div class="icon"><i class="fas fa-envelope"></i></div><strong>{{ $unreadThreads }}</strong><span>Belum dibaca</span></div>
    </div>

    <form class="filter-bar" method="GET">
        <div class="filter-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk / customer…">
        </div>
        <select name="unread" class="filter-select">
            <option value="">Semua pesan</option>
            <option value="1" @selected(request('unread')==='1')>Belum dibaca</option>
        </select>
        <button class="btn-brand" type="submit"><i class="fas fa-filter"></i> Filter</button>
        @if(request()->hasAny(['q','unread']))
            <a class="filter-reset" href="{{ route('seller.inquiries.index') }}">Reset</a>
        @endif
    </form>

    <div class="data-card">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Customer</th>
                        <th>Pesan terakhir</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inquiries as $inquiry)
                        @php($unread = $inquiry->messages()->where('sender_type','customer')->whereNull('read_at')->exists())
                        <tr>
                            <td><span class="cell-title">{{ optional($inquiry->product)->name ?? $inquiry->subject ?? 'Produk EcoCraft' }}</span></td>
                            <td>{{ optional($inquiry->customer)->name_customers ?? 'Customer' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit(optional($inquiry->latestMessage)->body, 45) ?: '—' }} @if($unread)<span class="badge-status warn">baru</span>@endif</td>
                            <td>{{ optional($inquiry->last_message_at)->diffForHumans() }}</td>
                            <td><a class="btn-icon edit" href="{{ route('seller.inquiries.show', $inquiry->id_inquiries) }}"><i class="fas fa-comments"></i> Buka</a></td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="5">Belum ada pertanyaan dari customer.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $inquiries->links() }}</div>
</div>
@endsection
