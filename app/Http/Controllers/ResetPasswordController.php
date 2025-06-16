<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    /**
     * Menampilkan form reset password
     */
    public function showResetForm()
    {
        return view('reset-password');
    }

    /**
     * Memproses reset password
     */
    public function reset(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'new_password' => 'required|min:8|confirmed',
    ]);

    
    // dd($request->all());

    $user = null;
    $guard = null;

    if ($admin = \App\Models\Admin::where('email', $request->email)->first()) {
        $admin->password = Hash::make($request->new_password);
        $admin->save();
        $user = $admin;
        $guard = 'admin';
    } 
    elseif ($seller = \App\Models\Seller::where('email', $request->email)->first()) {
        $seller->password = Hash::make($request->new_password);
        $seller->save();
        $user = $seller;
        $guard = 'seller';
    } 
    elseif ($customer = \App\Models\Customer::where('email', $request->email)->first()) {
        $customer->password = Hash::make($request->new_password);
        $customer->save();
        $user = $customer;
        $guard = 'customer';
    } 
    else {
        return back()->withErrors(['email' => 'Email tidak ditemukan'])->withInput();
    }

    // // Logout user dari guard yang sesuai
    // Auth::guard($guard)->logout();

    // Invalidate session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Password berhasil direset!');
}
}