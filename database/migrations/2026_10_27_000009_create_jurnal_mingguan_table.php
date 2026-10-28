<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_mingguan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota_pengajuan')->restrictOnDelete();
            $table->date('awal_minggu');
            $table->date('akhir_minggu');
            $table->timestamp('diajukan_pada')->nullable();
            // menunggu, disetujui, revisi
            $table->string('status_dosen', 20)->default('menunggu');
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('dosen_pada')->nullable();
            $table->string('status_perusahaan', 20)->default('menunggu');
            $table->text('catatan_perusahaan')->nullable();
            $table->timestamp('perusahaan_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('
            CREATE UNIQUE INDEX jurnal_mingguan_anggota_minggu_unik
            ON jurnal_mingguan (anggota_id, awal_minggu)
            WHERE deleted_at IS NULL
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_mingguan');
    }
};
