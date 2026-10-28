<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan penting: PengaturanSeeder tidak bergantung apa pun,
     * UserSeeder perlu tabel users dan profil_mahasiswa.
     */
    public function run(): void
    {
        $this->call([
            PengaturanSeeder::class,
            UserSeeder::class,
        ]);
    }
}
