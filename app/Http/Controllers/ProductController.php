<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Menampilkan daftar produk yang sudah disetujui
    public function index()
    {
        $products = Product::where('status', 'approved')->paginate(10);
        return view('products.index', compact('products'));
    }

    // Menampilkan form untuk menambah produk
    public function create()
    {
        return view('products.create');
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'material_type' => 'required|string',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'in_stock' => 'required|in:0,1',
            'is_active' => 'required|in:0,1',
        ]);

        try {
            $product = new Product();
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->price = $request->price;
            $product->category = $request->category;
            $product->material_type = $request->material_type;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->in_stock = $request->in_stock;
            $product->is_active = $request->is_active;
            $product->seller_id = auth()->user()->id_sellers;

            // Simpan gambar utama
            if ($request->hasFile('image_url')) {
                $imagePath = $request->file('image_url')->store('product_images', 'public');
                $product->image_url = $imagePath;
            }

            // Simpan gambar galeri jika ada
            if ($request->hasFile('image_gallery')) {
                $galleryPaths = [];
                foreach ($request->file('image_gallery') as $image) {
                    $galleryPaths[] = $image->store('product_images/gallery', 'public');
                }
                $product->image_gallery = json_encode($galleryPaths);
            }

            // Set status menjadi pending
            $product->status = 'pending';

            $product->save();

            return redirect()->route('products.create')
                ->with('success', 'Produk berhasil dibuat! Menunggu verifikasi admin sebelum tampil di halaman.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    // Menampilkan form untuk mengedit produk
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Memperbarui data produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'price' => 'required|numeric',
            'category' => 'required|string',
            'material_type' => 'required|string',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->material_type = $request->material_type;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->in_stock = $request->has('in_stock');
        $product->is_active = $request->has('is_active');

        // Simpan gambar utama jika diubah
        if ($request->hasFile('image_url')) {
            if ($product->image_url) {
                Storage::delete('public/' . $product->image_url);
            }
            $product->image_url = $request->file('image_url')->store('product_images', 'public');
        }

        // Simpan ulang gambar galeri jika diubah
        if ($request->hasFile('image_gallery')) {
            if ($product->image_gallery) {
                foreach (json_decode($product->image_gallery) as $image) {
                    Storage::delete('public/' . $image);
                }
            }

            $galleryPaths = [];
            foreach ($request->file('image_gallery') as $image) {
                $galleryPaths[] = $image->store('product_images/gallery', 'public');
            }
            $product->image_gallery = json_encode($galleryPaths);
        }

        // Reset status ke pending agar perlu diverifikasi ulang
        $product->status = 'pending';

        $product->save();

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui! Menunggu verifikasi ulang admin.');
    }

    // Menghapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_url) {
            Storage::delete('public/' . $product->image_url);
        }

        if ($product->image_gallery) {
            foreach (json_decode($product->image_gallery) as $image) {
                Storage::delete('public/' . $image);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function show($id)
    {
        // Mengambil produk berdasarkan ID dan memuat relasi seller
        $product = Product::with('seller')->findOrFail($id);

        if (!$product) {
            abort(404); // Jika produk tidak ditemukan
        }
        
        // Mengambil data galeri gambar produk (jika ada)
        $image_gallery = json_decode($product->image_gallery, true);

        // Mengirim data produk ke view
        return view('products.show', [
            'product' => $product, // Menyertakan data produk
            'image_url' => $product->image_url, // Menyertakan gambar utama produk
            'image_gallery' => $image_gallery // Menyertakan galeri gambar produk
        ]);
    }



    public function showDashboard()
    {
        // Mengambil semua produk dari database
        $products = Product::all();

        // Mengirimkan data produk ke view dashboard.blade.php
        return view('customer.dashboard', compact('products'));
    }
}
