<?php

namespace App\Http\Controllers;

use App\Models\CourierUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('courier.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $courier = CourierUser::where('email', $credentials['email'])->first();

        if ($courier && ! $courier->is_active) {
            return back()->withInput()->withErrors(['email' => 'Akun kurir ini sedang tidak aktif.']);
        }

        if (Auth::guard('courier')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('courier.tasks.index');
        }

        return back()->withInput()->withErrors(['email' => 'Email atau password kurir salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('courier')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('courier.login')->with('status', 'Berhasil keluar.');
    }
}
