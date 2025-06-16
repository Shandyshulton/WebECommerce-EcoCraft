<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah sudah login sebagai seller
        if (!Auth::guard('seller')->check()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Hanya seller yang boleh masuk.');
        }

        // Ambil data seller yang sedang login
        $seller = Auth::guard('seller')->user();

        // Cek status seller
        if ($seller->status !== 'approved') {
            Auth::guard('seller')->logout(); // logout agar tidak bisa akses
            return redirect()->route('login')->with('error', 'Akun seller belum disetujui oleh admin.');
        }

        return $next($request);
    }
}
