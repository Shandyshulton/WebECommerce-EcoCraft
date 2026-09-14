<?php

namespace App\Http\Controllers;

use App\Models\ProductInquiry;
use App\Models\ProductInquiryMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerInquiryController extends Controller
{
    /**
     * Inbox seller: semua thread pertanyaan produk milik seller yang login.
     */
    public function index(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();

        $query = ProductInquiry::with(['product', 'customer', 'latestMessage'])
            ->where('seller_id', $sellerId);

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('customer', fn ($c) => $c->where('name_customers', 'like', "%{$search}%"))
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->get('unread') === '1') {
            $query->whereHas('messages', function ($q) {
                $q->where('sender_type', 'customer')->whereNull('read_at');
            });
        }

        $inquiries = $query->orderByDesc('last_message_at')
            ->orderByDesc('id_inquiries')
            ->paginate(15)
            ->withQueryString();

        // Thread dengan pesan customer yang belum dibaca seller (badge).
        $unreadThreads = ProductInquiry::where('seller_id', $sellerId)
            ->whereHas('messages', function ($q) {
                $q->where('sender_type', 'customer')->whereNull('read_at');
            })->count();

        $totalThreads = ProductInquiry::where('seller_id', $sellerId)->count();

        return view('seller.inquiries.index', compact('inquiries', 'unreadThreads', 'totalThreads'));
    }

    /**
     * Tampilkan satu thread milik seller dan tandai pesan customer terbaca.
     */
    public function show($id)
    {
        $sellerId = Auth::guard('seller')->id();

        $inquiry = ProductInquiry::with(['product', 'customer', 'messages'])
            ->where('seller_id', $sellerId)
            ->findOrFail($id);

        ProductInquiryMessage::where('inquiry_id', $inquiry->id_inquiries)
            ->where('sender_type', 'customer')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('seller.inquiries.show', compact('inquiry'));
    }

    /**
     * Seller membalas thread.
     */
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $sellerId = Auth::guard('seller')->id();
        $inquiry = ProductInquiry::where('seller_id', $sellerId)->findOrFail($id);

        ProductInquiryMessage::create([
            'inquiry_id' => $inquiry->id_inquiries,
            'sender_type' => 'seller',
            'sender_id' => $sellerId,
            'body' => $validated['body'],
        ]);

        $inquiry->forceFill(['last_message_at' => now()])->save();

        return redirect()
            ->route('seller.inquiries.show', $inquiry->id_inquiries)
            ->with('success', 'Balasan terkirim ke customer.');
    }
}
