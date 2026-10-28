<?php

namespace App\Helpers;

class PeranHelper
{
    /**
     * Peta nilai peran di database ke label bahasa Indonesia.
     */
    private static array $labels = [
        'admin'          => 'Admin Sistem',
        'pic_magang'     => 'PIC Magang',
        'kps'            => 'Ketua Program Studi',
        'dosen'          => 'Dosen Pembimbing',
        'mahasiswa'      => 'Mahasiswa',
        'pic_perusahaan' => 'PIC Perusahaan',
    ];

    /**
     * Kembalikan label bahasa Indonesia untuk nilai peran.
     * Jika tidak ditemukan, kembalikan nilai aslinya.
     */
    public static function label(string $peran): string
    {
        return self::$labels[$peran] ?? ucfirst($peran);
    }

    /**
     * Kembalikan semua pasangan nilai => label.
     */
    public static function semua(): array
    {
        return self::$labels;
    }
}
