<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CourierMiddleware
{
    /**
     * Kurir harus masuk sebagai petugas, dan akunnya tidak sedang dinonaktifkan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('courier')->check()) {
            return redirect()->route('courier.login')->with('error', 'Silakan masuk sebagai kurir.');
        }

        $courier = Auth::guard('courier')->user();

        if (! $courier->is_active) {
            Auth::guard('courier')->logout();

            return redirect()->route('courier.login')->with('error', 'Akun kurir ini sedang tidak aktif.');
        }

        return $next($request);
    }
}
