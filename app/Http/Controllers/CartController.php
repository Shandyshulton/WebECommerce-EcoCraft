<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id_products', array_keys($cart))
            ->where('status', 'approved')
            ->where('is_active', true)
            ->get();

        $items = $products->map(fn ($product) => [
            'product' => $product,
            'quantity' => (int) ($cart[$product->getKey()] ?? 0),
            'subtotal' => $product->price * (int) ($cart[$product->getKey()] ?? 0),
        ])->filter(fn ($item) => $item['quantity'] > 0);

        // Pilihan yang tersimpan menentukan mana yang ikut dihitung. Bila belum
        // ada pilihan, semua item dianggap terpilih.
        $selectedIds = $this->selectedIds($items);

        return view('cart.cart', [
            'items' => $items,
            'selectedIds' => $selectedIds,
            'total' => $items
                ->filter(fn ($item) => in_array($item['product']->id_products, $selectedIds, true))
                ->sum('subtotal'),
        ]);
    }

    /**
     * Item yang ikut di-checkout. Tanpa pilihan tersimpan, semua item dipakai.
     *
     * @param  \Illuminate\Support\Collection|null  $items
     * @return array<int>
     */
    private function selectedIds($items = null): array
    {
        $items ??= $this->cartItems();

        $selection = session('cart_selection');

        if (is_array($selection) && $selection !== []) {
            // Buang id yang sudah tidak ada lagi di keranjang.
            $available = $items->pluck('product.id_products')->map(fn ($id) => (int) $id)->all();
            $filtered = array_values(array_intersect(array_map('intval', $selection), $available));

            if ($filtered !== []) {
                return $filtered;
            }
        }

        return $items->pluck('product.id_products')->map(fn ($id) => (int) $id)->all();
    }

    /**
     * Isi keranjang yang benar-benar akan diproses checkout.
     *
     * @return array<int|string, int>
     */
    private function cartItems(): array
    {
        $cart = session('cart', []);
        $selection = session('cart_selection');

        if (! is_array($selection) || $selection === []) {
            return $cart;
        }

        return array_intersect_key($cart, array_flip(array_map('intval', $selection)));
    }

    /**
     * Simpan pilihan item, lalu lanjut ke halaman checkout.
     */
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer'],
        ]);

        $cart = session('cart', []);
        $selected = array_values(array_intersect(
            array_map('intval', $data['product_ids']),
            array_map('intval', array_keys($cart))
        ));

        if ($selected === []) {
            return redirect()->route('cart.show')->with('error', 'Pilih minimal satu produk untuk di-checkout.');
        }

        session(['cart_selection' => $selected]);

        return redirect()->route('checkout.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
            'buy_now' => ['nullable', 'boolean'],
        ]);

        Product::where('status', 'approved')->where('is_active', true)->findOrFail($data['product_id']);
        $cart = session('cart', []);
        $cart[$data['product_id']] = ($cart[$data['product_id']] ?? 0) + $data['quantity'];
        session(['cart' => $cart]);

        // Beli langsung: khusus customer login, langsung ke checkout.
        if ($request->boolean('buy_now') && Auth::guard('customer')->check()) {
            // Hanya produk ini yang dibeli, bukan seluruh isi keranjang.
            session(['cart_selection' => [(int) $data['product_id']]]);

            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.show')->with('success', 'Produk ditambahkan ke cart.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1']]);
        $cart = session('cart', []);
        $cart[$product->getKey()] = $data['quantity'];
        session(['cart' => $cart]);

        return redirect()->route('cart.show');
    }

    public function destroy(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->getKey()]);
        session(['cart' => $cart]);

        return redirect()->route('cart.show');
    }

    /**
     * Hapus beberapa item sekaligus (bulk select).
     */
    public function destroySelected(Request $request)
    {
        $data = $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer'],
        ]);

        $cart = session('cart', []);
        foreach ($data['product_ids'] as $id) {
            unset($cart[$id]);
        }
        session(['cart' => $cart]);

        // Item yang sudah tidak ada tidak boleh tertinggal di pilihan.
        if (is_array(session('cart_selection'))) {
            $remaining = array_values(array_intersect(
                array_map('intval', session('cart_selection')),
                array_map('intval', array_keys($cart))
            ));
            session(['cart_selection' => $remaining]);
        }

        return redirect()->route('cart.show')->with('success', count($data['product_ids']).' item dihapus dari keranjang.');
    }
}
