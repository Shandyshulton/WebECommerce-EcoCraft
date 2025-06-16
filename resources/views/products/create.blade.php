<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create Product</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            padding: 20px;
            max-width: 960px;
            margin: 0 auto;
        }

        .card {
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border: none;
            margin-bottom: 50px;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 0.375rem;
            padding: 10px;
            border: 1px solid #ced4da;
            width: 100%;
            box-sizing: border-box;
            transition: 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: #5bc0de;
            box-shadow: 0 0 8px rgba(91, 188, 222, 0.5);
            outline: none;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 0.375rem;
            cursor: pointer;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0069d9;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .preview-image {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .preview-image img {
            width: 100px;
            height: auto;
            border-radius: 0.25rem;
            object-fit: cover;
        }

        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 0.375rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Styling untuk dua kolom dengan ukuran berbeda */
        .card-group {
            display: flex;
            gap: 40px;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }

        .card-price {
            flex: 0 0 35%;
            /* Fixed 35% lebar untuk price */
            min-width: 150px;
        }

        .card-other {
            flex: 1;
            /* Sisanya untuk kolom lain */
            min-width: 100px;
        }
    </style>
</head>

<body>
    @extends('seller.dashboard')

    @section('content')
    <div class="container">
        <h1>Create Product</h1>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row" style="display: flex; gap: 40px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 300px;">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" required
                                    oninput="generateSlug()" />
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" id="slug" name="slug" class="form-control" readonly required />
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <div style="flex: 1; min-width: 300px;">
                            <div class="form-group">
                                <label for="image_url">Main Image</label>
                                <input type="file" id="image_url" name="image_url" class="form-control" required
                                    onchange="previewMainImage(event)" />
                                <div class="preview-image" id="mainImagePreview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="image_gallery">Gallery Images</label>
                        <input type="file" id="image_gallery" name="image_gallery[]" class="form-control" multiple
                            onchange="previewGalleryImages(event)" />
                        <small class="form-text">Select multiple images for the gallery (optional)</small>
                        <div class="preview-image" id="galleryPreview"></div>
                    </div>
                </div>
            </div>

            <div class="card-group">
                <div class="card card-price">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" id="price" name="price" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" required />
                        </div>
                    </div>
                </div>


                <div class="card card-other">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="Electronics Accessories">Electronics Accessories</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Clothing & Accessories">Clothing & Accessories</option>
                                <option value="Home Decor">Home Decor</option>
                                <option value="Books">Books</option>
                                <option value="Toys">Toys</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="material_type">Material Type</label>
                            <input type="text" id="material_type" name="material_type" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="in_stock">In Stock</label>
                            <select id="in_stock" name="in_stock" class="form-control" required>
                                <option value="">Select Option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Is Active</label>
                            <select id="is_active" name="is_active" class="form-control" required>
                                <option value="">Select Option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Product</button>
        </form>
    </div>

    <script>
        function generateSlug() {
            const name = document.getElementById('name').value;
            const slug = name
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '');
            document.getElementById('slug').value = slug;
        }

        function previewMainImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('mainImagePreview');
            preview.innerHTML = '';
            if (file) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                preview.appendChild(img);
            }
        }

        function previewGalleryImages(event) {
            const files = event.target.files;
            const preview = document.getElementById('galleryPreview');

            Array.from(files).forEach((file) => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                preview.appendChild(img);
            });
        }
    </script>
    @endsection
</body>

</html>
