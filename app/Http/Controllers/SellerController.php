<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    // Menampilkan dashboard seller
    public function dashboard()
    {
        return view('seller.dashboard');
    }

    // Menampilkan profil seller (bisa dipakai untuk detail readonly)
    public function showProfile()
    {
        $seller = Auth::guard('seller')->user();
        return view('seller.profile-show', compact('seller'));
    }

    // Menampilkan form edit profil seller
    public function editProfile()
    {
        $seller = Auth::guard('seller')->user();
        return view('seller.profile', compact('seller'));
    }

    // Menyimpan perubahan pada profil seller
    public function updateProfile(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $request->validate([
            'name_sellers' => 'required|string|max:255',
            'email' => 'required|email|unique:sellers,email,' . $seller->id_sellers . ',id_sellers',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name_sellers' => $request->name_sellers,
            'email' => $request->email,
        ];

        // Jika ada upload file
        if ($request->hasFile('profile_image')) {
            // Hapus file lama jika ada
            if ($seller->profile_image) {
                Storage::disk('public')->delete($seller->profile_image);
            }

            // Simpan file baru
            $path = $request->file('profile_image')->store('profile-photos', 'public');
            $data['profile_image'] = $path;
        }

        // Update ke database
        DB::table('sellers')->where('id_sellers', $seller->id_sellers)->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
