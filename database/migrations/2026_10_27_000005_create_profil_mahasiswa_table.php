<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->restrictOnDelete();
            $table->string('npm', 30)->unique();
            $table->string('jenis_kelamin', 10);
            $table->string('program_studi', 100);
            $table->string('kelas', 30);
            $table->text('alamat_ktp');
            $table->text('alamat_domisili');
            $table->string('no_hp', 30);
            $table->boolean('status_aktif')->default(true); // selama SSO belum ada: diisi Admin
            $table->decimal('ipk', 3, 2);                   // syarat minimal 2,00
            $table->timestamp('disinkron_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_mahasiswa');
    }
};
