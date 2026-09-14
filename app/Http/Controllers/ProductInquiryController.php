<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\ProductInquiryMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductInquiryController extends Controller
{
    /**
     * Daftar semua thread pertanyaan milik customer yang sedang login.
     */
    public function index()
    {
        $customer = auth('customer')->user();

        $inquiries = ProductInquiry::with(['product', 'seller', 'latestMessage'])
            ->where('customer_id', $customer->id_customers)
            ->orderByDesc('last_message_at')
            ->orderByDesc('id_inquiries')
            ->get();

        return view('customer.inquiries.index', compact('inquiries'));
    }

    /**
     * Mulai pertanyaan baru tentang sebuah produk (atau lanjutkan thread yang sudah ada),
     * lalu simpan pesan pertama.
     */
    public function store(Request $request, $productId)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $customer = auth('customer')->user();
        $product = Product::findOrFail($productId);

        $inquiry = DB::transaction(function () use ($customer, $product, $validated) {
            // Satu thread per (customer, product): gunakan yang ada bila sudah pernah bertanya.
            $inquiry = ProductInquiry::firstOrCreate(
                [
                    'customer_id' => $customer->id_customers,
                    'product_id' => $product->id_products,
                ],
                [
                    'seller_id' => $product->seller_id,
                    'subject' => $product->name,
                ]
            );

            // Pastikan seller_id ikut terisi bila thread lama belum punya.
            if (! $inquiry->seller_id && $product->seller_id) {
                $inquiry->seller_id = $product->seller_id;
            }

            ProductInquiryMessage::create([
                'inquiry_id' => $inquiry->id_inquiries,
                'sender_type' => 'customer',
                'sender_id' => $customer->id_customers,
                'body' => $validated['body'],
            ]);

            $inquiry->forceFill(['last_message_at' => now()])->save();

            return $inquiry;
        });

        return redirect()
            ->route('customer.inquiries.show', $inquiry->id_inquiries)
            ->with('success', 'Pertanyaanmu sudah terkirim ke admin EcoCraft.');
    }

    /**
     * Tampilkan satu thread milik customer dan tandai balasan admin sebagai terbaca.
     */
    public function show($id)
    {
        $customer = auth('customer')->user();

        $inquiry = ProductInquiry::with(['product', 'seller', 'messages'])
            ->where('customer_id', $customer->id_customers)
            ->findOrFail($id);

        // Tandai pesan dari seller sebagai sudah dibaca customer.
        ProductInquiryMessage::where('inquiry_id', $inquiry->id_inquiries)
            ->where('sender_type', 'seller')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('customer.inquiries.show', compact('inquiry'));
    }

    /**
     * Kirim balasan pada thread yang sudah ada.
     */
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $customer = auth('customer')->user();

        $inquiry = ProductInquiry::where('customer_id', $customer->id_customers)->findOrFail($id);

        ProductInquiryMessage::create([
            'inquiry_id' => $inquiry->id_inquiries,
            'sender_type' => 'customer',
            'sender_id' => $customer->id_customers,
            'body' => $validated['body'],
        ]);

        $inquiry->forceFill(['last_message_at' => now()])->save();

        return redirect()
            ->route('customer.inquiries.show', $inquiry->id_inquiries)
            ->with('success', 'Balasan terkirim.');
    }
}
