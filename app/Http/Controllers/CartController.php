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

        return view('cart.cart', ['items' => $items, 'total' => $items->sum('subtotal')]);
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

        return redirect()->route('cart.show')->with('success', count($data['product_ids']).' item dihapus dari keranjang.');
    }
}
