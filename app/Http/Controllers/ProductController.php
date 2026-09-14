<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Menampilkan daftar produk milik seller (dengan filter)
    public function index(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();

        $query = Product::where('seller_id', $sellerId);

        if ($search = trim((string) $request->get('q'))) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        // Daftar kategori milik seller untuk dropdown filter
        $categories = Product::where('seller_id', $sellerId)
            ->whereNotNull('category')->where('category', '!=', '')
            ->distinct()->orderBy('category')->pluck('category');

        // Statistik ringkas
        $base = Product::where('seller_id', $sellerId);
        $stats = [
            'total' => (clone $base)->count(),
            'active' => (clone $base)->where('status', 'approved')->where('is_active', true)->count(),
            'pending' => (clone $base)->where('status', 'pending')->count(),
            'out' => (clone $base)->where('in_stock', false)->count(),
        ];

        return view('products.index', compact('products', 'categories', 'stats'));
    }

    // Menampilkan form untuk menambah produk
    public function create()
    {
        return view('products.create', [
            'materials' => \App\Models\ImpactFactor::orderBy('material_type')->get(),
        ]);
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'material_type' => 'required|string',
            'material_type_other' => 'nullable|string|max:100',
            'waste_factor' => 'nullable|numeric|min:0|max:9999',
            'carbon_factor' => 'nullable|numeric|min:0|max:9999',
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
            $product->slug = $this->uniqueSlug($request->name);
            $product->price = $request->price;
            $product->category = $request->category;
            $material = $request->material_type === '__other'
                ? ($request->material_type_other ?: 'Lainnya')
                : $request->material_type;
            $product->material_type = $material;

            // Faktor: pakai input; jika kosong, ambil dari referensi material; jika tak ada, default.
            $ref = \App\Models\ImpactFactor::where('material_type', $material)->first();
            $product->waste_factor = $request->filled('waste_factor')
                ? $request->waste_factor
                : ($ref->waste_per_item ?? 1.20);
            $product->carbon_factor = $request->filled('carbon_factor')
                ? $request->carbon_factor
                : ($ref->carbon_per_item ?? 2.70);
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->in_stock = $request->in_stock;
            $product->is_active = $request->is_active;
            $product->seller_id = Auth::guard('seller')->id();

            // Simpan gambar utama
            if ($request->hasFile('image_url')) {
                $imagePath = $request->file('image_url')->store('product_images', 'public');
                $product->image_url = $imagePath;
            }

            // Simpan gambar galeri jika ada (bisa lebih dari 1)
            if ($request->hasFile('image_gallery')) {
                $galleryPaths = [];
                foreach ($request->file('image_gallery') as $image) {
                    $galleryPaths[] = $image->store('product_images/gallery', 'public');
                }
                $product->image_gallery = $galleryPaths;
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
        $product = Product::where('seller_id', Auth::guard('seller')->id())->findOrFail($id);
        return view('products.edit', [
            'product' => $product,
            'materials' => \App\Models\ImpactFactor::orderBy('material_type')->get(),
        ]);
    }

    // Memperbarui data produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'material_type' => 'required|string',
            'material_type_other' => 'nullable|string|max:100',
            'waste_factor' => 'nullable|numeric|min:0|max:9999',
            'carbon_factor' => 'nullable|numeric|min:0|max:9999',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image',
        ]);

        $product = Product::where('seller_id', Auth::guard('seller')->id())->findOrFail($id);
        $product->name = $request->name;
        $product->slug = $this->uniqueSlug($request->name, $product->getKey());
        $product->price = $request->price;
        $product->category = $request->category;
        $material = $request->material_type === '__other'
            ? ($request->material_type_other ?: 'Lainnya')
            : $request->material_type;
        $product->material_type = $material;

        $ref = \App\Models\ImpactFactor::where('material_type', $material)->first();
        $product->waste_factor = $request->filled('waste_factor')
            ? $request->waste_factor
            : ($ref->waste_per_item ?? $product->waste_factor ?? 1.20);
        $product->carbon_factor = $request->filled('carbon_factor')
            ? $request->carbon_factor
            : ($ref->carbon_per_item ?? $product->carbon_factor ?? 2.70);
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
        // Tambahkan gambar galeri baru (bisa lebih dari 1) ke galeri yang sudah ada
        if ($request->hasFile('image_gallery')) {
            $galleryPaths = $product->image_gallery ?? [];
            if (! is_array($galleryPaths)) {
                $galleryPaths = json_decode($galleryPaths, true) ?: [];
            }
            foreach ($request->file('image_gallery') as $image) {
                $galleryPaths[] = $image->store('product_images/gallery', 'public');
            }
            $product->image_gallery = $galleryPaths;
        }

        // Reset status ke pending agar perlu diverifikasi ulang
        $product->status = 'pending';

        $product->save();

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui! Menunggu verifikasi ulang admin.');
    }

    // Menghapus produk
    public function destroy($id)
    {
        $product = Product::where('seller_id', Auth::guard('seller')->id())->findOrFail($id);

        if ($product->image_url) {
            Storage::delete('public/' . $product->image_url);
        }

        if ($product->image_gallery) {
            $gallery = is_array($product->image_gallery) ? $product->image_gallery : (json_decode($product->image_gallery, true) ?: []);
            foreach ($gallery as $image) {
                Storage::delete('public/' . $image);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    // Hapus satu gambar dari galeri produk
    public function deleteGalleryImage(Request $request, $id)    {
        $product = Product::where('seller_id', Auth::guard('seller')->id())->findOrFail($id);

        $data = $request->validate(['path' => ['required', 'string']]);

        $gallery = is_array($product->image_gallery)
            ? $product->image_gallery
            : (json_decode($product->image_gallery ?? '[]', true) ?: []);

        if (($key = array_search($data['path'], $gallery, true)) !== false) {
            Storage::delete('public/' . $gallery[$key]);
            unset($gallery[$key]);
            $product->image_gallery = array_values($gallery);
            $product->save();

            return back()->with('success', 'Gambar galeri dihapus.');
        }

        return back()->with('error', 'Gambar tidak ditemukan.');
    }

    // Hapus gambar utama produk
    public function deleteMainImage($id)
    {
        $product = Product::where('seller_id', Auth::guard('seller')->id())->findOrFail($id);

        if ($product->image_url) {
            Storage::delete('public/' . $product->image_url);
            $product->image_url = null;
            $product->save();
            return back()->with('success', 'Gambar utama dihapus.');
        }

        return back()->with('error', 'Tidak ada gambar utama.');
    }

    public function show($id)
    {
        $product = Product::with('seller')
            ->where('status', 'approved')
            ->where('is_active', true)
            ->findOrFail($id);
        
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

    private function uniqueSlug(string $name, $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $counter = 2;

        while (Product::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where(Product::getKeyName(), '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
