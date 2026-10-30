<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilMahasiswaRequest;
use App\Services\ProfilMahasiswaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function __construct(
        protected ProfilMahasiswaService $service
    ) {}

    /**
     * Tampilkan profil mahasiswa (read-only).
     */
    public function show(): View
    {
        $profil = $this->service->getOrNew(Auth::user());

        return view('mahasiswa.profil.show', compact('profil'));
    }

    /**
     * Tampilkan form edit profil mahasiswa.
     */
    public function edit(): View
    {
        $profil = $this->service->getOrNew(Auth::user());

        return view('mahasiswa.profil.edit', compact('profil'));
    }

    /**
     * Simpan perubahan profil mahasiswa.
     */
    public function update(ProfilMahasiswaRequest $request): RedirectResponse
    {
        $this->service->saveProfile(Auth::user(), $request->validated());

        return redirect()->route('mahasiswa.profil.show')
            ->with('success', 'Profil berhasil disimpan.');
    }
}
