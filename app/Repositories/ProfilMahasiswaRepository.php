<?php

namespace App\Repositories;

use App\Models\ProfilMahasiswa;

class ProfilMahasiswaRepository
{
    /**
     * Ambil profil berdasarkan user_id.
     */
    public function findByUserId(int $userId): ?ProfilMahasiswa
    {
        return ProfilMahasiswa::where('user_id', $userId)->first();
    }

    /**
     * Buat atau perbarui profil untuk user_id tertentu.
     * Hanya mengupdate field yang ada di fillable model (tanpa status_aktif & ipk).
     */
    public function upsertByUserId(int $userId, array $data): ProfilMahasiswa
    {
        return ProfilMahasiswa::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }
}
