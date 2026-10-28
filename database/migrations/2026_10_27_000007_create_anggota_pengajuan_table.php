<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->restrictOnDelete();
            $table->foreignId('mahasiswa_id')->index()->constrained('users')->restrictOnDelete();
            $table->foreignId('dosen_pembimbing_id')->nullable()->index()
                ->constrained('users')->restrictOnDelete();
            $table->string('status_magang', 20)->nullable(); // berjalan, laporan, selesai
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->text('saran_perusahaan')->nullable();    // FORM PKL 008
            $table->timestamp('selesai_pada')->nullable();
            // false jika pengajuan menjadi ditolak_final atau dibatalkan
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Satu mahasiswa tidak boleh muncul dua kali di kelompok yang sama.
        DB::statement('
            CREATE UNIQUE INDEX anggota_pengajuan_kelompok_unik
            ON anggota_pengajuan (pengajuan_id, mahasiswa_id)
            WHERE deleted_at IS NULL
        ');

        // Satu mahasiswa hanya boleh punya satu kelompok magang yang aktif.
        DB::statement('
            CREATE UNIQUE INDEX anggota_pengajuan_mahasiswa_aktif_unik
            ON anggota_pengajuan (mahasiswa_id)
            WHERE aktif AND deleted_at IS NULL
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_pengajuan');
    }
};
