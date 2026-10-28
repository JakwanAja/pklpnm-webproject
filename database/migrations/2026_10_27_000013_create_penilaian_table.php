<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota_pengajuan')->restrictOnDelete();
            $table->string('jenis', 20); // lapangan (FORM 006), pembimbing (FORM 007)
            $table->foreignId('penilai_id')->constrained('users')->restrictOnDelete();
            $table->jsonb('nilai');      // contoh: {"kedisiplinan": 85, ...}
            $table->decimal('total_nilai', 5, 2);
            $table->timestamps();

            $table->unique(['anggota_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
