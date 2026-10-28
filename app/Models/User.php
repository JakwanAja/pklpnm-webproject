<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'sso_id',
        'nama',
        'email',
        'password',
        'wajib_ganti_password',
        'peran',
        'perusahaan_id',
        'aktif',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast tipe data.
     */
    protected function casts(): array
    {
        return [
            'password'             => 'hashed',
            'wajib_ganti_password' => 'boolean',
            'aktif'                => 'boolean',
        ];
    }

    // =========================================================
    // Relasi
    // =========================================================

    /**
     * Profil mahasiswa (1:1, hanya untuk peran mahasiswa).
     */
    public function profilMahasiswa(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ProfilMahasiswa::class);
    }

    /**
     * Perusahaan yang dikelola (1:1, hanya untuk peran pic_perusahaan).
     */
    public function perusahaan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    // =========================================================
    // Helper
    // =========================================================

    /**
     * Cek apakah pengguna memiliki peran tertentu.
     */
    public function hasPeran(string $peran): bool
    {
        return $this->peran === $peran;
    }

    /**
     * Cek apakah pengguna adalah mahasiswa yang memenuhi syarat PKL
     * (aktif dan IPK >= 2.00).
     */
    public function memenuhinSyaratPkl(): bool
    {
        if ($this->peran !== 'mahasiswa' || !$this->aktif) {
            return false;
        }

        $profil = $this->profilMahasiswa;

        return $profil
            && $profil->status_aktif
            && $profil->ipk >= 2.00;
    }
}
