<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('registerseller');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name_sellers' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:sellers',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'store_name' => 'required|string|max:255',
            'province' => 'required|string|max:255',  // tambah validasi
            'city' => 'required|string|max:255',      // tambah validasi
            'password' => 'required|min:9|confirmed',
            'ktp_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan file KTP ke storage/app/public/ktp_sellers
        $ktpPath = $request->file('ktp_image')->store('ktp_sellers', 'public');

        // Simpan data seller termasuk city dan province
        $seller = Seller::create([
            'name_sellers' => $validated['name_sellers'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'store_name' => $validated['store_name'],
            'province' => $validated['province'],  // simpan
            'city' => $validated['city'],          // simpan
            'password' => Hash::make($validated['password']),
            'ktp_image' => $ktpPath,
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }
}
