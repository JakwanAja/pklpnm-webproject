<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota_pengajuan')->restrictOnDelete();
            $table->foreignId('jurnal_mingguan_id')->nullable()->index()
                ->constrained('jurnal_mingguan')->restrictOnDelete();
            $table->date('tanggal');
            $table->text('kegiatan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('path_foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('
            CREATE UNIQUE INDEX jurnal_harian_anggota_tanggal_unik
            ON jurnal_harian (anggota_id, tanggal)
            WHERE deleted_at IS NULL
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_harian');
    }
};
