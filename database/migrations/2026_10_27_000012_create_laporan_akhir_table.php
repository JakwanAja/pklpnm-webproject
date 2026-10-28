<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota_pengajuan')->restrictOnDelete();
            $table->unsignedSmallInteger('versi');
            $table->string('judul');
            $table->string('path_berkas');
            // menunggu, disetujui, revisi
            $table->string('status_perusahaan', 20)->default('menunggu');
            $table->string('status_dosen', 20)->default('menunggu');
            $table->string('status_kps', 20)->default('menunggu');
            $table->text('catatan_perusahaan')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_kps')->nullable();
            $table->timestamp('perusahaan_pada')->nullable();
            $table->timestamp('dosen_pada')->nullable();
            $table->timestamp('kps_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('
            CREATE UNIQUE INDEX laporan_akhir_anggota_versi_unik
            ON laporan_akhir (anggota_id, versi)
            WHERE deleted_at IS NULL
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_akhir');
    }
};
