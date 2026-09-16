<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerVoucher;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;

class ImpactService
{
    /** Faktor cadangan bila produk belum punya nilai material. */
    public const DEFAULT_WASTE_FACTOR = 1.2;
    public const DEFAULT_CARBON_FACTOR = 2.7;

    /** Estimasi 1 pohon setara 10 kg limbah yang dialihkan. */
    public const KG_PER_TREE = 10.0;

    /**
     * Ringkasan dampak lingkungan seorang customer.
     *
     * @param  Collection|null  $orders  Pesanan yang sudah dimuat, untuk menghindari query ulang.
     */
    public function forCustomer(Customer $customer, ?Collection $orders = null): array
    {
        $orders ??= Order::with('items.product')
            ->where('customer_id', $customer->getKey())
            ->get();

        $items = $orders->flatMap(fn ($order) => $order->items);

        $waste = $this->wasteOf($items);
        $carbon = $this->carbonOf($items);

        return [
            'waste' => round($waste, 1),
            'carbon' => round($carbon, 1),
            'trees' => (int) floor($waste / self::KG_PER_TREE),
            'artisans' => $items->pluck('seller_id')->filter()->unique()->count(),
            'items' => (int) $items->sum('quantity'),
            'orders' => $orders->count(),
            'coins' => (int) $customer->coin_balance,
            'vouchers' => CustomerVoucher::where('customer_id', $customer->getKey())
                ->where('status', 'available')
                ->count(),
            'trend' => $this->monthlyTrend($orders),
        ];
    }

    /**
     * Total limbah yang dialihkan oleh seluruh pesanan di platform.
     */
    public function platformWaste(): float
    {
        return $this->wasteOf(OrderItem::with('product')->get());
    }

    private function monthlyTrend(Collection $orders, int $months = 6): array
    {
        $trend = [];

        for ($offset = $months - 1; $offset >= 0; $offset--) {
            $date = now()->subMonths($offset);

            $items = $orders
                ->filter(fn ($order) => $order->created_at->isSameMonth($date))
                ->flatMap(fn ($order) => $order->items);

            $trend[] = [
                'label' => $date->format("M 'y"),
                'waste' => round($this->wasteOf($items), 1),
                'carbon' => round($this->carbonOf($items), 1),
            ];
        }

        return $trend;
    }

    private function wasteOf(Collection $items): float
    {
        return (float) $items->sum(fn ($item) => $item->quantity * (float) (optional($item->product)->waste_factor ?? self::DEFAULT_WASTE_FACTOR));
    }

    private function carbonOf(Collection $items): float
    {
        return (float) $items->sum(fn ($item) => $item->quantity * (float) (optional($item->product)->carbon_factor ?? self::DEFAULT_CARBON_FACTOR));
    }
}
