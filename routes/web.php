<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Informasi PKL/Magang
|--------------------------------------------------------------------------
|
| Rute dikelompokkan per fase dan per peran.
| Autentikasi bawaan Laravel tanpa Breeze (lihat dev-guide Bagian 5).
|
*/

// =========================================================
// Landing page — redirect ke login jika belum autentikasi,
// atau ke dashboard peran jika sudah login.
// =========================================================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->peran . '.dashboard');
    }
    return view('welcome');
})->name('home');

// =========================================================
// Placeholder route login/logout — akan diimplementasikan
// di Phase 1 (AuthController).
// Didefinisikan di sini agar view welcome.blade.php tidak error
// saat me-render route('login').
// =========================================================
Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->peran . '.dashboard');
    }
    return redirect('/'); // placeholder sampai Phase 1
})->name('login');

Route::post('/logout', function () {
    // placeholder — akan diisi di Phase 1
    return redirect('/');
})->name('logout');

// =========================================================
// Placeholder notifikasi — dipakai _navbar.blade.php
// =========================================================
Route::get('/notifikasi/tandai-semua', function () {
    return back();
})->middleware('auth')->name('notifikasi.tandai-semua');

// =========================================================
// Placeholder dashboard per peran
// Akan digantikan oleh controller nyata di Phase 1.
// =========================================================
Route::middleware('auth')->group(function () {

    Route::get('/mahasiswa/dashboard', fn () => 'Dashboard Mahasiswa — Phase 1')->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/profil/edit', fn () => '')->name('mahasiswa.profil.edit');
    Route::get('/mahasiswa/pengajuan', fn () => '')->name('mahasiswa.pengajuan.index');
    Route::get('/mahasiswa/jurnal', fn () => '')->name('mahasiswa.jurnal.index');
    Route::get('/mahasiswa/bimbingan', fn () => '')->name('mahasiswa.bimbingan.index');
    Route::get('/mahasiswa/laporan', fn () => '')->name('mahasiswa.laporan.index');

    Route::get('/pic-magang/dashboard', fn () => 'Dashboard PIC Magang — Phase 1')->name('pic-magang.dashboard');
    Route::get('/pic-magang/pengajuan', fn () => '')->name('pic-magang.pengajuan.index');
    Route::get('/pic-magang/perusahaan', fn () => '')->name('pic-magang.perusahaan.index');
    Route::get('/pic-magang/pemantauan', fn () => '')->name('pic-magang.pemantauan.index');
    Route::get('/pic-magang/rekap', fn () => '')->name('pic-magang.rekap.index');

    Route::get('/kps/dashboard', fn () => 'Dashboard KPS — Phase 1')->name('kps.dashboard');
    Route::get('/kps/pengajuan', fn () => '')->name('kps.pengajuan.index');
    Route::get('/kps/laporan', fn () => '')->name('kps.laporan.index');

    Route::get('/dosen/dashboard', fn () => 'Dashboard Dosen — Phase 1')->name('dosen.dashboard');
    Route::get('/dosen/mahasiswa', fn () => '')->name('dosen.mahasiswa.index');
    Route::get('/dosen/bimbingan', fn () => '')->name('dosen.bimbingan.index');

    Route::get('/pic-perusahaan/dashboard', fn () => 'Dashboard PIC Perusahaan — Phase 1')->name('pic-perusahaan.dashboard');
    Route::get('/pic-perusahaan/jurnal', fn () => '')->name('pic-perusahaan.jurnal.index');
    Route::get('/pic-perusahaan/laporan', fn () => '')->name('pic-perusahaan.laporan.index');

    Route::get('/admin/dashboard', fn () => 'Dashboard Admin — Phase 1')->name('admin.dashboard');
    Route::get('/admin/pengguna', fn () => '')->name('admin.pengguna.index');
    Route::get('/admin/perusahaan', fn () => '')->name('admin.perusahaan.index');
    Route::get('/admin/periode', fn () => '')->name('admin.periode.index');
    Route::get('/admin/pengaturan', fn () => '')->name('admin.pengaturan.index');
    Route::get('/admin/log', fn () => '')->name('admin.log.index');
});
