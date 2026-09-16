<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\RewardService;
use App\Services\ShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected RewardService $rewards;
    protected ShipmentService $shipments;

    public function __construct(RewardService $rewards, ShipmentService $shipments)
    {
        $this->rewards = $rewards;
        $this->shipments = $shipments;
    }

    public function index(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();

        $query = Order::with('items.product')
            ->whereHas('items', fn ($q) => $q->where('seller_id', $sellerId));

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Statistik ringkas (semua order seller ini)
        $base = Order::whereHas('items', fn ($q) => $q->where('seller_id', $sellerId));
        $stats = [
            'total' => (clone $base)->count(),
            'processing' => (clone $base)->where('status', 'Processing')->count(),
            'shipped' => (clone $base)->where('status', 'Shipped')->count(),
            'revenue' => (clone $base)->sum('total'),
        ];

        return view('order.index', compact('orders', 'stats'));
    }

    public function create()
    {
        $products = Product::where('seller_id', Auth::guard('seller')->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('order.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $sellerId = Auth::guard('seller')->id();

        $product = Product::where('seller_id', $sellerId)
            ->whereKey($data['product_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $order = DB::transaction(function () use ($data, $product, $sellerId) {
            $quantity = (int) $data['quantity'];
            $subtotal = $product->price * $quantity;

            $order = Order::create([
                ...collect($data)->except(['product_id', 'quantity'])->toArray(),
                'order_number' => $this->orderNumber(),
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => 'Hold',
            ]);

            $order->items()->create([
                'product_id' => $product->getKey(),
                'seller_id' => $sellerId,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            // Siapkan data pengiriman untuk pesanan yang dibuat manual ini.
            $this->shipments->syncForOrder($order);

            return $order;
        });

        return redirect()->route('order.index')->with('success', "Order {$order->order_number} berhasil dibuat.");
    }

    public function edit(Order $order)
    {
        $order = $this->sellerOrder($order);
        $products = Product::where('seller_id', Auth::guard('seller')->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $order->load('items');

        return view('order.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $order = $this->sellerOrder($order);
        $data = $this->validated($request);
        $sellerId = Auth::guard('seller')->id();

        $product = Product::where('seller_id', $sellerId)
            ->whereKey($data['product_id'])
            ->where('is_active', true)
            ->firstOrFail();

        DB::transaction(function () use ($data, $order, $product, $sellerId) {
            $quantity = (int) $data['quantity'];
            $subtotal = $product->price * $quantity;

            $order->update([
                ...collect($data)->except(['product_id', 'quantity'])->toArray(),
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            $order->items()->delete();
            $order->items()->create([
                'product_id' => $product->getKey(),
                'seller_id' => $sellerId,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        });

        if ($order->status === 'Delivered' && $order->customer_id) {
            $customer = $order->customer;
            if ($customer) {
                $this->rewards->grantMilestoneVouchers($customer);
            }
        }

        return redirect()->route('order.index')->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $this->sellerOrder($order)->delete();

        return redirect()->route('order.index')->with('success', 'Order berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_province' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'shipping_method' => ['required', 'in:Reguler,Express,Sameday'],
            'payment_method' => ['required', 'in:COD,Transfer Bank,QRIS'],
            'status' => ['nullable', 'in:Hold,Processing,Shipped,Delivered,Cancelled'],
        ]);
    }

    private function sellerOrder(Order $order): Order
    {
        abort_unless(
            $order->items()->where('seller_id', Auth::guard('seller')->id())->exists(),
            404
        );

        return $order;
    }

    private function orderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
