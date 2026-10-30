<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilMahasiswa extends Model
{
    protected $table = 'profil_mahasiswa';

    protected $fillable = [
        'user_id',
        'npm',
        'jenis_kelamin',
        'program_studi',
        'kelas',
        'alamat_ktp',
        'alamat_domisili',
        'no_hp',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
            'ipk'          => 'decimal:2',
        ];
    }

    // =========================================================
    // Relasi
    // =========================================================

    /**
     * Pengguna pemilik profil ini.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
