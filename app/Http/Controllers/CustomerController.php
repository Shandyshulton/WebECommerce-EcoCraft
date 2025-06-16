<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use App\Models\Product;

class CustomerController extends Controller
{
    /**
     * Menampilkan dashboard customer
     */
    public function dashboard()
    {
        $user = Auth::guard('customer')->user();

        $products = Product::where('status', 'approved')
            ->where('is_active', 1)
            ->latest()
            ->take(8)
            ->get();

        return view('customer.dashboard', compact('products'));

        // Cek apakah user juga daftar sebagai seller berdasarkan email
        $seller = Seller::where('email', $user->email)->first();

        if ($seller) {
            if ($seller->status === 'approved') {
                session()->flash('success', 'Akun seller kamu telah disetujui!');
            } elseif ($seller->status === 'rejected') {
                session()->flash('error', 'Maaf, akun seller kamu ditolak. Mohon diperhatikan gambar dan datanya yang jelas. Terimakasih');
            }
        }

        return view('customer.dashboard', compact('products', 'user'));
    }

    /**
     * Menampilkan form edit profile
     */
    public function showProfileForm()
    {
        $user = Auth::guard('customer')->user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Update data profil customer
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('customer')->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name_customers' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:customers,email,' . $user->id_customers . ',id_customers',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name_customers', 'email']);

        // Jika ada foto baru di-upload
        if ($request->hasFile('profile_image')) {
            // Jangan hapus foto lama (sesuai permintaan kamu sebelumnya)
            $path = $request->file('profile_image')->store('profile-photos', 'public');
            $data['profile_image'] = $path;
        }

        // Update data menggunakan query builder dengan primary key id_customers
        DB::table('customers')->where('id_customers', $user->id_customers)->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
