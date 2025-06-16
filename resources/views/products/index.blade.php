@extends('seller.dashboard')

@section('content')
<div class="container">
    <h1>Product List</h1>

    <!-- Menampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tombol untuk menambahkan produk baru -->
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>

    <!-- Daftar Produk -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Category</th>
                <th>Images</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->slug }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->category }}</td>
                <td>
                    <!-- Menampilkan gambar utama -->
                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="Main Image" width="100">

                    <!-- Menampilkan gambar galeri -->
                    <div>
                        @if($product->image_gallery)
                            @php
                                $galleryImages = json_decode($product->image_gallery);
                            @endphp
                            @foreach($galleryImages as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" width="80" class="m-1">
                            @endforeach
                        @endif
                    </div>
                </td>
                <td>{{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}</td>
                <td>
                    <a href="{{ route('products.edit', $product->id_products) }}" class="btn btn-warning">Edit</a>

                    <!-- Form untuk menghapus produk -->
                    <form action="{{ route('products.destroy', $product->id_products) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $products->links() }}
</div>
@endsection
