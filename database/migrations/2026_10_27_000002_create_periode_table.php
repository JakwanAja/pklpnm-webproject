<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamp('buka_pada');
            $table->timestamp('tutup_pada');
            $table->date('tanggal_mulai_pkl');
            $table->date('tanggal_selesai_pkl');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode');
    }
};
