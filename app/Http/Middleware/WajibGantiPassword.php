<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WajibGantiPassword
{
    /**
     * Handle an incoming request.
     *
     * Redirect ke halaman ganti password jika pengguna yang sudah login
     * masih memiliki flag wajib_ganti_password = true.
     * Pengecualian: route ganti-password itu sendiri dan logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->wajib_ganti_password) {
            // Izinkan akses ke route ganti-password dan logout agar tidak loop
            if (
                $request->routeIs('ganti-password') ||
                $request->routeIs('logout')
            ) {
                return $next($request);
            }

            return redirect()->route('ganti-password');
        }

        return $next($request);
    }
}
