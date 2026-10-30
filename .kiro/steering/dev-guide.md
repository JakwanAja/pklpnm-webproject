---
inclusion: always
---

# Panduan Pengembangan — Sistem Informasi PKL/Magang

> **File ini adalah acuan wajib dan sumber kebenaran tunggal selama proses development.**
> Perbarui file ini setiap kali ada perubahan kebutuhan, keputusan teknis, atau penyelesaian phase.
> Jangan membuat asumsi di luar dokumen ini. Jika ada informasi yang kurang jelas, tanyakan kepada pengguna sebelum melanjutkan.

---

## 1. Gambaran Umum Project

| Item | Detail |
|---|---|
| **Nama** | Sistem Informasi PKL/Magang — Politeknik Negeri Madiun |
| **Tujuan** | Mendigitalkan proses magang/PKL dari pengajuan sampai penilaian akhir |
| **Framework** | Laravel 12 (PHP ^8.2) |
| **Database** | PostgreSQL |
| **UI Template** | MaterialPro Bootstrap 5 v10 (sudah ada di `materialpro-bt5-v10/dist/`) |
| **Autentikasi** | Bawaan Laravel — tanpa Breeze, Jetstream, atau paket autentikasi lain |
| **PDF** | `barryvdh/laravel-dompdf` |
| **Excel** | `maatwebsite/excel` (Laravel Excel) |
| **Arsitektur** | Controller -> Service -> Repository |
| **Otorisasi** | Laravel Policy/Gate (RBAC per peran) |
| **Notifikasi** | Laravel Notification System (database + email) |
| **Storage** | Private storage, akses via signed URL |
| **Soft Delete** | Wajib pada semua tabel data transaksi (sudah didefinisikan di migration) |

---

## 2. Kondisi Project Saat Ini

> **Phase 0 selesai** — 2026-10-28
> **Phase 1 selesai** — 2026-10-30
> **Phase 2 selesai** — 2026-10-30
> **Phase 3 selesai** — 2026-10-30

### Yang Sudah Ada
- **Database migrations** — 18 tabel sudah termigrasi di PostgreSQL (`pklpnm`).
- **Composer dependencies** — Laravel 12, `barryvdh/laravel-dompdf v3.1.2`, `maatwebsite/excel 3.1.70`.
- **MaterialPro Bootstrap 5 v10** — Aset dicopy ke `public/assets/`.
- **Dokumentasi** — SKPL v0.7 dan SRS tersedia di folder `documentation/`.
- **Model `User`** — sudah disesuaikan (SoftDeletes, fillable, relasi, helper `memenuhinSyaratPkl()`).
- **Model `ProfilMahasiswa`** — dibuat Phase 3 (fillable, casts, relasi belongsTo User).
- **Layout Blade** — `layouts/app.blade.php` dan `layouts/auth.blade.php` siap pakai.
- **Partial views** — `_sidebar.blade.php` (menu per peran), `_navbar.blade.php`, `_scripts.blade.php`.
- **Helper** — `App\Helpers\PeranHelper` untuk label peran bahasa Indonesia.
- **Seeders** — `PengaturanSeeder` (8 baris default) + `UserSeeder` (5 akun + 1 profil mahasiswa).
- **Routes** — Semua route sudah diimplementasikan dengan controller nyata (Phase 1–3); placeholder 503 untuk fitur Phase 4+.
- **Welcome page** — `welcome.blade.php` dengan status infrastruktur (tampil di mode debug).
- **Autentikasi** — `Auth\AuthController` (login/logout), `LoginRequest`, `CheckPeran` middleware.
- **Ganti password** — `WajibGantiPassword` middleware, `GantiPasswordController`, `GantiPasswordRequest`.
- **Dashboard stub** — 6 dashboard per peran (mahasiswa, pic_magang, kps, dosen, pic_perusahaan, admin).
- **Halaman error** — `errors/403.blade.php` untuk akses ditolak.
- **Profil mahasiswa** — `ProfilMahasiswaRepository`, `ProfilMahasiswaService`, `Mahasiswa/ProfilController`, `ProfilMahasiswaRequest`, middleware `ProfilLengkap`, view `mahasiswa/profil/edit.blade.php`.

### Yang Belum Ada (dikerjakan mulai Phase 4)
- Models selain `User` dan `ProfilMahasiswa` (`Perusahaan`, `Periode`, `Pengajuan`, dst.)
- Services dan Repositories selain `ProfilMahasiswaService` dan `ProfilMahasiswaRepository`
- Halaman fungsional (pengajuan, jurnal, bimbingan, laporan, penilaian, dll.)
- Policies

---

## 3. Peran Pengguna (RBAC)

| Nilai `peran` di DB | Nama Peran | Autentikasi |
|---|---|---|
| `admin` | Admin Sistem | Login lokal (sementara, nanti SSO) |
| `pic_magang` | PIC Magang | Login lokal (sementara, nanti SSO) |
| `kps` | KPS | Login lokal (sementara, nanti SSO) |
| `dosen` | Dosen Pembimbing | Login lokal (sementara, nanti SSO) |
| `mahasiswa` | Mahasiswa | Login lokal (sementara, nanti SSO) |
| `pic_perusahaan` | PIC Perusahaan | Login lokal selamanya (akun dibuat PIC Magang) |

**Catatan SSO:** Kolom `sso_id` di tabel `users` sudah disiapkan untuk integrasi SSO di masa depan. Alur login harus dibuat agar SSO dapat dihubungkan tanpa mengubah modul lain. Implementasi SSO sendiri di luar scope project ini.

---

## 4. Skema Database

### Tabel dan Relasi Utama

```
perusahaan --< pengajuan >-- periode
                    |
                    +--< anggota_pengajuan >-- users (mahasiswa)
                                |                     |
                                |               users (dosen_pembimbing)
                                |
                    +-----------+-----------+
                    |           |           |
               jurnal_mingguan  bimbingan   laporan_akhir
                    |
               jurnal_harian

users ---- profil_mahasiswa (1:1, hanya mahasiswa)
users ---- perusahaan (1:1, hanya pic_perusahaan via users.perusahaan_id)

penilaian --< anggota_pengajuan
dokumen   --< pengajuan (+ opsional ke anggota_pengajuan)
notifications --< users (polimorfik, standar Laravel)
log_aktivitas --< users + morphs('subjek')
pengaturan (key-value, standalone)
```

### Daftar Tabel Lengkap

| # | Tabel | Deskripsi Singkat | Soft Delete |
|---|---|---|---|
| 1 | `perusahaan` | Master perusahaan; status: `usulan`, `terverifikasi` | Y |
| 2 | `periode` | Periode pengajuan (tanggal buka/tutup & tanggal PKL) | Y |
| 3 | `pengaturan` | Key-value pengaturan sistem (bobot nilai, template surat, dll.) | N |
| 4 | `users` | Semua pengguna; kolom `peran` membedakan role | Y |
| 5 | `profil_mahasiswa` | Detail profil mahasiswa (1:1 dengan users) | N |
| 6 | `pengajuan` | Pengajuan magang per kelompok per periode | Y |
| 7 | `anggota_pengajuan` | Anggota kelompok; pivot mahasiswa <-> pengajuan | Y |
| 8 | `dokumen` | Semua berkas unggahan terkait pengajuan | Y |
| 9 | `jurnal_mingguan` | Jurnal mingguan per anggota | Y |
| 10 | `jurnal_harian` | Jurnal harian per anggota; terhubung ke jurnal_mingguan | Y |
| 11 | `bimbingan` | Pengajuan bimbingan per anggota | Y |
| 12 | `laporan_akhir` | Laporan akhir per anggota (versioning) | Y |
| 13 | `penilaian` | Nilai lapangan & pembimbing per anggota | N |
| 14 | `log_aktivitas` | Audit log semua aksi penting | N |
| 15 | `notifications` | Notifikasi database standar Laravel | N |

### Status Pengajuan (State Machine)

```
draft
  +- [Mahasiswa ajukan] -> diajukan
        +- [PIC tolak dengan catatan] -> revisi --> diajukan (ajukan ulang)
        +- [PIC tolak seutuhnya]      -> ditolak_final  (terminal)
        +- [PIC setujui]              -> disetujui_pic
              +- [KPS kembalikan]     -> dikembalikan_kps --> disetujui_pic (diteruskan kembali)
              +- [KPS setujui]        -> disetujui_kps
                    +- [PIC unggah surat TTD] -> surat_terbit
                          +- [PIC catat diterima] -> diterima
                                +- [PKL berjalan]  -> berjalan (via anggota_pengajuan.status_magang)
                                +- [PIC batalkan]  -> dibatalkan  (terminal)
                          +- [PIC batalkan]        -> dibatalkan  (terminal)
```

### Status Jurnal Mingguan
`menunggu` -> `disetujui` / `revisi` (per dosen dan per perusahaan secara independen)

### Status Bimbingan
`diajukan` -> `disetujui` / `revisi`

### Status Laporan Akhir
`menunggu` -> `disetujui` / `revisi` (per perusahaan, dosen, dan KPS secara independen)

### Tipe Dokumen (`dokumen.jenis`)
| Nilai | Keterangan | Siapa Upload |
|---|---|---|
| `proposal` | Proposal magang | Mahasiswa |
| `surat_jawaban` | Surat Jawaban Perusahaan | Mahasiswa |
| `surat_penolakan` | Surat Balasan Penolakan | Mahasiswa / PIC Magang |
| `form_004` | FORM PKL 004 (Surat Pernyataan bermeterai) | Mahasiswa |
| `form_009` | FORM PKL 009 (Surat Keterangan Perusahaan) | Mahasiswa |
| `surat_permohonan_ttd` | Surat Permohonan PKL bertanda tangan | PIC Magang |
| `surat_pengantar_ttd` | Surat Pengantar Pelaksanaan bertanda tangan | PIC Magang |

---

## 5. Arsitektur Kode

### Pola Wajib: Controller -> Service -> Repository

```
app/
+-- Http/
|   +-- Controllers/
|   |   +-- Auth/
|   |   +-- Mahasiswa/
|   |   +-- PicMagang/
|   |   +-- Kps/
|   |   +-- Dosen/
|   |   +-- PicPerusahaan/
|   |   +-- Admin/
|   +-- Requests/          <- Form Request untuk validasi input & MIME berkas
|   +-- Middleware/
+-- Services/              <- Logika bisnis (status machine, kalkulasi nilai, kirim notifikasi)
+-- Repositories/          <- Interaksi database (query Eloquent)
+-- Models/
+-- Policies/              <- Satu Policy per Model utama
+-- Notifications/         <- Kelas notifikasi (database + email)
+-- Providers/
```

**Aturan:**
- Controller hanya menerima request, memanggil Service, dan mengembalikan response/view.
- Service menangani logika bisnis: validasi aturan kelompok, perubahan status, kalkulasi nilai, pengiriman notifikasi.
- Repository menangani query Eloquent. Tidak ada query langsung di Controller.
- Semua validasi input menggunakan Form Request.
- Semua akses berkas privat melalui signed URL.

### Struktur Views (Blade)

```
resources/views/
+-- layouts/
|   +-- app.blade.php       <- Layout utama (sidebar MaterialPro)
|   +-- auth.blade.php      <- Layout halaman login
+-- auth/
|   +-- login.blade.php
+-- mahasiswa/
+-- pic-magang/
+-- kps/
+-- dosen/
+-- pic-perusahaan/
+-- admin/
+-- components/             <- Blade components (alert, card, modal, dll.)
+-- partials/               <- Partial views (sidebar, navbar, dll.)
```

### Integrasi MaterialPro Bootstrap 5 v10

Template MaterialPro adalah pre-built HTML + compiled assets. Strategi integrasi:

1. **Copy aset** dari `materialpro-bt5-v10/dist/assets/` ke `public/assets/` (sekali saat awal setup).
2. **Blade layouts** dibuat berdasarkan struktur HTML di `materialpro-bt5-v10/dist/main/starter.html` sebagai titik awal.
3. Referensi aset di Blade menggunakan helper `asset('assets/...')`.
4. Jangan ubah file di `materialpro-bt5-v10/dist/` — folder itu hanya referensi; semua modifikasi dilakukan di `resources/views/`.

---

## 6. Peta Fitur ke Phase

| Kode SKPL | Fitur | Phase |
|---|---|---|
| F-01, F-02, F-04 | Autentikasi & RBAC: login, logout, middleware, redirect per peran | 1 |
| F-03 | PIC Perusahaan: wajib ganti password pertama kali | 2 |
| F-10 | Mahasiswa: profil (isi & edit) | 3 |
| F-63 | Admin: manajemen pengguna & peran | 4 |
| F-60 | Admin: CRUD periode pengajuan & pengaturan sistem (bobot nilai) | 5 |
| F-22 | Admin & PIC Magang: CRUD perusahaan (master) | 5 |
| F-59 | Admin: tampilan log audit | 5 |
| F-11, F-12, F-18 | Mahasiswa: buat pengajuan kelompok (draft, pilih periode & perusahaan, tambah anggota) | 6 |
| F-13 | Mahasiswa: unggah dokumen pendukung (proposal, FORM 004) | 7 |
| F-14 | Mahasiswa: lihat status & riwayat, ajukan, ajukan ulang | 7 |
| F-20, F-21 | PIC Magang: antrean berkas, verifikasi, setujui/tolak/revisi/kembalikan KPS | 8 |
| F-30, F-32, F-33 | KPS: persetujuan pengajuan, isi nomor surat, kembalikan, dashboard statistik | 9 |
| F-31 | Generate draf PDF Surat Permohonan PKL (FORM PKL 002) | 9 |
| F-26, F-15 | PIC Magang: upload surat TTD -> mahasiswa unduh (status `surat_terbit`) | 10 |
| F-27, F-23 | PIC Magang: catat penerimaan, buat akun PIC Perusahaan, tetapkan dosen, generate Surat Pengantar | 10 |
| F-29 | PIC Magang: pengumuman jadwal & penempatan | 10 |
| F-28 | PIC Magang: batalkan pengajuan | 10 |
| F-58a | Notifikasi perubahan status pengajuan (Phase 6-10) | 10 |
| F-16 | Mahasiswa: jurnal harian (isi, edit, lampiran foto) | 11 |
| F-19 | Mahasiswa: ajukan jurnal mingguan | 11 |
| F-40, F-50 | Dosen & PIC Perusahaan: review & setujui/revisi jurnal mingguan | 12 |
| F-54, F-55, F-56 | Mahasiswa & Dosen: pengajuan bimbingan + persetujuan (offline/daring, validasi tautan) | 13 |
| F-57 | Mahasiswa: unggah foto bukti bimbingan (hanya setelah disetujui) | 13 |
| F-58b | Notifikasi jurnal & bimbingan (Phase 11-13) | 13 |
| F-17 | Mahasiswa: upload laporan akhir & revisi berulang | 14 |
| F-41, F-52, F-34 | Dosen, PIC Perusahaan, KPS: review & legalisasi laporan akhir; KPS tandai Selesai | 15 |
| F-51, F-53 | PIC Perusahaan: nilai lapangan (FORM PKL 006) + saran masukan (FORM PKL 008) | 16 |
| F-41b | Dosen: nilai pembimbing (FORM PKL 007); sistem hitung nilai akhir otomatis | 16 |
| F-58c | Notifikasi laporan & penilaian (Phase 14-16) | 16 |
| F-24 | Rekap & ekspor Excel/PDF (permohonan + nilai) | 17 |
| NF-01, NF-02 | Keamanan: CSRF, validasi MIME, signed URL | (diterapkan sepanjang development) |
| NF-03 | Performa: pagination, eager loading, index DB | (diterapkan sepanjang development) |
| NF-04 | Responsif, bahasa Indonesia | (diterapkan sepanjang development) |
| NF-05 | Soft delete | (semua tabel transaksi sudah disiapkan) |

---

## 7. Rencana Pengembangan Bertahap (Phases)

> **Prinsip:** 1 phase = 1 fitur yang dapat diuji secara mandiri.
> Setiap phase memiliki **Prasyarat**, **Lingkup**, dan **Kriteria Selesai** yang jelas.
> Selesaikan dan uji setiap phase sebelum melanjutkan ke phase berikutnya.

---

### Phase 0 — Persiapan & Setup Infrastruktur SELESAI (2026-10-28)

**Prasyarat:** —

**Hasil:**
- [x] Dependensi: `barryvdh/laravel-dompdf v3.1.2`, `maatwebsite/excel 3.1.70`
- [x] Database PostgreSQL `pklpnm` termigrasi lengkap (18 tabel)
- [x] Aset MaterialPro dicopy ke `public/assets/`
- [x] Layout Blade: `layouts/app.blade.php`, `layouts/auth.blade.php`
- [x] Partials: `_sidebar.blade.php`, `_navbar.blade.php`, `_scripts.blade.php`
- [x] `App\Helpers\PeranHelper` dibuat
- [x] Seeder: pengaturan default (8 baris) + 5 akun dev + 1 profil mahasiswa
- [x] Route placeholder semua nama route sudah terdefinisi
- [x] `welcome.blade.php` menampilkan status infrastruktur

---

### Phase 1 — Autentikasi & RBAC
> **Phase 1 selesai** — 2026-10-30

**Fitur yang diuji:** Semua peran dapat login dan logout; akses halaman dibatasi per peran.

**Prasyarat:** Phase 0 selesai.

**Lingkup:**
- [x] `AuthController`: login (POST email+password), logout
- [x] Form Request: `LoginRequest` (validasi email & password)
- [x] Middleware `CheckPeran`: cegah akses lintas peran
- [x] Redirect pasca login ke dashboard sesuai peran
- [x] Halaman login (`auth/login.blade.php`) menggunakan layout `auth.blade.php`
- [x] Dashboard stub per peran (6 halaman sederhana, cukup "Selamat datang, [nama]")
- [x] Proteksi semua route dengan `middleware('auth')` + `middleware('peran:...')`

**Kriteria Selesai:**
- Login berhasil untuk semua 5 peran akun dev
- Akses URL peran lain di-redirect ke halaman 403
- Logout menghapus sesi dan redirect ke halaman login

---

### Phase 2 — Ganti Password PIC Perusahaan
> **Phase 2 selesai** — 2026-10-30

**Fitur yang diuji:** PIC Perusahaan wajib ganti password saat login pertama sebelum mengakses sistem.

**Prasyarat:** Phase 1 selesai.

**Lingkup:**
- [x] Middleware `WajibGantiPassword`: redirect jika `wajib_ganti_password = true`
- [x] `Auth/GantiPasswordController`: tampilkan form, proses ganti, set flag ke false
- [x] Halaman ganti password (`auth/ganti-password.blade.php`)
- [x] Form Request: `GantiPasswordRequest`

**Kriteria Selesai:**
- PIC Perusahaan dengan `wajib_ganti_password = true` diarahkan ke form ganti password setelah login
- Setelah ganti password berhasil, dapat mengakses dashboard normal
- Peran lain tidak terpengaruh middleware ini

---

### Phase 3 — Profil Mahasiswa
> **Phase 3 selesai** — 2026-10-30

**Fitur yang diuji:** Mahasiswa dapat mengisi dan mengedit data profilnya lengkap.

**Prasyarat:** Phase 1 selesai.

**Lingkup:**
- [x] Model: `ProfilMahasiswa` (relasi hasOne dari User)
- [x] Repository: `ProfilMahasiswaRepository`
- [x] `Mahasiswa/ProfilController`: show (lihat profil), edit, update
- [x] Form Request: `ProfilMahasiswaRequest` (semua field wajib)
- [x] View: `mahasiswa/profil/edit.blade.php`
- [x] Mahasiswa belum isi profil -> redirect ke halaman profil saat akses halaman lain

**Kriteria Selesai:**
- Mahasiswa dapat menyimpan semua field profil (nama, NPM, prodi, kelas, jenis kelamin, alamat KTP, alamat domisili, no HP)
- Data tersimpan di `profil_mahasiswa`
- Validasi: semua field wajib, NPM unik

---

### Phase 4 — Manajemen Pengguna (Admin)
**Fitur yang diuji:** Admin dapat mengelola akun semua peran dan mengisi data akademik mahasiswa.

**Prasyarat:** Phase 1 selesai.

**Lingkup:**
- [ ] Repository: `UserRepository`
- [ ] Service: `UserService`
- [ ] `Admin/PenggunaController`: index, create, store, edit, update, destroy (soft delete)
- [ ] Form Request: `BuatPenggunaRequest`, `UpdatePenggunaRequest`
- [ ] Policy: `UserPolicy`
- [ ] Views: `admin/pengguna/` (index tabel + filter peran, form buat, form edit)
- [ ] Admin dapat update `status_aktif` dan `ipk` di `profil_mahasiswa`
- [ ] Setiap aksi dicatat ke `log_aktivitas`

**Kriteria Selesai:**
- Admin dapat membuat, mengedit, menonaktifkan, soft-delete akun
- Filter berdasarkan peran berfungsi
- IPK dan status aktif mahasiswa dapat diupdate lewat form edit
- Aksi tercatat di `log_aktivitas`

---

### Phase 5 — Master Data Admin (Periode, Perusahaan, Pengaturan, Log Audit)
**Fitur yang diuji:** Admin dapat mengelola data master sistem dan melihat seluruh log audit.

**Prasyarat:** Phase 4 selesai.

**Lingkup:**
- [ ] Model: `Periode`, `Perusahaan`, `Pengaturan`, `LogAktivitas`
- [ ] Repository: `PeriodeRepository`, `PerusahaanRepository`, `PengaturanRepository`, `LogAktivitasRepository`
- [ ] `Admin/PeriodeController`: CRUD periode (tanggal buka/tutup + tanggal PKL)
- [ ] `Admin/PerusahaanController`: CRUD perusahaan master, ubah status usulan -> terverifikasi
- [ ] `Admin/PengaturanController`: edit bobot nilai & nama institusi
- [ ] `Admin/LogController`: tampilkan log audit dengan pagination + filter
- [ ] Policy: `PeriodePolicy`, `PerusahaanPolicy`

**Kriteria Selesai:**
- Admin dapat membuka/menutup periode
- Admin dapat mengelola perusahaan master dan verifikasi perusahaan usulan
- Bobot nilai tersimpan dan dapat dibaca ulang
- Log audit menampilkan riwayat dengan filter tanggal/aksi

---

### Phase 6 — Buat Pengajuan Kelompok (Draft)
**Fitur yang diuji:** Mahasiswa dapat membuat pengajuan magang kelompok (1-4 orang) dalam status draft.

**Prasyarat:** Phase 3 dan Phase 5 selesai.

**Lingkup:**
- [ ] Model: `Pengajuan`, `AnggotaPengajuan`
- [ ] Repository: `PengajuanRepository`, `AnggotaPengajuanRepository`
- [ ] Service: `PengajuanService` — validasi anggota (aktif, IPK >= 2.00, belum di kelompok aktif lain, maks 4 orang)
- [ ] `Mahasiswa/PengajuanController`: index, create, store
- [ ] Form Request: `BuatPengajuanRequest`
- [ ] Views: `mahasiswa/pengajuan/index.blade.php`, `mahasiswa/pengajuan/create.blade.php`
- [ ] Pencarian anggota via NPM (endpoint JSON sederhana)
- [ ] Mahasiswa dapat mengeluarkan anggota selama status masih `draft`
- [ ] Mahasiswa dapat mengusulkan perusahaan baru (status -> `usulan`)

**Kriteria Selesai:**
- Pengajuan tersimpan di status `draft`
- Sistem menolak anggota tidak aktif / IPK < 2.00 / sudah di kelompok lain
- Batas 4 anggota ditegakkan
- Mahasiswa tidak bisa buat pengajuan baru jika punya kelompok aktif

---

### Phase 7 — Unggah Dokumen & Ajukan Pengajuan
**Fitur yang diuji:** Mahasiswa dapat mengunggah proposal dan mengajukan pengajuan dari draft ke diajukan.

**Prasyarat:** Phase 6 selesai.

**Lingkup:**
- [ ] Model: `Dokumen`
- [ ] Repository: `DokumenRepository`
- [ ] Service: `DokumenService` — simpan ke storage privat, generate signed URL
- [ ] `Mahasiswa/PengajuanController`: show (detail + status + riwayat), update status -> diajukan
- [ ] `Mahasiswa/DokumenController`: store (unggah), destroy (hapus saat draft)
- [ ] Form Request: `UnggahDokumenRequest` (PDF, maks 5 MB, MIME check)
- [ ] Views: `mahasiswa/pengajuan/show.blade.php`

**Kriteria Selesai:**
- Upload PDF berhasil; file non-PDF atau > 5 MB ditolak dengan pesan error
- Signed URL berfungsi untuk preview/download dokumen
- Tombol Ajukan hanya aktif jika proposal sudah ada
- Status berubah ke `diajukan` setelah diklik

---

### Phase 8 — Verifikasi Berkas (PIC Magang)
**Fitur yang diuji:** PIC Magang dapat memverifikasi perusahaan baru dan mengambil keputusan atas setiap pengajuan.

**Prasyarat:** Phase 7 selesai.

**Lingkup:**
- [ ] Service: `VerifikasiService` — transisi status (setujui, tolak catatan, tolak final)
- [ ] `PicMagang/PengajuanController`: index (antrean + filter), show, setujui, tolak, revisi, tinjau ulang (dari dikembalikan_kps)
- [ ] `PicMagang/PerusahaanController`: verifikasi usulan (status -> terverifikasi)
- [ ] Form Request: `KeputusanPengajuanRequest`
- [ ] Views: `pic-magang/pengajuan/`
- [ ] Log audit setiap perubahan status
- [ ] Notifikasi ke mahasiswa (revisi/tolak) dan KPS (disetujui_pic)

**Kriteria Selesai:**
- Tiga aksi berfungsi dan mengubah status dengan benar
- Verifikasi perusahaan baru berfungsi
- Notifikasi terkirim ke pihak terkait
- Log audit tercatat untuk setiap keputusan

---

### Phase 9 — Persetujuan KPS & Draf Surat Permohonan PKL
**Fitur yang diuji:** KPS dapat menyetujui atau mengembalikan pengajuan; sistem men-generate draf PDF otomatis.

**Prasyarat:** Phase 8 selesai.

**Lingkup:**
- [ ] `Kps/PengajuanController`: index, show, setujui (isi nomor surat), kembalikan
- [ ] Service: `KpsService` — transisi ke `disetujui_kps` atau `dikembalikan_kps`
- [ ] `PdfService`: generate Surat Permohonan PKL (FORM PKL 002) via DomPDF
- [ ] Blade template PDF: `pdf/surat-permohonan.blade.php`
- [ ] Form Request: `PersetujuanKpsRequest` (nomor surat wajib saat setujui)
- [ ] `Kps/DashboardController`: statistik jumlah pengajuan per status
- [ ] Views: `kps/pengajuan/`, `kps/dashboard.blade.php`
- [ ] Notifikasi ke PIC Magang (dikembalikan) dan mahasiswa (disetujui_kps)

**Kriteria Selesai:**
- KPS dapat setujui dengan nomor surat; status -> `disetujui_kps`
- Nomor surat tersimpan unik
- PDF draf dapat di-preview/download (tanpa TTD)
- Kembalikan dengan catatan berfungsi; status -> `dikembalikan_kps`
- Dashboard statistik tampil

---

### Phase 10 — Surat TTD, Penerimaan, Penempatan & Pembatalan
**Fitur yang diuji:** Seluruh alur pasca persetujuan KPS hingga mahasiswa resmi ditempatkan atau dibatalkan.

**Prasyarat:** Phase 9 selesai.

**Lingkup:**
- [ ] `PicMagang/SuratController`: upload surat TTD (status -> surat_terbit), generate Surat Pengantar Pelaksanaan PDF
- [ ] `PicMagang/PenerimaanController`: catat penerimaan (status -> diterima), buat akun PIC Perusahaan, tetapkan dosen per mahasiswa
- [ ] `PicMagang/PengumumanController`: kelola pengumuman jadwal PKL, daftar dosen, daftar perusahaan
- [ ] `PicMagang/PembatalanController`: batalkan dari `surat_terbit` atau `diterima` -> `dibatalkan`
- [ ] `Mahasiswa/SuratController`: unduh surat TTD via signed URL
- [ ] `PdfService`: generate Surat Pengantar Pelaksanaan + Biodata (FORM PKL 003 dari profil mahasiswa)
- [ ] Blade template PDF: `pdf/surat-pengantar.blade.php`
- [ ] Notifikasi ke mahasiswa & dosen saat ditempatkan; notifikasi pembatalan

**Kriteria Selesai:**
- PIC Magang upload surat TTD; mahasiswa dapat mengunduhnya
- Akun PIC Perusahaan dibuat dengan `wajib_ganti_password = true`
- Dosen ditetapkan per mahasiswa (bukan per kelompok)
- PDF Surat Pengantar + Biodata dapat di-generate
- Pembatalan dari dua status berbeda berfungsi

---

### Phase 11 — Jurnal Harian & Mingguan (Mahasiswa)
**Fitur yang diuji:** Mahasiswa aktif magang dapat mengisi jurnal harian dan mengajukan jurnal mingguan.

**Prasyarat:** Phase 10 selesai.

**Lingkup:**
- [ ] Model: `JurnalHarian`, `JurnalMingguan`
- [ ] Repository: `JurnalHarianRepository`, `JurnalMingguanRepository`
- [ ] Service: `JurnalService` — validasi tanggal unik, kumpulkan jurnal harian ke minggu
- [ ] `Mahasiswa/JurnalHarianController`: CRUD (tanggal, kegiatan, jam mulai-selesai, foto opsional)
- [ ] `Mahasiswa/JurnalMingguanController`: index, create (pilih rentang minggu), store (ajukan)
- [ ] Form Request: `JurnalHarianRequest` (foto JPG/PNG maks 2 MB jika ada), `AjukanJurnalRequest`
- [ ] Views: `mahasiswa/jurnal/`
- [ ] Foto disimpan di `storage/app/private/jurnal/{anggota_id}/`

**Kriteria Selesai:**
- Satu entri jurnal per tanggal per mahasiswa
- Foto opsional — hanya JPG/PNG <= 2 MB
- Jurnal mingguan yang sudah diajukan tidak bisa diedit
- Status jurnal mingguan awal -> `menunggu` (dosen & perusahaan)

---

### Phase 12 — Review Jurnal Mingguan (Dosen & PIC Perusahaan)
**Fitur yang diuji:** Dosen dan PIC Perusahaan dapat mereview jurnal secara independen.

**Prasyarat:** Phase 11 selesai.

**Lingkup:**
- [ ] `Dosen/JurnalController`: index, show, setujui, revisi (update `status_dosen`)
- [ ] `PicPerusahaan/JurnalController`: index, show, setujui, revisi (update `status_perusahaan`)
- [ ] Service: `JurnalService` — update status kolom masing-masing secara independen
- [ ] `PicMagang/PemantauanController`: ringkasan progres jurnal semua mahasiswa aktif
- [ ] Notifikasi ke mahasiswa saat jurnal disetujui/diminta revisi
- [ ] Views: `dosen/jurnal/`, `pic-perusahaan/jurnal/`, `pic-magang/pemantauan.blade.php`

**Kriteria Selesai:**
- Dosen update `status_dosen`; PIC Perusahaan update `status_perusahaan` — independen satu sama lain
- PIC Magang melihat ringkasan progres jurnal semua mahasiswa
- Notifikasi terkirim ke mahasiswa

---

### Phase 13 — Bimbingan (Pengajuan, Persetujuan & Foto Bukti)
**Fitur yang diuji:** Mahasiswa mengajukan bimbingan; dosen menyetujuinya; mahasiswa mengunggah foto bukti.

**Prasyarat:** Phase 12 selesai.

**Lingkup:**
- [ ] Model: `Bimbingan`
- [ ] Repository: `BimbinganRepository`
- [ ] Service: `BimbinganService` — validasi tipe daring wajib tautan; cegah upload foto sebelum disetujui
- [ ] `Mahasiswa/BimbinganController`: index, create, store, upload foto bukti
- [ ] `Dosen/BimbinganController`: index, show, setujui, revisi
- [ ] Form Request: `AjukanBimbinganRequest`, `UploadFotoBuktiRequest` (JPG/PNG maks 2 MB)
- [ ] Foto disimpan di `storage/app/private/bimbingan/{anggota_id}/`
- [ ] Notifikasi ke dosen saat diajukan; ke mahasiswa saat disetujui/revisi
- [ ] Views: `mahasiswa/bimbingan/`, `dosen/bimbingan/`

**Kriteria Selesai:**
- Bimbingan daring tanpa tautan ditolak (validasi + DB constraint)
- Foto bukti tidak bisa diupload sebelum status `disetujui`
- DB check constraint tidak dilanggar
- Notifikasi berfungsi

---

### Phase 14 — Laporan Akhir (Upload & Revisi)
**Fitur yang diuji:** Mahasiswa dapat mengupload laporan akhir dan merevisinya berulang kali.

**Prasyarat:** Phase 13 selesai.

**Lingkup:**
- [ ] Model: `LaporanAkhir`
- [ ] Repository: `LaporanAkhirRepository`
- [ ] Service: `LaporanAkhirService` — manajemen versi (auto-increment `versi`)
- [ ] `Mahasiswa/LaporanController`: index (riwayat versi), create, store
- [ ] Form Request: `UploadLaporanRequest` (PDF maks 5 MB)
- [ ] Berkas disimpan di `storage/app/private/laporan/{anggota_id}/`
- [ ] Notifikasi ke dosen, PIC Perusahaan, KPS saat laporan baru diupload
- [ ] Views: `mahasiswa/laporan/`

**Kriteria Selesai:**
- Mahasiswa dapat upload laporan versi 1
- Setelah ada catatan, dapat upload versi baru
- Riwayat semua versi tampil dengan status masing-masing per pihak

---

### Phase 15 — Review Laporan Akhir & Penyelesaian Magang
**Fitur yang diuji:** Tiga pihak mereview laporan; KPS menandai magang Selesai.

**Prasyarat:** Phase 14 selesai.

**Lingkup:**
- [ ] `Dosen/LaporanController`: index, show, setujui, revisi
- [ ] `PicPerusahaan/LaporanController`: index, show, setujui, revisi
- [ ] `Kps/LaporanController`: index, show, legalisasi, tandai Selesai
- [ ] Service: `LaporanAkhirService` — update status per kolom; `AnggotaPengajuanService` — set `status_magang = 'selesai'`
- [ ] Views: `dosen/laporan/`, `pic-perusahaan/laporan/`, `kps/laporan/`
- [ ] Notifikasi ke mahasiswa setiap ada keputusan

**Kriteria Selesai:**
- Tiga pihak review independen
- KPS baru bisa tandai Selesai setelah ketiga status `disetujui`
- `anggota_pengajuan.status_magang` berubah ke `selesai`

---

### Phase 16 — Penilaian Akhir
**Fitur yang diuji:** PIC Perusahaan dan Dosen mengisi nilai; sistem menghitung nilai akhir otomatis.

**Prasyarat:** Phase 15 selesai.

**Lingkup:**
- [ ] Model: `Penilaian`
- [ ] Repository: `PenilaianRepository`
- [ ] Service: `PenilaianService` — hitung total_nilai per penilai; hitung nilai_akhir di anggota_pengajuan
- [ ] `PicPerusahaan/PenilaianController`: form 6 kriteria FORM PKL 006 + form saran masukan FORM PKL 008
- [ ] `Dosen/PenilaianController`: form 2 kriteria FORM PKL 007
- [ ] Form Request: `NilaiLapanganRequest`, `NilaiPembimbingRequest` (tiap kriteria 1-100)
- [ ] Notifikasi ke KPS saat kedua nilai sudah diinput
- [ ] Views: `pic-perusahaan/penilaian.blade.php`, `dosen/penilaian.blade.php`

**Kriteria Selesai:**
- Nilai tersimpan sebagai JSONB per kriteria + total_nilai tertimbang
- `nilai_akhir` di `anggota_pengajuan` terhitung otomatis dari bobot di `pengaturan`
- Perubahan bobot di pengaturan tidak mengubah nilai yang sudah dihitung (snapshot)

---

### Phase 17 — Rekap & Ekspor Excel/PDF
**Fitur yang diuji:** PIC Magang dan KPS dapat mengekspor rekap permohonan dan nilai ke Excel/PDF.

**Prasyarat:** Phase 16 selesai.

**Lingkup:**
- [ ] `PicMagang/RekapController`: rekap permohonan (filter periode, status, perusahaan), ekspor Excel & PDF
- [ ] `Kps/RekapController`: rekap nilai akhir per periode, ekspor Excel & PDF
- [ ] Laravel Excel Export classes: `RekapPermohonanExport`, `RekapNilaiExport`
- [ ] Blade template PDF rekap: `pdf/rekap-permohonan.blade.php`, `pdf/rekap-nilai.blade.php`
- [ ] Views: `pic-magang/rekap/`, `kps/rekap/`

**Kriteria Selesai:**
- Ekspor Excel menghasilkan .xlsx dengan data yang benar
- Ekspor PDF menghasilkan file yang dapat dibuka
- Filter periode dan status berfungsi sebelum ekspor

---

## 8. Aturan Bisnis Kritis (Wajib Ditegakkan di Service DAN Database)

1. **Satu kelompok aktif per mahasiswa** — Ditegakkan di `AnggotaPengajuan` via partial unique index di DB (`WHERE aktif AND deleted_at IS NULL`) dan di `PengajuanService`.

2. **Syarat anggota kelompok** — `profil_mahasiswa.status_aktif = true` AND `profil_mahasiswa.ipk >= 2.00`. Dicek di Service saat menambahkan anggota.

3. **Batas kelompok** — 1 sampai 4 mahasiswa per pengajuan. Dicek di Service.

4. **Foto bukti bimbingan** — Hanya boleh diunggah jika `bimbingan.status = 'disetujui'`. Ditegakkan via DB check constraint DAN di `BimbinganService`.

5. **Bimbingan daring** — Wajib ada `tautan`. Ditegakkan via DB check constraint DAN Form Request.

6. **Nomor surat unik** — `nomor_surat_permohonan` dan `nomor_surat_pengantar` di tabel `pengajuan` sudah unique di DB.

7. **Satu PIC Perusahaan per perusahaan** — `users.perusahaan_id` sudah `unique()` di DB.

8. **Soft delete wajib** — Jangan pernah hard-delete data transaksi. Gunakan Eloquent SoftDeletes trait di semua model yang memiliki kolom `deleted_at`.

9. **Foto bukti bimbingan tidak boleh ada jika status bukan `disetujui`** — DB check constraint `bimbingan_foto_disetujui_check` sudah ada di migration.

10. **Anggota yang sama tidak boleh ada dua kali di pengajuan yang sama** — Partial unique index `anggota_pengajuan_kelompok_unik` sudah ada.

---

## 9. Aturan Berkas (Storage & Validasi)

| Jenis Berkas | Format | Ukuran Maks | Storage |
|---|---|---|---|
| Proposal, surat-surat, form PDF | PDF | 5 MB | Private storage |
| Foto jurnal harian | JPG/PNG | 2 MB | Private storage |
| Foto bukti bimbingan | JPG/PNG | 2 MB | Private storage |
| Laporan akhir | PDF | 5 MB | Private storage |

- Semua berkas disimpan di `storage/app/private/` atau subdirektori di dalamnya.
- Akses berkas hanya via signed URL (bukan akses langsung ke path).
- Validasi MIME dan ukuran di Form Request sesuai tabel di atas.
- Cegah eksekusi berkas (jangan simpan di `public/`).

### Struktur Direktori Storage
```
storage/app/private/
+-- pengajuan/{pengajuan_id}/          <- dokumen pengajuan
+-- jurnal/{anggota_id}/               <- foto jurnal harian
+-- bimbingan/{anggota_id}/            <- foto bukti bimbingan
+-- laporan/{anggota_id}/              <- laporan akhir
```

---

## 10. Ketentuan Penilaian

### Nilai Lapangan — FORM PKL 006 (PIC Perusahaan)
| Kriteria | Bobot |
|---|---|
| Penguasaan materi dan pengetahuan | 10% |
| Kedisiplinan dan ketekunan | 20% |
| Tanggung jawab dan kejujuran | 20% |
| Keterampilan dan hasil pekerjaan | 15% |
| Kerja sama tim, kreativitas, dan inisiatif | 15% |
| Kepribadian | 20% |

### Nilai Pembimbing — FORM PKL 007 (Dosen Pembimbing)
| Kriteria | Bobot |
|---|---|
| Kelengkapan jurnal | 40% |
| Tata tulis laporan | 60% |

### Nilai Akhir
```
Nilai Akhir = (Bobot Perusahaan x Total Nilai Lapangan) + (Bobot Dosen x Total Nilai Pembimbing)
```
- Bobot default: 50% lapangan + 50% dosen.
- Bobot dapat diubah Admin melalui tabel `pengaturan` (kunci: `bobot_perusahaan`, `bobot_dosen`).
- Nilai disimpan di `penilaian.nilai` (JSONB per kriteria) dan `penilaian.total_nilai` (hasil tertimbang).
- Nilai akhir disimpan di `anggota_pengajuan.nilai_akhir`.

---

## 11. Formulir Kampus dan Penanganannya

| Formulir | Fungsi | Penanganan di Sistem |
|---|---|---|
| FORM PKL 001 | Permohonan penempatan PKL | Form pengajuan digital |
| FORM PKL 002 | Surat Permohonan PKL | Draf PDF generate otomatis; TTD basah di luar sistem |
| FORM PKL 003 | Biodata mahasiswa | Generate otomatis dari profil sebagai lampiran |
| FORM PKL 004 | Surat pernyataan bermeterai | Diunggah mahasiswa (scan) |
| FORM PKL 005 | Jurnal kegiatan mingguan | Modul jurnal mingguan |
| FORM PKL 006 | Penilaian pembimbing lapangan | Form penilaian PIC Perusahaan |
| FORM PKL 007 | Penilaian Dosen Pembimbing | Form penilaian Dosen Pembimbing |
| FORM PKL 008 | Saran & masukan perusahaan | Form isian PIC Perusahaan pasca PKL |
| FORM PKL 009 | Surat keterangan dari perusahaan | Diunggah mahasiswa (scan) |
| FORM PKL 010 | Monitoring dan bimbingan | Modul bimbingan (pengajuan + persetujuan + foto bukti) |

---

## 12. Surat dan Mekanisme Tanda Tangan Basah

Kampus mewajibkan tanda tangan basah. Sistem hanya menyiapkan draf dan mencatat surat TTD yang diunggah.

| Surat | Nomor Diisi Oleh | Generate Draf | TTD | Upload Scan |
|---|---|---|---|---|
| Surat Permohonan PKL (FORM PKL 002) | KPS (saat persetujuan) | Sistem (otomatis setelah KPS setujui) | KPS + stempel (di luar sistem) | PIC Magang |
| Surat Pengantar Pelaksanaan (+FORM PKL 003) | PIC Magang (saat catat penerimaan) | Sistem | KPS + stempel (di luar sistem) | PIC Magang |

---

## 13. Notifikasi

Notifikasi dikirim via Laravel Notification (channel `database` + `mail`) pada kejadian berikut:

| Kejadian | Penerima | Disisipi di Phase |
|---|---|---|
| Pengajuan dibuat (status `diajukan`) | PIC Magang | 8 |
| Pengajuan disetujui PIC (`disetujui_pic`) | KPS | 8 |
| Pengajuan diminta revisi / ditolak final | Mahasiswa | 8 |
| Pengajuan dikembalikan KPS | PIC Magang | 9 |
| Pengajuan disetujui KPS (`disetujui_kps`) | Mahasiswa | 9 |
| Surat TTD diunggah (`surat_terbit`) | Mahasiswa | 10 |
| Penerimaan dicatat, dosen ditetapkan | Mahasiswa, Dosen Pembimbing | 10 |
| Pengajuan dibatalkan | Mahasiswa | 10 |
| Jurnal mingguan diajukan | Dosen Pembimbing, PIC Perusahaan | 12 |
| Jurnal disetujui / diminta revisi | Mahasiswa | 12 |
| Pengajuan bimbingan dibuat | Dosen Pembimbing | 13 |
| Bimbingan disetujui / diminta revisi | Mahasiswa | 13 |
| Laporan akhir diunggah/revisi | Dosen Pembimbing, PIC Perusahaan, KPS | 14 |
| Laporan disetujui/diminta revisi | Mahasiswa | 15 |
| Nilai lengkap diinput | KPS | 16 |
| Magang ditandai Selesai | Mahasiswa | 15 |

---

## 14. Konvensi Kode

- **Bahasa antarmuka:** Indonesia.
- **Bahasa kode:** English (nama class, method, variabel dalam bahasa Inggris); komentar boleh bahasa Indonesia.
- **Penamaan Model:** PascalCase, singular, sesuai nama tabel (e.g., `Pengajuan`, `AnggotaPengajuan`).
- **Penamaan tabel:** snake_case, sesuai migration yang sudah ada (jangan ubah nama tabel).
- **Route:** Gunakan resource route di mana memungkinkan; prefix per peran (e.g., `/mahasiswa/...`, `/pic-magang/...`).
- **Policy:** Satu Policy per Model utama. Gunakan `$this->authorize()` di Controller.
- **Transaksi DB:** Bungkus operasi multi-tabel dalam `DB::transaction()` di Service.
- **Validasi:** Selalu gunakan Form Request. Jangan validasi manual di Controller.
- **Error handling:** Gunakan exception handling Laravel; tampilkan pesan error yang ramah pengguna dalam bahasa Indonesia.

---

## 15. Dependensi yang Digunakan

| Package | Versi | Tujuan |
|---|---|---|
| `barryvdh/laravel-dompdf` | ^3.1 | Generate PDF draf surat |
| `maatwebsite/excel` | ^3.1 | Ekspor rekap Excel |

Belum ada package tambahan lain. Jangan tambahkan package baru tanpa konfirmasi pengguna.

---

## 16. Hal yang Di Luar Scope (Jangan Diimplementasikan)

- Integrasi SSO kampus (hanya siapkan alur agar bisa disambungkan nanti).
- Proses seleksi dan keputusan penerimaan oleh perusahaan.
- Fitur di luar yang didefinisikan di SKPL v0.7.

---

## 17. Log Perubahan dan Keputusan

| Tanggal | Keputusan | Keterangan |
|---|---|---|
| 2026-10-28 | Integrasi MaterialPro: copy aset ke `public/assets/` | Pre-built HTML template, tidak perlu Vite untuk aset template |
| 2026-10-28 | PDF: `barryvdh/laravel-dompdf` v3.1.2 | Pilihan pengguna; kompatibel Laravel 12 + PHP 8.2 |
| 2026-10-28 | Excel: `maatwebsite/excel` 3.1.70 | Pilihan pengguna; v4.x tidak kompatibel PHP 8.2 (requires ^8.3) |
| 2026-10-28 | Database dev & production: PostgreSQL via Laragon | DB_PASSWORD kosong (Laragon default) |
| 2026-10-28 | APP_URL: `http://pklpnm-webproject.test` | Konvensi virtual host Laragon |
| 2026-10-28 | Phase 0 selesai | Semua infrastruktur siap; lanjut Phase 1 |
| 2026-10-30 | Phase 1-4 dipecah menjadi Phase 1-17 | Prinsip 1 phase = 1 fitur yang dapat diuji secara mandiri |
| 2026-10-30 | Phase 1 selesai | Login/logout & RBAC berfungsi untuk semua peran; middleware `CheckPeran`, `AuthController`, `LoginRequest`, 6 dashboard stub, halaman 403 sudah dibuat |
| 2026-10-30 | Phase 2 selesai | Ganti password wajib PIC Perusahaan berfungsi; `WajibGantiPassword` middleware, `GantiPasswordController`, `GantiPasswordRequest`, view `auth/ganti-password.blade.php`, akun `picperusahaan@pklpnm.test` ditambahkan di seeder |
| 2026-10-30 | Phase 3 selesai | Profil mahasiswa dapat diisi dan diedit; `ProfilMahasiswa` model, `ProfilMahasiswaRepository`, `ProfilMahasiswaService`, `Mahasiswa/ProfilController`, `ProfilMahasiswaRequest`, middleware `ProfilLengkap` |

*Tambahkan entri baru di tabel ini setiap kali ada keputusan teknis penting selama development.*