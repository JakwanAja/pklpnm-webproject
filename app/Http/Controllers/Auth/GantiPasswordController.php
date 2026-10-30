<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\GantiPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GantiPasswordController extends Controller
{
    /**
     * Tampilkan form ganti password.
     *
     * Jika pengguna tidak diwajibkan ganti password, redirect ke dashboard.
     */
    public function show(): View|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->wajib_ganti_password) {
            return redirect()->route($this->dashboardRoute($user));
        }

        return view('auth.ganti-password');
    }

    /**
     * Proses penggantian password.
     *
     * Hash password baru, simpan, dan set flag wajib_ganti_password ke false.
     */
    public function update(GantiPasswordRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->password              = $request->input('password_baru');
        $user->wajib_ganti_password  = false;
        $user->save();

        return redirect()
            ->route($this->dashboardRoute($user))
            ->with('success', 'Kata sandi berhasil diperbarui. Selamat datang!');
    }

    /**
     * Petakan peran pengguna ke nama route dashboard.
     */
    private function dashboardRoute(\App\Models\User $user): string
    {
        return match ($user->peran) {
            'pic_magang'     => 'pic-magang.dashboard',
            'pic_perusahaan' => 'pic-perusahaan.dashboard',
            default          => $user->peran . '.dashboard',
        };
    }
}
