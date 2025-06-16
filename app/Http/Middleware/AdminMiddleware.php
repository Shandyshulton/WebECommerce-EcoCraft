<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user terautentikasi sebagai admin
        if (!Auth::guard('admin')->check()) {
            // Jika bukan admin (belum login sebagai admin), redirect ke login dengan pesan error
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke halaman admin!');
        }

        // Jika sudah login sebagai admin, lanjutkan request
        return $next($request);
    }
}
