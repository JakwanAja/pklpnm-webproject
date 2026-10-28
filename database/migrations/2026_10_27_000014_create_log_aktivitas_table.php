<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()
                ->constrained('users')->restrictOnDelete(); // NULL untuk aksi sistem otomatis
            $table->morphs('subjek');                       // subjek_type + subjek_id (+ index)
            $table->string('aksi', 50);                     // status_berubah, dibuat, login, ...
            $table->string('dari_status', 30)->nullable();
            $table->string('ke_status', 30)->nullable();
            $table->text('catatan')->nullable();
            $table->jsonb('properti')->nullable();
            $table->string('alamat_ip', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
