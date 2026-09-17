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

    public function showSellerLoginForm()
    {
        return view('auth.seller-login');
    }

    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Login seller — hanya guard seller yang diperiksa.
     * Sebelumnya method ini menitipkan flag ke authenticate(), yang berarti
     * form login seller ikut mencoba guard admin lebih dulu.
     */
    public function authenticateSeller(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $seller = Seller::where('email', $input['email'])->first();

        if ($seller && $seller->status !== 'approved') {
            $message = $seller->status === 'pending'
                ? 'Akun seller masih menunggu persetujuan admin.'
                : 'Akun seller belum dapat digunakan. Silakan hubungi admin.';

            return back()->withInput()->withErrors(['email' => $message]);
        }

        if (Auth::guard('seller')->attempt($input)) {
            return redirect()->intended(route('seller.dashboard'));
        }

        return back()->withInput()->withErrors(['email' => 'Email atau password seller salah.']);
    }

    public function authenticateAdmin(Request $request)
    {
        $input = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);

        if (Auth::guard('admin')->attempt($input)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withInput()->withErrors(['email' => 'Email atau password admin salah.']);
    }

    public function showAdminRegistrationForm()
    {
        return view('auth.admin-register');
    }

    public function registerAdmin(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->route('admin.login')->with('success', 'Akun admin berhasil dibuat.');
    }

    /**
     * Login customer — hanya guard customer yang diperiksa.
     *
     * Sebelumnya route /login ini mencoba guard admin, lalu seller, baru
     * customer. Akibatnya akun admin dan seller bisa masuk lewat halaman login
     * customer (dan diarahkan ke dashboard admin/seller). Admin dan seller
     * punya halamannya sendiri: admin.login dan seller.login.
     */
    public function authenticate(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $email = filter_var($input['email'], FILTER_VALIDATE_EMAIL)
            ? $input['email']
            : optional(Customer::where('phone_number', $input['email'])->first())->email;

        if (!$email) {
            return $this->loginFailure('Email atau nomor WhatsApp tidak ditemukan.');
        }

        if (Auth::guard('customer')->attempt(['email' => $email, 'password' => $input['password']])) {
            return redirect()->intended(route('customer.dashboard'));
        }

        return $this->loginFailure('Email atau password salah.');
    }

    private function loginFailure(string $message)
    {
        return redirect()->back()->withInput()->withErrors(['email' => $message]);
    }


    public function logout(Request $request)
    {
        // Logout dari guard yang aktif
        $guards = ['admin', 'seller', 'customer', 'courier'];
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
