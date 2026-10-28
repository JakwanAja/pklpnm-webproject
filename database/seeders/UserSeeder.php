<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Buat akun default untuk testing di lingkungan development.
     * JANGAN jalankan di production.
     *
     * Akun yang dibuat:
     *   admin@pklpnm.test        | password: password
     *   picmagang@pklpnm.test    | password: password
     *   kps@pklpnm.test          | password: password
     *   dosen@pklpnm.test        | password: password
     *   mahasiswa@pklpnm.test    | password: password
     */
    public function run(): void
    {
        $users = [
            [
                'nama'                  => 'Administrator',
                'email'                 => 'admin@pklpnm.test',
                'password'              => Hash::make('password'),
                'peran'                 => 'admin',
                'aktif'                 => true,
                'wajib_ganti_password'  => false,
            ],
            [
                'nama'                  => 'PIC Magang',
                'email'                 => 'picmagang@pklpnm.test',
                'password'              => Hash::make('password'),
                'peran'                 => 'pic_magang',
                'aktif'                 => true,
                'wajib_ganti_password'  => false,
            ],
            [
                'nama'                  => 'Ketua Program Studi',
                'email'                 => 'kps@pklpnm.test',
                'password'              => Hash::make('password'),
                'peran'                 => 'kps',
                'aktif'                 => true,
                'wajib_ganti_password'  => false,
            ],
            [
                'nama'                  => 'Dosen Pembimbing',
                'email'                 => 'dosen@pklpnm.test',
                'password'              => Hash::make('password'),
                'peran'                 => 'dosen',
                'aktif'                 => true,
                'wajib_ganti_password'  => false,
            ],
            [
                'nama'                  => 'Budi Santoso',
                'email'                 => 'mahasiswa@pklpnm.test',
                'password'              => Hash::make('password'),
                'peran'                 => 'mahasiswa',
                'aktif'                 => true,
                'wajib_ganti_password'  => false,
            ],
        ];

        foreach ($users as $user) {
            // Upsert berdasarkan email agar aman dijalankan ulang
            DB::table('users')->upsert(
                array_merge($user, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                ['email'],
                ['nama', 'password', 'peran', 'aktif', 'wajib_ganti_password', 'updated_at']
            );
        }

        // Buat profil mahasiswa untuk akun mahasiswa test
        $mahasiswaId = DB::table('users')
            ->where('email', 'mahasiswa@pklpnm.test')
            ->value('id');

        if ($mahasiswaId) {
            DB::table('profil_mahasiswa')->upsert(
                [
                    'user_id'          => $mahasiswaId,
                    'npm'              => '2301010001',
                    'jenis_kelamin'    => 'Laki-laki',
                    'program_studi'    => 'Teknik Informatika',
                    'kelas'            => 'TI-3A',
                    'alamat_ktp'       => 'Jl. Contoh No. 1, Madiun',
                    'alamat_domisili'  => 'Jl. Contoh No. 1, Madiun',
                    'no_hp'            => '08123456789',
                    'status_aktif'     => true,
                    'ipk'              => 3.50,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ],
                ['user_id'],
                ['npm', 'jenis_kelamin', 'program_studi', 'kelas', 'status_aktif', 'ipk', 'updated_at']
            );
        }
    }
}
