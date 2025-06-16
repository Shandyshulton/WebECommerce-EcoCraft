<?php

// app/Http/Controllers/LoginController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\Customer;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Cek di tabel admin dulu
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        // Cek di tabel seller dulu
        $seller = Seller::where('email', $credentials['email'])->first();

        if ($seller && $seller->status === 'approved') {
            if (Auth::guard('seller')->attempt($credentials)) {
                return redirect()->route('seller.dashboard');
            }
            // Jika gagal login seller walau sudah approved, lanjut cek customer
        }

        // Coba login customer
        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->route('customer.dashboard');
        }

        // Jika semua gagal
        return redirect()->back()->withErrors(['email' => 'Email atau password salah atau akun belum disetujui.']);
    }


    public function logout(Request $request)
    {
        // Logout dari guard yang aktif
        $guards = ['admin', 'seller', 'customer'];
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
                break; // Hentikan loop setelah logout
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Logout berhasil!');
    }
}
