<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->text('alamat');
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->string('telepon', 30)->nullable();
            $table->string('bidang', 150);
            $table->string('nama_kontak', 150)->nullable();
            $table->string('jabatan_kontak', 100)->nullable();
            $table->string('telepon_kontak', 30)->nullable();
            $table->string('status', 20)->default('usulan'); // usulan, terverifikasi
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
