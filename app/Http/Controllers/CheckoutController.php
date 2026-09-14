<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerVoucher;
use App\Models\Order;
use App\Models\Product;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    protected RewardService $rewards;

    public function __construct(RewardService $rewards)
    {
        $this->rewards = $rewards;
    }

    public function index()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id_products', array_keys($cart))->where('status', 'approved')->where('is_active', true)->get();
        $items = $products->map(fn ($product) => ['product' => $product, 'quantity' => (int) $cart[$product->getKey()], 'subtotal' => $product->price * (int) $cart[$product->getKey()]]);

        if ($items->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Cart masih kosong.');
        }

        $customer = Auth::guard('customer')->user();
        $subtotal = (float) $items->sum('subtotal');

        return view('checkout.checkout', [
            'items' => $items,
            'total' => $subtotal,
            'subtotal' => $subtotal,
            'availableVouchers' => CustomerVoucher::with('voucher')
                ->where('customer_id', $customer->getKey())
                ->where('status', 'available')
                ->get(),
            'coinBalance' => (int) $customer->coin_balance,
            'coinValue' => $this->rewards->coinValue(),
            'maxDiscount' => $this->rewards->maxDiscount($subtotal),
        ]);
    }

    public function preview(Request $request)
    {
        $data = $request->validate([
            'voucher_code' => ['nullable', 'string', 'max:50'],
            'customer_voucher_id' => ['nullable', 'integer'],
            'coins_used' => ['nullable', 'integer', 'min:0'],
        ]);

        $customer = Auth::guard('customer')->user();
        $cart = session('cart', []);
        $subtotal = (float) Product::whereIn('id_products', array_keys($cart))
            ->where('status', 'approved')->where('is_active', true)->get()
            ->sum(fn ($product) => $product->price * (int) $cart[$product->getKey()]);

        $quote = $this->quote(
            $customer,
            $data['customer_voucher_id'] ?? null,
            $data['voucher_code'] ?? null,
            (int) ($data['coins_used'] ?? 0),
            $subtotal,
            false
        );

        return response()->json([
            'subtotal' => $quote['subtotal'],
            'voucher_discount' => $quote['voucher_discount'],
            'coin_discount' => $quote['coin_discount'],
            'discount_total' => $quote['discount_total'],
            'coins_used' => $quote['coins_used'],
            'max_coins' => $quote['max_coins'],
            'coins_earned' => $quote['coins_earned'],
            'total' => $quote['total'],
            'voucher_error' => $quote['voucher_error'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_province' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'shipping_method' => ['required', 'in:Reguler,Express,Sameday'],
            'payment_method' => ['required', 'in:COD,Transfer Bank,QRIS'],
            'voucher_code' => ['nullable', 'string', 'max:50'],
            'customer_voucher_id' => ['nullable', 'integer'],
            'coins_used' => ['nullable', 'integer', 'min:0'],
        ]);

        $customer = Auth::guard('customer')->user();
        $cart = session('cart', []);
        $products = Product::whereIn('id_products', array_keys($cart))->where('status', 'approved')->where('is_active', true)->get();

        abort_if($products->isEmpty(), 422, 'Cart masih kosong.');

        $order = DB::transaction(function () use ($data, $customer, $cart, $products) {
            $customer = Customer::whereKey($customer->getKey())->lockForUpdate()->firstOrFail();

            $subtotal = (float) $products->sum(fn ($product) => $product->price * (int) $cart[$product->getKey()]);

            $quote = $this->quote(
                $customer,
                $data['customer_voucher_id'] ?? null,
                $data['voucher_code'] ?? null,
                (int) ($data['coins_used'] ?? 0),
                $subtotal,
                true
            );

            if ($quote['voucher_error']) {
                throw ValidationException::withMessages(['voucher_code' => $quote['voucher_error']]);
            }

            $order = Order::create([
                'customer_id' => $customer->getKey(),
                'customer_voucher_id' => optional($quote['customer_voucher'])->getKey(),
                'coupon_code' => optional($quote['voucher'])->code,
                'order_number' => $this->orderNumber(),
                'customer_name' => $customer->name_customers,
                'customer_email' => $customer->email,
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'shipping_city' => $data['shipping_city'],
                'shipping_province' => $data['shipping_province'],
                'shipping_postal_code' => $data['shipping_postal_code'],
                'shipping_method' => $data['shipping_method'],
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'voucher_discount' => $quote['voucher_discount'],
                'coins_used' => $quote['coins_used'],
                'coin_discount' => $quote['coin_discount'],
                'discount_total' => $quote['discount_total'],
                'total' => $quote['total'],
                'status' => 'Processing',
            ]);

            foreach ($products as $product) {
                $quantity = (int) $cart[$product->getKey()];
                $order->items()->create([
                    'product_id' => $product->getKey(),
                    'seller_id' => $product->seller_id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $quantity,
                ]);
            }

            if ($quote['coins_used'] > 0) {
                $this->rewards->redeemCoins($customer, $order, $quote['coins_used']);
            }

            if ($quote['customer_voucher']) {
                $this->rewards->markVoucherUsed($quote['customer_voucher'], $order);
            }

            $coinsEarned = $this->rewards->earnFromOrder($customer, $order);
            $order->update(['coins_earned' => $coinsEarned]);

            return $order;
        });

        session()->forget('cart');

        $message = "Order {$order->order_number} berhasil dibuat.";
        if ($order->coins_earned > 0) {
            $message .= " Kamu mendapat {$order->coins_earned} koin sirkular.";
        }

        return redirect()->route('track.track')->with('success', $message);
    }

    /**
     * Hitung potongan voucher + koin untuk sebuah subtotal.
     *
     * @return array{subtotal: float, voucher_discount: float, coins_used: int, coin_discount: float, discount_total: float, total: float, coins_earned: int, max_coins: int, voucher_error: ?string, customer_voucher: ?CustomerVoucher, voucher: ?\App\Models\Voucher}
     */
    private function quote(Customer $customer, ?int $customerVoucherId, ?string $voucherCode, int $requestedCoins, float $subtotal, bool $persist): array
    {
        $maxDiscount = $this->rewards->maxDiscount($subtotal);
        $coinValue = $this->rewards->coinValue();

        $voucherResult = $this->rewards->resolveVoucher($customer, $voucherCode, $customerVoucherId, $subtotal, $persist);
        $voucherDiscount = min((float) $voucherResult['discount'], $maxDiscount);

        $remaining = max(0.0, $maxDiscount - $voucherDiscount);
        $allowedCoins = (int) floor($remaining / $coinValue);
        $coinsUsed = max(0, min($requestedCoins, (int) $customer->coin_balance, $allowedCoins));
        $coinDiscount = $coinsUsed * $coinValue;

        $discountTotal = round($voucherDiscount + $coinDiscount, 2);
        $total = max(0, round($subtotal - $discountTotal, 2));

        return [
            'subtotal' => $subtotal,
            'voucher_discount' => $voucherDiscount,
            'coins_used' => $coinsUsed,
            'coin_discount' => $coinDiscount,
            'discount_total' => $discountTotal,
            'total' => $total,
            'coins_earned' => $this->rewards->coinsEarnedFor($subtotal),
            'max_coins' => $allowedCoins,
            'voucher_error' => $voucherResult['error'],
            'customer_voucher' => $voucherResult['customer_voucher'],
            'voucher' => $voucherResult['voucher'],
        ];
    }

    private function orderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
