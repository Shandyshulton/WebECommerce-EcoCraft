<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class TrackController extends Controller
{
    public function show()
    {
        $customerId = Auth::guard('customer')->id();
        $orders = Order::with('items')->where('customer_id', $customerId)->latest()->get();

        $stats = [
            'total' => $orders->count(),
            'active' => $orders->whereIn('status', ['Processing', 'Shipped'])->count(),
            'spent' => $orders->sum('total'),
        ];

        return view('track.track', compact('orders', 'stats'));
    }

}
