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

    public function authenticateSeller(Request $request)
    {
        $request->merge(['seller_login' => true, 'seller_login_page' => true]);
        return $this->authenticate($request);
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
            return $this->loginFailure($request, 'Email atau nomor WhatsApp tidak ditemukan.');
        }

        $credentials = ['email' => $email, 'password' => $input['password']];

        // Cek di tabel admin dulu
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Cek di tabel seller dulu
        $seller = Seller::where('email', $credentials['email'])->first();

        if ($seller && $seller->status !== 'approved') {
            $message = $seller->status === 'pending'
                ? 'Akun seller masih menunggu persetujuan admin.'
                : 'Akun seller belum dapat digunakan. Silakan hubungi admin.';

            return $this->loginFailure($request, $message);
        }

        if ($seller && $seller->status === 'approved') {
            if (Auth::guard('seller')->attempt($credentials)) {
                return redirect()->route('seller.dashboard');
            }
            // Jika gagal login seller walau sudah approved, lanjut cek customer
        }

        // Coba login customer
        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->intended(route('customer.dashboard'));
        }

        // Jika semua gagal
        return $this->loginFailure($request, 'Email atau password salah atau akun belum disetujui.');
    }

    private function loginFailure(Request $request, string $message)
    {
        if ($request->boolean('seller_login_page')) {
            return back()->withInput()->withErrors(['email' => $message]);
        }

        if ($request->boolean('seller_login')) {
            return redirect()->to(route('customer.dashboard') . '#seller-login')
                ->withInput()
                ->withErrors(['email' => $message], 'seller');
        }

        return redirect()->back()->withInput()->withErrors(['email' => $message]);
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
