@extends('layout.app')

@section('breadcrumb')<span class="current">Faktor Dampak</span>@endsection

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">Referensi dampak</div>
        <h1>Faktor Dampak Material</h1>
        <p class="subtle mb-0">Nilai acuan limbah &amp; karbon per item untuk tiap material. Dipakai untuk mengisi faktor produk otomatis.</p>
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

<div class="admin-grid" style="grid-template-columns:1.5fr 1fr; align-items:start">
    <div class="admin-table-scroll">
        <table class="table">
            <thead>
                <tr><th>Material</th><th>Limbah (kg/item)</th><th>Karbon (kg CO₂/item)</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($factors as $f)
                    <tr>
                        <td>
                            <form action="{{ route('admin.impact.update', $f->id) }}" method="POST" id="fupd-{{ $f->id }}" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                                @csrf @method('PUT')
                                <input type="text" name="material_type" value="{{ $f->material_type }}" class="form-control form-control-sm" style="width:150px">
                        </td>
                        <td><input type="number" step="0.01" min="0" name="waste_per_item" value="{{ $f->waste_per_item }}" class="form-control form-control-sm" style="width:100px" form="fupd-{{ $f->id }}"></td>
                        <td><input type="number" step="0.01" min="0" name="carbon_per_item" value="{{ $f->carbon_per_item }}" class="form-control form-control-sm" style="width:100px" form="fupd-{{ $f->id }}"></td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-primary btn-sm" type="submit" form="fupd-{{ $f->id }}">Simpan</button>
                                </form>
                                <form action="{{ route('admin.impact.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Hapus material ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Belum ada material. Tambahkan di panel kanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <section class="panel">
        <div class="panel-heading"><div><div class="eyebrow">Tambah</div><h2>Material baru</h2></div></div>
        <form action="{{ route('admin.impact.store') }}" method="POST">
            @csrf
            <div class="mb-2"><label class="form-label" style="font-size:11px;font-weight:800">Nama material</label><input class="form-control" name="material_type" value="{{ old('material_type') }}" required></div>
            <div class="mb-2"><label class="form-label" style="font-size:11px;font-weight:800">Limbah (kg/item)</label><input class="form-control" type="number" step="0.01" min="0" name="waste_per_item" value="{{ old('waste_per_item', '1.20') }}" required></div>
            <div class="mb-3"><label class="form-label" style="font-size:11px;font-weight:800">Karbon (kg CO₂/item)</label><input class="form-control" type="number" step="0.01" min="0" name="carbon_per_item" value="{{ old('carbon_per_item', '2.70') }}" required></div>
            @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <button class="btn-brand" type="submit"><i class="fas fa-plus"></i> Tambah material</button>
        </form>
    </section>
</div>
@endsection
