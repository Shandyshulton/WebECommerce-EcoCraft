<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;

class AdminController extends Controller
{
    // Tampilkan dashboard admin
    public function dashboard()
    {
        return view('layout.app'); // Pastikan view ini sesuai
    }

    // Tampilkan daftar seller berdasarkan status (pending & sudah diproses)
    public function verifySellers()
    {
        $pendingSellers = Seller::where('status', 'pending')->paginate(10, ['*'], 'pendingPage');
        $processedSellers = Seller::whereIn('status', ['approved', 'rejected'])->paginate(10, ['*'], 'processedPage');

        return view('admin.verify_sellers', compact('pendingSellers', 'processedSellers'));
    }


    // Approve seller berdasarkan id
    public function approveSeller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'approved';
        $seller->save();

        return back()->with('success', 'Seller berhasil disetujui.');
    }

    // Reject seller berdasarkan id
    public function rejectSeller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'rejected';
        $seller->save();

        return back()->with('error', 'Seller ditolak.');
    }

    public function show($id)
    {
        $seller = Seller::findOrFail($id);
        return view('admin.show', compact('seller'));
    }

    // Menampilkan halaman verifikasi produk
    public function verifyProducts()
    {
        $pendingProducts = Product::where('status', 'pending')->paginate(10);
        $approvedProducts = Product::whereIn('status', ['approved', 'rejected'])->paginate(10);

        return view('admin.verify_products', compact('pendingProducts', 'approvedProducts'));
    }

    // Menyetujui produk
    public function approveProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 'approved';
        $product->save();

        return back()->with('success', 'Produk berhasil disetujui.');
    }

    // Menolak produk
    public function rejectProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->status = 'rejected';
        $product->save();

        return back()->with('error', 'Produk ditolak.');
    }
}
