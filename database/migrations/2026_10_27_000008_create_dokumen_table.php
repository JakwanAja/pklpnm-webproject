<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->restrictOnDelete();
            $table->foreignId('anggota_id')->nullable()->index()
                ->constrained('anggota_pengajuan')->restrictOnDelete();
            // proposal, surat_jawaban, surat_penolakan, form_004, form_009,
            // surat_permohonan_ttd, surat_pengantar_ttd
            $table->string('jenis', 40);
            $table->string('path');
            $table->string('nama_asli');
            $table->string('mime', 100);
            $table->unsignedInteger('ukuran'); // byte
            $table->foreignId('diunggah_oleh')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['pengajuan_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
