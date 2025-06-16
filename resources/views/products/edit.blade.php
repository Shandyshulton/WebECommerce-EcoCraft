@extends('seller.dashboard')

@section('content')
<h2>Edit Product</h2>

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Name*</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="form-control @error('slug') is-invalid @enderror">
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Price (IDR)*</label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" required min="0" step="0.01">
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Category*</label>
        <input type="text" name="category" value="{{ old('category', $product->category) }}" class="form-control @error('category') is-invalid @enderror" required>
        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Material Type*</label>
        <input type="text" name="material_type" value="{{ old('material_type', $product->material_type) }}" class="form-control @error('material_type') is-invalid @enderror" required>
        @error('material_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Images</label>
        <input type="file" name="images[]" id="images" multiple accept="image/*" class="form-control @error('images') is-invalid @enderror">
        @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror

        <div id="preview-images" class="mt-3 d-flex flex-wrap gap-2">
            @foreach ($product->images as $image)
                <div style="width:100px; height:100px; position:relative; cursor:move;">
                    <img src="{{ asset('storage/'.$image->image_path) }}" style="width:100%; height:100%; object-fit:cover;">
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="in_stock" id="in_stock" class="form-check-input" {{ old('in_stock', $product->in_stock) ? 'checked' : '' }}>
        <label for="in_stock" class="form-check-label">In Stock</label>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
        <label for="is_active" class="form-check-label">Is Active</label>
    </div>

    <button class="btn btn-primary">Update Product</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    const inputImages = document.getElementById('images');
    const previewImages = document.getElementById('preview-images');

    inputImages.addEventListener('change', function () {
        // Clear existing previews (except existing images)
        previewImages.innerHTML = '';

        Array.from(this.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgWrapper = document.createElement('div');
                imgWrapper.style.width = '100px';
                imgWrapper.style.height = '100px';
                imgWrapper.style.position = 'relative';
                imgWrapper.style.cursor = 'move';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';

                imgWrapper.appendChild(img);
                previewImages.appendChild(imgWrapper);
            };
            reader.readAsDataURL(file);
        });

        new Sortable(previewImages, {
            animation: 150,
            ghostClass: 'sortable-ghost',
        });
    });
</script>
@endsection
