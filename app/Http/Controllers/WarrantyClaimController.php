<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WarrantyClaim;
use Illuminate\Http\Request;

class WarrantyClaimController extends Controller
{
    /**
     * Daftar klaim garansi milik customer yang sedang login.
     */
    public function index()
    {
        $customerId = auth('customer')->id();

        $claims = WarrantyClaim::with(['product', 'seller', 'order'])
            ->where('customer_id', $customerId)
            ->orderByDesc('id_claims')
            ->get();

        return view('customer.claims.index', compact('claims'));
    }

    /**
     * Form pengajuan klaim: menampilkan item dari pesanan yang sudah diterima
     * dan belum pernah diklaim.
     */
    public function create(Request $request)
    {
        $customerId = auth('customer')->id();

        $claimedItemIds = WarrantyClaim::where('customer_id', $customerId)
            ->where('status', '!=', 'Rejected')
            ->pluck('order_item_id')
            ->all();

        $orders = Order::with(['items.product'])
            ->where('customer_id', $customerId)
            ->whereIn('status', WarrantyClaim::ELIGIBLE_ORDER_STATUSES)
            ->when($request->filled('order'), fn ($query) => $query->where('id_orders', $request->integer('order')))
            ->orderByDesc('id_orders')
            ->get();

        $items = $orders->flatMap(function ($order) use ($claimedItemIds) {
            return $order->items
                ->reject(fn ($item) => in_array($item->id, $claimedItemIds, true))
                ->map(fn ($item) => ['order' => $order, 'item' => $item]);
        });

        return view('customer.claims.create', compact('items'));
    }

    /**
     * Simpan klaim garansi baru.
     */
    public function store(Request $request)
    {
        $customerId = auth('customer')->id();

        $data = $request->validate([
            'order_item_id' => ['required', 'integer'],
            'category' => ['required', 'in:kerusakan,jahitan,tidak_sesuai,lainnya'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'order_item_id.required' => 'Pilih salah satu produk yang ingin diklaim.',
            'description.min' => 'Ceritakan masalahnya minimal 10 karakter.',
        ]);

        $item = OrderItem::with('order')->findOrFail($data['order_item_id']);
        $order = $item->order;

        abort_unless($order && (int) $order->customer_id === (int) $customerId, 403);
        abort_unless(in_array($order->status, WarrantyClaim::ELIGIBLE_ORDER_STATUSES, true), 403);

        $hasActiveClaim = WarrantyClaim::where('order_item_id', $item->id)
            ->where('status', '!=', 'Rejected')
            ->exists();

        if ($hasActiveClaim) {
            return back()
                ->withErrors(['order_item_id' => 'Produk ini sudah punya klaim garansi yang sedang diproses.'])
                ->withInput();
        }

        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('warranty_claims', 'public')
            : null;

        $claim = WarrantyClaim::create([
            'order_id' => $order->id_orders,
            'order_item_id' => $item->id,
            'customer_id' => $customerId,
            'seller_id' => $item->seller_id,
            'product_id' => $item->product_id,
            'category' => $data['category'],
            'description' => $data['description'],
            'photo' => $photoPath,
            'status' => 'Submitted',
        ]);

        return redirect()
            ->route('customer.claims.show', $claim->id_claims)
            ->with('success', 'Klaim garansi terkirim. Seller akan menindaklanjuti.');
    }

    /**
     * Detail satu klaim milik customer.
     */
    public function show($id)
    {
        $customerId = auth('customer')->id();

        $claim = WarrantyClaim::with(['product', 'seller', 'order', 'item'])
            ->where('customer_id', $customerId)
            ->findOrFail($id);

        return view('customer.claims.show', compact('claim'));
    }
}
