<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\WarrantyClaim;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function show()
    {
        $customerId = Auth::guard('customer')->id();
        $orders = Order::with([
            'items',
            'shipments.courier',
            'shipments.seller',
            'shipments.events',
        ])->where('customer_id', $customerId)->latest()->get();

        $claimedItemIds = WarrantyClaim::where('customer_id', $customerId)
            ->where('status', '!=', 'Rejected')
            ->pluck('order_item_id')
            ->all();

        $stats = [
            'total' => $orders->count(),
            'active' => $orders->whereIn('status', ['Processing', 'Shipped'])->count(),
            'spent' => $orders->sum('total'),
        ];

        return view('track.track', compact('orders', 'stats', 'claimedItemIds'));
    }

}
