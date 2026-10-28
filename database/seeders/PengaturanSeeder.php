<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanSeeder extends Seeder
{
    /**
     * Isi tabel pengaturan dengan nilai default sistem.
     * Menggunakan upsert agar aman dijalankan berulang kali.
     */
    public function run(): void
    {
        $pengaturan = [
            // Bobot nilai akhir (SKPL 4.3 poin 5)
            // Total harus 100; Admin Sistem dapat mengubah lewat UI.
            [
                'kunci' => 'bobot_perusahaan',
                'nilai' => '50',
            ],
            [
                'kunci' => 'bobot_dosen',
                'nilai' => '50',
            ],

            // Batas ukuran unggahan (MB) — NF-02
            [
                'kunci' => 'maks_ukuran_pdf_mb',
                'nilai' => '5',
            ],
            [
                'kunci' => 'maks_ukuran_foto_mb',
                'nilai' => '2',
            ],

            // Template nomor surat (placeholder: {tahun}, {urut}, {prodi})
            [
                'kunci' => 'template_nomor_surat_permohonan',
                'nilai' => '{urut}/PKL/PNM/{tahun}',
            ],
            [
                'kunci' => 'template_nomor_surat_pengantar',
                'nilai' => '{urut}/PENGANTAR/PNM/{tahun}',
            ],

            // Nama institusi — dipakai di header surat PDF
            [
                'kunci' => 'nama_institusi',
                'nilai' => 'Politeknik Negeri Madiun',
            ],
            [
                'kunci' => 'alamat_institusi',
                'nilai' => 'Jl. Serayu No. 84, Madiun, Jawa Timur 63133',
            ],
        ];

        foreach ($pengaturan as $item) {
            DB::table('pengaturan')->upsert(
                [
                    'kunci'      => $item['kunci'],
                    'nilai'      => $item['nilai'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                ['kunci'],          // kolom unique key
                ['nilai', 'updated_at'] // kolom yang diupdate jika sudah ada
            );
        }
    }
}
