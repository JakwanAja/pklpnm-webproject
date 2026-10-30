<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboard;
use App\Http\Controllers\Kps\DashboardController as KpsDashboard;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboard;
use App\Http\Controllers\PicMagang\DashboardController as PicMagangDashboard;
use App\Http\Controllers\PicPerusahaan\DashboardController as PicPerusahaanDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =========================================================
// Root — redirect ke login (belum login) atau dashboard
// =========================================================
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        $route = match ($user->peran) {
            'pic_magang'     => 'pic-magang.dashboard',
            'pic_perusahaan' => 'pic-perusahaan.dashboard',
            default          => $user->peran . '.dashboard',
        };
        return redirect()->route($route);
    }
    return redirect()->route('login');
})->name('home');

// =========================================================
// Autentikasi
// =========================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =========================================================
// Notifikasi
// =========================================================
Route::get('/notifikasi/tandai-semua', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
})->middleware('auth')->name('notifikasi.tandai-semua');

// =========================================================
// Dashboard & halaman per peran
// =========================================================
Route::middleware('auth')->group(function () {

    // Mahasiswa
    Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('peran:mahasiswa')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');
        Route::get('/profil/edit', fn () => abort(503, 'Phase 3'))->name('profil.edit');
        Route::get('/pengajuan', fn () => abort(503, 'Phase 6'))->name('pengajuan.index');
        Route::get('/jurnal', fn () => abort(503, 'Phase 11'))->name('jurnal.index');
        Route::get('/bimbingan', fn () => abort(503, 'Phase 13'))->name('bimbingan.index');
        Route::get('/laporan', fn () => abort(503, 'Phase 14'))->name('laporan.index');
    });

    // PIC Magang
    Route::prefix('pic-magang')->name('pic-magang.')->middleware('peran:pic_magang')->group(function () {
        Route::get('/dashboard', [PicMagangDashboard::class, 'index'])->name('dashboard');
        Route::get('/pengajuan', fn () => abort(503, 'Phase 8'))->name('pengajuan.index');
        Route::get('/perusahaan', fn () => abort(503, 'Phase 5'))->name('perusahaan.index');
        Route::get('/pemantauan', fn () => abort(503, 'Phase 12'))->name('pemantauan.index');
        Route::get('/rekap', fn () => abort(503, 'Phase 17'))->name('rekap.index');
    });

    // KPS
    Route::prefix('kps')->name('kps.')->middleware('peran:kps')->group(function () {
        Route::get('/dashboard', [KpsDashboard::class, 'index'])->name('dashboard');
        Route::get('/pengajuan', fn () => abort(503, 'Phase 9'))->name('pengajuan.index');
        Route::get('/laporan', fn () => abort(503, 'Phase 15'))->name('laporan.index');
    });

    // Dosen Pembimbing
    Route::prefix('dosen')->name('dosen.')->middleware('peran:dosen')->group(function () {
        Route::get('/dashboard', [DosenDashboard::class, 'index'])->name('dashboard');
        Route::get('/mahasiswa', fn () => abort(503, 'Phase 10'))->name('mahasiswa.index');
        Route::get('/bimbingan', fn () => abort(503, 'Phase 13'))->name('bimbingan.index');
    });

    // PIC Perusahaan
    Route::prefix('pic-perusahaan')->name('pic-perusahaan.')->middleware('peran:pic_perusahaan')->group(function () {
        Route::get('/dashboard', [PicPerusahaanDashboard::class, 'index'])->name('dashboard');
        Route::get('/jurnal', fn () => abort(503, 'Phase 12'))->name('jurnal.index');
        Route::get('/laporan', fn () => abort(503, 'Phase 15'))->name('laporan.index');
    });

    // Admin
    Route::prefix('admin')->name('admin.')->middleware('peran:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/pengguna', fn () => abort(503, 'Phase 4'))->name('pengguna.index');
        Route::get('/perusahaan', fn () => abort(503, 'Phase 5'))->name('perusahaan.index');
        Route::get('/periode', fn () => abort(503, 'Phase 5'))->name('periode.index');
        Route::get('/pengaturan', fn () => abort(503, 'Phase 5'))->name('pengaturan.index');
        Route::get('/log', fn () => abort(503, 'Phase 5'))->name('log.index');
    });

});