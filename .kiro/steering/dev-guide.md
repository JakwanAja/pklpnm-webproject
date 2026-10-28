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
| **Arsitektur** | Controller → Service → Repository |
| **Otorisasi** | Laravel Policy/Gate (RBAC per peran) |
| **Notifikasi** | Laravel Notification System (database + email) |
| **Storage** | Private storage, akses via signed URL |
| **Soft Delete** | Wajib pada semua tabel data transaksi (sudah didefinisikan di migration) |

---

## 2. Kondisi Project Saat Ini

> **Phase 0 selesai** — 2026-10-28

### Yang Sudah Ada
- **Database migrations** — 18 tabel sudah termigrasi di PostgreSQL (`pklpnm`).
- **Composer dependencies** — Laravel 12, `barryvdh/laravel-dompdf v3.1.2`, `maatwebsite/excel 3.1.70`.
- **MaterialPro Bootstrap 5 v10** — Aset dicopy ke `public/assets/`.
- **Dokumentasi** — SKPL v0.7 dan SRS tersedia di folder `documentation/`.
- **Model `User`** — sudah disesuaikan (SoftDeletes, fillable, relasi, helper `memenuhinSyaratPkl()`).
- **Layout Blade** — `layouts/app.blade.php` dan `layouts/auth.blade.php` siap pakai.
- **Partial views** — `_sidebar.blade.php` (menu per peran), `_navbar.blade.php`, `_scripts.blade.php`.
- **Helper** — `App\Helpers\PeranHelper` untuk label peran bahasa Indonesia.
- **Seeders** — `PengaturanSeeder` (8 baris default) + `UserSeeder` (5 akun + 1 profil mahasiswa).
- **Routes** — Semua nama route didefinisikan sebagai placeholder; siap diganti controller di Phase 1.
- **Welcome page** — `welcome.blade.php` dengan status infrastruktur (tampil di mode debug).

### Yang Belum Ada (dikerjakan mulai Phase 1)
- Controllers, Services, Repositories
- Models selain `User` (`Perusahaan`, `Periode`, `Pengajuan`, dst.)
- Halaman login, autentikasi, middleware peran
- Policies
- Semua halaman fungsional

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
perusahaan ──< pengajuan >── periode
                    │
                    └──< anggota_pengajuan >── users (mahasiswa)
                                │                     │
                                │               users (dosen_pembimbing)
                                │
                    ┌───────────┼───────────────┐
                    │           │               │
               jurnal_mingguan  bimbingan   laporan_akhir
                    │
               jurnal_harian

users ──── profil_mahasiswa (1:1, hanya mahasiswa)
users ──── perusahaan (1:1, hanya pic_perusahaan via users.perusahaan_id)

penilaian ──< anggota_pengajuan
dokumen   ──< pengajuan (+ opsional ke anggota_pengajuan)
notifications ──< users (polimorfik, standar Laravel)
log_aktivitas ──< users + morphs('subjek')
pengaturan (key-value, standalone)
```

### Daftar Tabel Lengkap

| # | Tabel | Deskripsi Singkat | Soft Delete |
|---|---|---|---|
| 1 | `perusahaan` | Master perusahaan; status: `usulan`, `terverifikasi` | ✓ |
| 2 | `periode` | Periode pengajuan (tanggal buka/tutup & tanggal PKL) | ✓ |
| 3 | `pengaturan` | Key-value pengaturan sistem (bobot nilai, template surat, dll.) | ✗ |
| 4 | `users` | Semua pengguna; kolom `peran` membedakan role | ✓ |
| 5 | `profil_mahasiswa` | Detail profil mahasiswa (1:1 dengan users) | ✗ |
| 6 | `pengajuan` | Pengajuan magang per kelompok per periode | ✓ |
| 7 | `anggota_pengajuan` | Anggota kelompok; pivot mahasiswa ↔ pengajuan | ✓ |
| 8 | `dokumen` | Semua berkas unggahan terkait pengajuan | ✓ |
| 9 | `jurnal_mingguan` | Jurnal mingguan per anggota | ✓ |
| 10 | `jurnal_harian` | Jurnal harian per anggota; terhubung ke jurnal_mingguan | ✓ |
| 11 | `bimbingan` | Pengajuan bimbingan per anggota | ✓ |
| 12 | `laporan_akhir` | Laporan akhir per anggota (versioning) | ✓ |
| 13 | `penilaian` | Nilai lapangan & pembimbing per anggota | ✗ |
| 14 | `log_aktivitas` | Audit log semua aksi penting | ✗ |
| 15 | `notifications` | Notifikasi database standar Laravel | ✗ |

### Status Pengajuan (State Machine)

```
draft
  └─ [Mahasiswa ajukan] → diajukan
        └─ [PIC tolak dengan catatan] → revisi  ──→ diajukan (ajukan ulang)
        └─ [PIC tolak seutuhnya]      → ditolak_final  (terminal)
        └─ [PIC setujui]              → disetujui_pic
              └─ [KPS kembalikan]     → dikembalikan_kps  ──→ disetujui_pic (diteruskan kembali)
              └─ [KPS setujui]        → disetujui_kps
                    └─ [PIC unggah surat TTD] → surat_terbit
                          └─ [PIC catat diterima] → diterima
                                └─ [PKL berjalan]  → berjalan (via anggota_pengajuan.status_magang)
                                └─ [PIC batalkan]  → dibatalkan  (terminal)
                          └─ [PIC batalkan]        → dibatalkan  (terminal)
```

### Status Jurnal Mingguan
`menunggu` → `disetujui` / `revisi` (per dosen dan per perusahaan secara independen)

### Status Bimbingan
`diajukan` → `disetujui` / `revisi`

### Status Laporan Akhir
`menunggu` → `disetujui` / `revisi` (per perusahaan, dosen, dan KPS secara independen)

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

### Pola Wajib: Controller → Service → Repository

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── Mahasiswa/
│   │   ├── PicMagang/
│   │   ├── Kps/
│   │   ├── Dosen/
│   │   ├── PicPerusahaan/
│   │   └── Admin/
│   ├── Requests/          ← Form Request untuk validasi input & MIME berkas
│   └── Middleware/
├── Services/              ← Logika bisnis (status machine, kalkulasi nilai, kirim notifikasi)
├── Repositories/          ← Interaksi database (query Eloquent)
├── Models/
├── Policies/              ← Satu Policy per Model utama
├── Notifications/         ← Kelas notifikasi (database + email)
└── Providers/
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
├── layouts/
│   ├── app.blade.php       ← Layout utama (sidebar MaterialPro)
│   └── auth.blade.php      ← Layout halaman login
├── auth/
│   └── login.blade.php
├── mahasiswa/
├── pic-magang/
├── kps/
├── dosen/
├── pic-perusahaan/
├── admin/
├── components/             ← Blade components (alert, card, modal, dll.)
└── partials/               ← Partial views (sidebar, navbar, dll.)
```

### Integrasi MaterialPro Bootstrap 5 v10

Template MaterialPro adalah pre-built HTML + compiled assets. Strategi integrasi:

1. **Copy aset** dari `materialpro-bt5-v10/dist/assets/` ke `public/assets/` (sekali saat awal setup).
2. **Blade layouts** dibuat berdasarkan struktur HTML di `materialpro-bt5-v10/dist/main/starter.html` sebagai titik awal.
3. Referensi aset di Blade menggunakan helper `asset('assets/...')`.
4. Jangan ubah file di `materialpro-bt5-v10/dist/` — folder itu hanya referensi; semua modifikasi dilakukan di `resources/views/`.

---

## 6. Fitur Utama dan Kode SKPL

| Kode SKPL | Fitur | Phase |
|---|---|---|
| F-01, F-02, F-03, F-04 | Autentikasi & RBAC | 1 |
| F-10 | Profil mahasiswa | 1 |
| F-60, F-61, F-22 | Admin: pengaturan, master data, perusahaan | 1 |
| F-11, F-12, F-13, F-18 | Pengajuan magang (draft & kelompok) | 2 |
| F-20, F-21, F-29, F-14 | PIC Magang: verifikasi berkas & pengumuman | 2 |
| F-30, F-31, F-32, F-33 | KPS: persetujuan & dashboard | 2 |
| F-26, F-27, F-28 | PIC Magang: surat, penerimaan, pembatalan | 2 |
| F-15, F-23 | Surat, penetapan dosen | 2 |
| F-16, F-19 | Jurnal harian & mingguan | 3 |
| F-40, F-50 | Dosen & PIC Perusahaan: review jurnal | 3 |
| F-54, F-55, F-56, F-57 | Bimbingan | 3 |
| F-17 | Laporan akhir | 4 |
| F-41, F-51, F-52, F-53 | Penilaian & legalisasi laporan | 4 |
| F-34 | KPS: selesaikan magang | 4 |
| F-24 | Rekap & ekspor Excel/PDF | 4 |
| F-58 | Notifikasi | (sepanjang development) |
| F-59, F-63 | Log audit & manajemen pengguna Admin | 1 |
| NF-01, NF-02 | Keamanan: CSRF, validasi MIME, signed URL | (sepanjang development) |
| NF-03 | Performa: pagination, eager loading, index DB | (sepanjang development) |
| NF-04 | Responsif, bahasa Indonesia | (sepanjang development) |
| NF-05 | Soft delete | (semua tabel transaksi sudah disiapkan) |

---

## 7. Rencana Pengembangan Bertahap (Phases)

### Phase 0 — Persiapan & Setup Infrastruktur ✅ SELESAI (2026-10-28)
**Tujuan:** Project siap dijalankan, aset terintegrasi, database termigrasi.

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

### Phase 1 — Autentikasi, RBAC, Profil, dan Master Data Admin
**Tujuan:** Semua peran dapat login/logout, admin dapat mengelola data dasar sistem.

**Lingkup:**
- [ ] Model: `User` (perbarui), `ProfilMahasiswa`, `Perusahaan`, `Periode`, `Pengaturan`
- [ ] Autentikasi bawaan Laravel: login (email + password), logout, redirect per peran
- [ ] Middleware guard peran (`CheckPeran`)
- [ ] Policies: `UserPolicy`, `PerusahaanPolicy`, `PeriodePolicy`
- [ ] Halaman profil mahasiswa (isi & edit) — F-10
- [ ] Admin: CRUD pengguna & peran, isi `status_aktif` & `ipk` mahasiswa — F-61, F-63
- [ ] Admin: CRUD periode pengajuan — F-60
- [ ] Admin: CRUD perusahaan — F-22
- [ ] Admin: pengaturan bobot nilai & template surat — F-60
- [ ] Admin: log audit (tampilan) — F-59
- [ ] PIC Perusahaan: wajib ganti password saat login pertama — F-03
- [ ] Seeders: akun admin, pic_magang, kps default untuk testing

**Hasil Akhir:** Login/logout berfungsi untuk semua peran, admin dapat mengelola data master, profil mahasiswa dapat diisi.

---

### Phase 2 — Pengajuan, Verifikasi, dan Penempatan
**Tujuan:** Alur pengajuan magang berjalan penuh dari draft hingga mahasiswa ditempatkan.

**Lingkup:**
- [ ] Model: `Pengajuan`, `AnggotaPengajuan`, `Dokumen`, `LogAktivitas`
- [ ] Service: `PengajuanService` (validasi kelompok: aktif, IPK ≥ 2.00, tidak di kelompok aktif lain; state machine status)
- [ ] Repository: `PengajuanRepository`, `DokumenRepository`
- [ ] Mahasiswa: form pengajuan, pilih periode & perusahaan, tambah anggota via NPM, unggah proposal — F-11, F-12, F-13, F-18
- [ ] Mahasiswa: usulan perusahaan baru — F-12
- [ ] Mahasiswa: lihat status & riwayat, ajukan ulang — F-14
- [ ] PIC Magang: antrean berkas, verifikasi perusahaan baru, setujui/tolak/minta revisi — F-20, F-21, F-22
- [ ] PIC Magang: tinjau ulang pengajuan yang dikembalikan KPS — F-21
- [ ] KPS: daftar pengajuan menunggu persetujuan, setujui + isi nomor surat, kembalikan — F-30, F-32
- [ ] KPS: dashboard statistik periode & status — F-33
- [ ] Generate draf PDF Surat Permohonan PKL (tanpa TTD) — F-31
- [ ] PIC Magang: unggah surat TTD, status → `surat_terbit` — F-26
- [ ] Mahasiswa: unduh surat TTD — F-15
- [ ] PIC Magang: catat penerimaan, buat akun PIC Perusahaan, tetapkan dosen per mahasiswa — F-23, F-27
- [ ] PIC Magang: generate draf Surat Pengantar Pelaksanaan (FORM PKL 003 otomatis dari profil) — F-27
- [ ] PIC Magang: batalkan pengajuan (dari `surat_terbit` atau `diterima`) — F-28
- [ ] PIC Magang: pengumuman jadwal PKL, daftar dosen, daftar perusahaan — F-29
- [ ] Log audit untuk semua perubahan status — F-59
- [ ] Notifikasi perubahan status (database + email) — F-58
- [ ] Rekap Permohonan dengan ekspor Excel/PDF — F-24

**Hasil Akhir:** Seluruh alur dari mahasiswa mengajukan hingga ditempatkan berjalan dan terekam.

---

### Phase 3 — Jurnal dan Bimbingan
**Tujuan:** Mahasiswa dapat mengisi jurnal dan mengajukan bimbingan; dosen dan PIC Perusahaan dapat meresponsnya.

**Lingkup:**
- [ ] Model: `JurnalMingguan`, `JurnalHarian`, `Bimbingan`
- [ ] Service: `JurnalService`, `BimbinganService`
- [ ] Mahasiswa: isi jurnal harian (tanggal, kegiatan, jam mulai-selesai, foto) — F-16
- [ ] Mahasiswa: ajukan jurnal mingguan (kumpulkan jurnal harian 1 minggu) — F-19
- [ ] Dosen Pembimbing: setujui/minta revisi jurnal mingguan — F-40
- [ ] PIC Perusahaan: setujui/minta revisi jurnal mingguan — F-50
- [ ] Mahasiswa: ajukan bimbingan (jadwal, tipe offline/daring, tautan jika daring) — F-54, F-55
- [ ] Dosen Pembimbing: setujui/minta revisi pengajuan bimbingan — F-56
- [ ] Mahasiswa: unggah foto bukti bimbingan (hanya setelah disetujui dosen) — F-57
- [ ] PIC Magang: pantau jurnal & bimbingan semua mahasiswa — F-25
- [ ] KPS: progres jurnal & bimbingan di dashboard — F-33
- [ ] Notifikasi jurnal & bimbingan — F-58

**Hasil Akhir:** Modul jurnal dan bimbingan berjalan lengkap dengan alur persetujuan dua pihak.

---

### Phase 4 — Laporan Akhir, Penilaian, dan Penyelesaian
**Tujuan:** Siklus magang selesai dengan laporan yang dilegalisasi dan nilai akhir terhitung.

**Lingkup:**
- [ ] Model: `LaporanAkhir`, `Penilaian`
- [ ] Service: `LaporanAkhirService`, `PenilaianService` (hitung nilai akhir otomatis)
- [ ] Mahasiswa: unggah laporan akhir, revisi berulang — F-17
- [ ] Dosen Pembimbing: setujui/minta revisi laporan akhir — F-41
- [ ] PIC Perusahaan: setujui/minta revisi laporan akhir — F-52
- [ ] KPS: legalisasi laporan akhir — F-34
- [ ] PIC Perusahaan: input nilai lapangan (6 kriteria berbobot, FORM PKL 006) — F-51
- [ ] Dosen Pembimbing: input nilai pembimbing (2 kriteria berbobot, FORM PKL 007) — F-41
- [ ] PIC Perusahaan: isi lembar saran & masukan (FORM PKL 008) — F-53
- [ ] Mahasiswa: unggah FORM PKL 009 (Surat Keterangan Perusahaan) — F-13
- [ ] Sistem: hitung nilai akhir otomatis (bobot dari tabel `pengaturan`) — SKPL 4.3 poin 5
- [ ] KPS: tandai magang mahasiswa Selesai — F-34
- [ ] Notifikasi laporan & penilaian — F-58
- [ ] Rekap & ekspor Excel/PDF (nilai & laporan) — F-24

**Hasil Akhir:** Siklus magang penuh dari awal hingga akhir berjalan; nilai akhir terhitung otomatis.

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
├── pengajuan/{pengajuan_id}/          ← dokumen pengajuan
├── jurnal/{anggota_id}/               ← foto jurnal harian
├── bimbingan/{anggota_id}/            ← foto bukti bimbingan
└── laporan/{anggota_id}/              ← laporan akhir
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
Nilai Akhir = (Bobot Perusahaan × Total Nilai Lapangan) + (Bobot Dosen × Total Nilai Pembimbing)
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

| Kejadian | Penerima |
|---|---|
| Pengajuan dibuat (status `diajukan`) | PIC Magang |
| Pengajuan disetujui PIC (status `disetujui_pic`) | KPS |
| Pengajuan dikembalikan KPS | PIC Magang |
| Pengajuan disetujui KPS (status `disetujui_kps`) | Mahasiswa |
| Pengajuan diminta revisi PIC | Mahasiswa |
| Pengajuan ditolak final | Mahasiswa |
| Surat TTD diunggah (status `surat_terbit`) | Mahasiswa |
| Penerimaan dicatat, dosen ditetapkan | Mahasiswa, Dosen Pembimbing |
| Pengajuan dibatalkan | Mahasiswa |
| Jurnal mingguan diajukan | Dosen Pembimbing, PIC Perusahaan |
| Jurnal disetujui / diminta revisi | Mahasiswa |
| Pengajuan bimbingan dibuat | Dosen Pembimbing |
| Bimbingan disetujui / diminta revisi | Mahasiswa |
| Laporan akhir diunggah/revisi | Dosen Pembimbing, PIC Perusahaan, KPS |
| Laporan disetujui/diminta revisi oleh salah satu pihak | Mahasiswa |
| Nilai diinput | KPS |
| Magang ditandai Selesai | Mahasiswa |

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

## 15. Dependensi yang Akan Ditambahkan

| Package | Versi | Tujuan |
|---|---|---|
| `barryvdh/laravel-dompdf` | ^3.0 | Generate PDF draf surat |
| `maatwebsite/excel` | ^3.1 | Ekspor rekap Excel & PDF |

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

*Tambahkan entri baru di tabel ini setiap kali ada keputusan teknis penting selama development.*
