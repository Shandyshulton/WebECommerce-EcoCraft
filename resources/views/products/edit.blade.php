@extends('seller.dashboard')

@section('breadcrumb')<a href="{{ route('products.index') }}">Katalog Produk</a><span class="current">Edit Produk</span>@endsection

@section('content')
<div class="seller-page seller-form">
    <div class="seller-toolbar">
        <div>
            <div class="eyebrow">Katalog toko</div>
            <h1>Edit Produk</h1>
            <p class="subtle mb-0">Perbarui detail produk. Perubahan akan diverifikasi ulang oleh admin.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-icon edit"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('products.update', $product->id_products) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-card">
            <div class="form-card-head"><i class="fas fa-circle-info"></i> Informasi Produk</div>
            <div class="form-grid">
                <div class="field field-full">
                    <label>Nama produk</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required oninput="generateSlug()">
                </div>
                <div class="field field-full">
                    <label>Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" readonly>
                </div>
                <div class="field field-full">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-head"><i class="fas fa-image"></i> Media</div>
            <div class="form-grid">
                <div class="field">
                    <label>Gambar utama</label>
                    @if($product->image_url)
                        <div class="gallery-manage">
                            <div class="gallery-thumb">
                                <img src="{{ asset('storage/'.$product->image_url) }}" alt="">
                                <button type="button" class="gallery-del" title="Hapus gambar utama"
                                    onclick="event.preventDefault(); if(confirm('Hapus gambar utama?')){document.getElementById('del-main').submit();}">&times;</button>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="image_url" onchange="previewMainImage(event)">
                    <small>Kosongkan jika tidak ingin mengganti.</small>
                    <div class="preview-image" id="mainImagePreview"></div>
                </div>
                <div class="field">
                    <label>Galeri (opsional, bisa lebih dari 1)</label>
                    @php($existingGallery = is_array($product->image_gallery) ? $product->image_gallery : (json_decode($product->image_gallery ?? '[]', true) ?: []))
                    @if(count($existingGallery))
                        <div class="gallery-manage">
                            @foreach($existingGallery as $g)
                                <div class="gallery-thumb">
                                    <img src="{{ asset('storage/'.$g) }}" alt="">
                                    <button type="button" class="gallery-del" title="Hapus gambar"
                                        onclick="event.preventDefault(); if(confirm('Hapus gambar ini?')){document.getElementById('delg-{{ $loop->index }}').submit();}">&times;</button>
                                </div>
                            @endforeach
                        </div>
                        <small>Gambar baru akan ditambahkan ke galeri yang sudah ada.</small>
                    @endif
                    <input type="file" name="image_gallery[]" multiple accept="image/*" onchange="previewGalleryImages(event)">
                    <div class="preview-image" id="galleryPreview"></div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-head"><i class="fas fa-tag"></i> Harga, Stok &amp; Kategori</div>
            <div class="form-grid">
                <div class="field">
                    <label>Harga</label>
                    <div class="rupiah-input">
                        <span class="rupiah-prefix">Rp</span>
                        <input type="text" id="price_display" inputmode="numeric" value="{{ (int) old('price', $product->price) }}" data-rupiah>
                    </div>
                    <input type="hidden" id="price" name="price" value="{{ (int) old('price', $product->price) }}" data-rupiah-value>
                </div>
                <div class="field">
                    <label>Jumlah stok</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" min="0" required>
                </div>
                <div class="field">
                    <label>Kategori</label>
                    <input type="text" name="category" value="{{ old('category', $product->category) }}" required>
                </div>
                <div class="field">
                    <label>Ketersediaan</label>
                    <select name="in_stock">
                        <option value="1" @selected(old('in_stock', $product->in_stock))>Tersedia</option>
                        <option value="0" @selected(!old('in_stock', $product->in_stock))>Habis</option>
                    </select>
                </div>
                <div class="field">
                    <label>Status tampil</label>
                    <select name="is_active">
                        <option value="1" @selected(old('is_active', $product->is_active))>Aktif</option>
                        <option value="0" @selected(!old('is_active', $product->is_active))>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-head"><i class="fas fa-leaf"></i> Dampak Lingkungan</div>
            <div class="form-grid">
                <div class="field">
                    <label>Material</label>
                    @if($materials->isNotEmpty())
                        <select name="material_type" data-material-select required>
                            @foreach($materials as $m)
                                <option value="{{ $m->material_type }}" data-waste="{{ $m->waste_per_item }}" data-carbon="{{ $m->carbon_per_item }}" @selected(old('material_type', $product->material_type)===$m->material_type)>{{ $m->material_type }}</option>
                            @endforeach
                            <option value="__other" @selected(old('material_type', $product->material_type) && !$materials->pluck('material_type')->contains($product->material_type))>Lainnya…</option>
                        </select>
                        <input type="text" name="material_type_other" id="material_type_other" class="mt-2" placeholder="Material lain" value="{{ $materials->pluck('material_type')->contains($product->material_type) ? '' : $product->material_type }}" style="{{ $materials->pluck('material_type')->contains($product->material_type) ? 'display:none' : '' }}">
                    @else
                        <input type="text" name="material_type" value="{{ old('material_type', $product->material_type) }}" required>
                    @endif
                </div>
                <div class="field">
                    <label>Faktor limbah (kg/item)</label>
                    <input type="number" step="0.01" min="0" name="waste_factor" value="{{ old('waste_factor', $product->waste_factor) }}" data-waste-input readonly>
                    <small>Terisi otomatis dari material.</small>
                </div>
                <div class="field">
                    <label>Faktor karbon (kg CO₂/item)</label>
                    <input type="number" step="0.01" min="0" name="carbon_factor" value="{{ old('carbon_factor', $product->carbon_factor) }}" data-carbon-input readonly>
                    <small>Terisi otomatis dari material.</small>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('products.index') }}" class="btn-icon edit">Batal</a>
            <button type="submit" class="btn-brand"><i class="fas fa-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

@if($product->image_url)
    <form id="del-main" action="{{ route('products.main.delete', $product->id_products) }}" method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endif

@if(count($existingGallery ?? []))
    @foreach($existingGallery as $g)
        <form id="delg-{{ $loop->index }}" action="{{ route('products.gallery.delete', $product->id_products) }}" method="POST" class="d-none">
            @csrf @method('DELETE')
            <input type="hidden" name="path" value="{{ $g }}">
        </form>
    @endforeach
@endif

<script>
    function generateSlug() {
        const name = document.getElementById('name').value;
        document.getElementById('slug').value = name.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
    }
    (function(){
        var disp = document.querySelector('[data-rupiah]');
        var hidden = document.querySelector('[data-rupiah-value]');
        if (!disp || !hidden) return;
        function fmt(v){ v = (v||'').toString().replace(/\D/g,''); return v ? Number(v).toLocaleString('id-ID') : ''; }
        function sync(){ var raw = disp.value.replace(/\D/g,''); hidden.value = raw; disp.value = fmt(disp.value); }
        disp.value = fmt(disp.value);
        disp.addEventListener('input', sync);
        sync();
    })();
    function galleryThumb(file, onRemove) {
        const wrap = document.createElement('div');
        wrap.className = 'gallery-thumb';
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'gallery-del';
        btn.title = 'Hapus gambar';
        btn.innerHTML = '&times;';
        btn.addEventListener('click', onRemove);
        wrap.appendChild(img);
        wrap.appendChild(btn);
        return wrap;
    }
    function previewMainImage(event) {
        const input = event.target;
        const preview = document.getElementById('mainImagePreview');
        preview.innerHTML = '';
        if (!input.files[0]) return;
        preview.appendChild(galleryThumb(input.files[0], function () {
            input.value = '';
            preview.innerHTML = '';
        }));
    }
    function previewGalleryImages(event) {
        const input = event.target;
        const preview = document.getElementById('galleryPreview');
        let files = Array.from(input.files);
        function render() {
            preview.innerHTML = '';
            files.forEach((file) => {
                preview.appendChild(galleryThumb(file, function () {
                    files = files.filter((f) => f !== file);
                    const dt = new DataTransfer();
                    files.forEach((f) => dt.items.add(f));
                    input.files = dt.files;
                    render();
                }));
            });
        }
        render();
    }
    (function(){
        var sel = document.querySelector('[data-material-select]');
        if (!sel) return;
        var other = document.getElementById('material_type_other');
        var wasteInput = document.querySelector('[data-waste-input]');
        var carbonInput = document.querySelector('[data-carbon-input]');
        sel.addEventListener('change', function(){
            var opt = sel.options[sel.selectedIndex];
            if (sel.value === '__other') { if (other) other.style.display = 'block'; return; }
            if (other) other.style.display = 'none';
            if (opt && opt.dataset.waste) wasteInput.value = opt.dataset.waste;
            if (opt && opt.dataset.carbon) carbonInput.value = opt.dataset.carbon;
        });
    })();
</script>
@endsection
