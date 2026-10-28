<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota_pengajuan')->restrictOnDelete();
            $table->string('tipe', 10);               // offline, daring
            $table->date('tanggal');                  // jadwal bimbingan
            $table->string('hari', 10);               // hari pelaksanaan
            $table->time('jam_mulai')->nullable();    // opsional
            $table->string('tautan')->nullable();     // Google Meet / Zoom, wajib jika daring
            $table->text('topik')->nullable();        // opsional, pokok bahasan
            $table->string('status', 20)->default('diajukan'); // diajukan, revisi, disetujui
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('ditinjau_pada')->nullable();
            $table->string('path_foto_bukti')->nullable();     // hanya setelah disetujui
            $table->timestamp('foto_diunggah_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['anggota_id', 'status']);
        });

        // Bimbingan daring wajib mencantumkan tautan.
        DB::statement("
            ALTER TABLE bimbingan
            ADD CONSTRAINT bimbingan_tautan_daring_check
            CHECK (tipe <> 'daring' OR tautan IS NOT NULL)
        ");

        // Foto bukti baru boleh ada setelah pengajuan disetujui dosen.
        DB::statement("
            ALTER TABLE bimbingan
            ADD CONSTRAINT bimbingan_foto_disetujui_check
            CHECK (path_foto_bukti IS NULL OR status = 'disetujui')
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbingan');
    }
};
