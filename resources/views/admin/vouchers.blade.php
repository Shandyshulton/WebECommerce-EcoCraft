@extends('layout.app')

@section('breadcrumb')<span class="current">Voucher</span>@endsection

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">Promo &amp; reward</div>
        <h1>Kelola Voucher</h1>
        <p class="subtle mb-0">Buat kode promo yang bisa diklaim customer, atau tandai sebagai template reward otomatis.</p>
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="admin-grid" style="grid-template-columns:1.6fr 1fr; align-items:start">
    <div class="admin-table-scroll">
        <table class="table">
            <thead>
                <tr><th>Kode</th><th>Judul</th><th>Tipe</th><th>Nilai</th><th>Min. belanja</th><th>Kuota</th><th>Periode</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($vouchers as $v)
                    <tr>
                        <td><strong>{{ $v->code }}</strong></td>
                        <td>{{ $v->title }}</td>
                        <td>{{ $v->type === 'percent' ? 'Persen' : 'Nominal' }}</td>
                        <td>
                            @if($v->type === 'percent')
                                {{ (float) $v->value }}%@if($v->max_discount)<div class="text-muted" style="font-size:11px">maks Rp {{ number_format((float) $v->max_discount, 0, ',', '.') }}</div>@endif
                            @else
                                Rp {{ number_format((float) $v->value, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>Rp {{ number_format((float) $v->min_spend, 0, ',', '.') }}</td>
                        <td>{{ $v->used_count }}/{{ $v->usage_limit ?? '∞' }}</td>
                        <td style="font-size:11px">
                            {{ $v->starts_at ? $v->starts_at->format('d M Y') : '—' }}<br>
                            s.d. {{ $v->expires_at ? $v->expires_at->format('d M Y') : '—' }}
                        </td>
                        <td>
                            @if($v->is_active)<span class="status approved">Aktif</span>@else<span class="status rejected">Nonaktif</span>@endif
                            @if($v->is_reward)<div class="mt-1"><span class="status pending">Reward</span></div>@endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.vouchers.edit', $v->id) }}"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.vouchers.toggle', $v->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm" type="submit" title="{{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas {{ $v->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center">Belum ada voucher. Tambahkan di panel kanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <section class="panel">
        <div class="panel-heading"><div><div class="eyebrow">Tambah</div><h2>Voucher baru</h2></div></div>
        <form action="{{ route('admin.vouchers.store') }}" method="POST">
            @csrf
            @include('admin._voucher_form', ['voucher' => null])
            @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <button class="btn-brand" type="submit"><i class="fas fa-plus"></i> Tambah voucher</button>
        </form>
    </section>
</div>
@endsection
