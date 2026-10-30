<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfilLengkap
{
    /**
     * Paksa mahasiswa yang belum mengisi profil untuk ke halaman profil terlebih dahulu.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Middleware ini hanya berlaku untuk peran mahasiswa
        if ($user && $user->peran === 'mahasiswa') {
            // Kecualikan route profil (edit & update) dan logout agar tidak loop redirect
            $excludedRoutes = ['mahasiswa.profil.show', 'mahasiswa.profil.edit', 'mahasiswa.profil.update', 'logout'];

            if (
                !in_array($request->route()?->getName(), $excludedRoutes)
                && $user->profilMahasiswa === null
            ) {
                return redirect()->route('mahasiswa.profil.edit')
                    ->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
