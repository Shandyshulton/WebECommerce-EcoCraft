<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Hanya izinkan admin dengan peran super_admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke halaman admin!');
        }

        if (! $admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Halaman ini hanya untuk Super Admin.');
        }

        return $next($request);
    }
}
