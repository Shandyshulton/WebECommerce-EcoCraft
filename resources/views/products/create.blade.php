@extends('seller.dashboard')

@section('breadcrumb')<a href="{{ route('products.index') }}">Katalog Produk</a><span class="current">Tambah Produk</span>@endsection

@section('content')
<div class="seller-page seller-form">
    <div class="seller-toolbar">
        <div>
            <div class="eyebrow">Katalog toko</div>
            <h1>Tambah Produk</h1>
            <p class="subtle mb-0">Lengkapi detail karya, media, dan faktor dampaknya.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-icon edit"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-card">
            <div class="form-card-head"><i class="fas fa-circle-info"></i> Informasi Produk</div>
            <div class="form-grid">
                <div class="field field-full">
                    <label for="name">Nama produk</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required oninput="generateSlug()">
                </div>
                <div class="field field-full">
                    <label for="slug">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" readonly>
                    <small>Dibuat otomatis dari nama produk.</small>
                </div>
                <div class="field field-full">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-head"><i class="fas fa-image"></i> Media</div>
            <div class="form-grid">
                <div class="field">
                    <label for="image_url">Gambar utama</label>
                    <input type="file" id="image_url" name="image_url" required onchange="previewMainImage(event)">
                    <div class="preview-image" id="mainImagePreview"></div>
                </div>
                <div class="field">
                    <label for="image_gallery">Galeri (opsional, bisa lebih dari 1)</label>
                    <input type="file" id="image_gallery" name="image_gallery[]" multiple accept="image/*" onchange="previewGalleryImages(event)">
                    <small>Tekan Ctrl/Cmd untuk memilih beberapa gambar sekaligus.</small>
                    <div class="preview-image" id="galleryPreview"></div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-head"><i class="fas fa-tag"></i> Harga, Stok &amp; Kategori</div>
            <div class="form-grid">
                <div class="field">
                    <label for="price_display">Harga</label>
                    <div class="rupiah-input">
                        <span class="rupiah-prefix">Rp</span>
                        <input type="text" id="price_display" inputmode="numeric" value="{{ old('price') }}" placeholder="0" data-rupiah>
                    </div>
                    <input type="hidden" id="price" name="price" value="{{ old('price') }}" data-rupiah-value>
                </div>
                <div class="field">
                    <label for="quantity">Jumlah stok</label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" min="0" required>
                </div>
                <div class="field">
                    <label for="category">Kategori</label>
                    <select id="category" name="category" required>
                        <option value="">Pilih kategori</option>
                        @foreach(['Electronics Accessories','Furniture','Clothing & Accessories','Home Decor','Books','Toys'] as $cat)
                            <option value="{{ $cat }}" @selected(old('category')===$cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="in_stock">Ketersediaan</label>
                    <select id="in_stock" name="in_stock" required>
                        <option value="1" @selected(old('in_stock')==='1')>Tersedia</option>
                        <option value="0" @selected(old('in_stock')==='0')>Habis</option>
                    </select>
                </div>
                <div class="field">
                    <label for="is_active">Status tampil</label>
                    <select id="is_active" name="is_active" required>
                        <option value="1" @selected(old('is_active')==='1')>Aktif</option>
                        <option value="0" @selected(old('is_active')==='0')>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-head"><i class="fas fa-leaf"></i> Dampak Lingkungan</div>
            <div class="form-grid">
                <div class="field">
                    <label for="material_type">Material</label>
                    @if($materials->isNotEmpty())
                        <select id="material_type" name="material_type" data-material-select required>
                            <option value="">Pilih material</option>
                            @foreach($materials as $m)
                                <option value="{{ $m->material_type }}" data-waste="{{ $m->waste_per_item }}" data-carbon="{{ $m->carbon_per_item }}" @selected(old('material_type')===$m->material_type)>{{ $m->material_type }}</option>
                            @endforeach
                            <option value="__other" @selected(old('material_type')==='__other')>Lainnya…</option>
                        </select>
                        <input type="text" id="material_type_other" name="material_type_other" class="mt-2" placeholder="Material lain" style="display:none">
                    @else
                        <input type="text" id="material_type" name="material_type" value="{{ old('material_type') }}">
                    @endif
                </div>
                <div class="field">
                    <label for="waste_factor">Faktor limbah (kg/item)</label>
                    <input type="number" step="0.01" min="0" id="waste_factor" name="waste_factor" value="{{ old('waste_factor', '1.20') }}" data-waste-input readonly>
                    <small>Terisi otomatis dari material yang dipilih.</small>
                </div>
                <div class="field">
                    <label for="carbon_factor">Faktor karbon (kg CO₂/item)</label>
                    <input type="number" step="0.01" min="0" id="carbon_factor" name="carbon_factor" value="{{ old('carbon_factor', '2.70') }}" data-carbon-input readonly>
                    <small>Terisi otomatis dari material yang dipilih.</small>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('products.index') }}" class="btn-icon edit">Batal</a>
            <button type="submit" class="btn-brand"><i class="fas fa-plus"></i> Simpan Produk</button>
        </div>
    </form>
</div>

<script>
    (function(){
        var sel = document.querySelector('[data-material-select]');
        if (sel) {
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
        }
    })();

    function generateSlug() {
        const name = document.getElementById('name').value;
        document.getElementById('slug').value = name.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '');
    }
    // Format input harga sebagai Rupiah; kirim angka murni via hidden field
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
</script>
@endsection
