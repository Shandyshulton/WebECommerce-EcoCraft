<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user terautentikasi sebagai customer
        if (!Auth::guard('customer')->check()) {
            // Jika bukan customer, redirect ke login dengan pesan error
            return redirect()->route('login')->with('error', 'Anda harus login sebagai customer!');
        }

        return $next($request);
    }
}