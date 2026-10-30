<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPeran
{
    /**
     * Periksa apakah pengguna yang sedang login memiliki salah satu
     * dari peran yang diizinkan.
     *
     * Contoh pemakaian di route:
     *   ->middleware('peran:admin')
     *   ->middleware('peran:admin,pic_magang')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$peranDiizinkan): Response
    {
        // Belum login → redirect ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Peran tidak cocok → 403
        if (!in_array($user->peran, $peranDiizinkan, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
