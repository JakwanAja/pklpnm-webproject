<?php

namespace App\Services;

use App\Models\ProfilMahasiswa;
use App\Models\User;
use App\Repositories\ProfilMahasiswaRepository;
use Illuminate\Support\Facades\DB;

class ProfilMahasiswaService
{
    public function __construct(
        protected ProfilMahasiswaRepository $repository
    ) {}

    /**
     * Kembalikan profil yang ada atau instance baru (belum disimpan).
     */
    public function getOrNew(User $user): ProfilMahasiswa
    {
        return $this->repository->findByUserId($user->id)
            ?? new ProfilMahasiswa(['user_id' => $user->id]);
    }

    /**
     * Simpan profil mahasiswa.
     * Nama disimpan ke tabel users; sisa data ke profil_mahasiswa.
     */
    public function saveProfile(User $user, array $validatedData): ProfilMahasiswa
    {
        return DB::transaction(function () use ($user, $validatedData) {
            // Update nama di tabel users
            $user->update(['nama' => $validatedData['nama']]);

            // Pisahkan field profil_mahasiswa (tanpa 'nama')
            $profilData = collect($validatedData)
                ->except('nama')
                ->all();

            return $this->repository->upsertByUserId($user->id, $profilData);
        });
    }
}
