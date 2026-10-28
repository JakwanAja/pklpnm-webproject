<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode')->restrictOnDelete();
            $table->foreignId('perusahaan_id')->index()->constrained('perusahaan')->restrictOnDelete();
            $table->foreignId('dibuat_oleh')->constrained('users')->restrictOnDelete();
            // draft, diajukan, revisi, disetujui_pic, dikembalikan_kps, disetujui_kps,
            // surat_terbit, diterima, berjalan, ditolak_final, dibatalkan
            $table->string('status', 30)->default('draft');
            $table->string('nomor_surat_permohonan', 100)->nullable()->unique(); // diisi KPS
            $table->string('nomor_surat_pengantar', 100)->nullable()->unique();  // diisi PIC Magang
            $table->timestamp('diajukan_pada')->nullable();
            $table->timestamp('diterima_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['periode_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
