**SISTEM INFORMASI MAGANG/PKL**

*Software Requirements Specification (SRS)*

Sistem Informasi Magang/PKL adalah aplikasi web responsif yang dirancang untuk mendigitalkan proses magang/PKL di Politeknik Negeri Madiun, mulai dari pengajuan kelompok, verifikasi, persetujuan dan surat permohonan, penempatan, pemantauan jurnal dan bimbingan, laporan akhir, sampai penilaian. Aplikasi melayani 6 peran pengguna dengan satu basis data.

# Tujuan Sistem Informasi Magang/PKL

- Mendigitalkan proses magang/PKL yang sebelumnya manual, dari pengajuan sampai penilaian akhir.

- Menyediakan alur verifikasi dan persetujuan berjenjang (PIC Magang, KPS) yang tercatat dan dapat ditelusuri.

- Memudahkan penyiapan surat (draf PDF) dan pencatatan surat bertanda tangan basah yang tetap dilakukan di luar sistem.

- Memudahkan pemantauan jurnal, bimbingan, dan progres mahasiswa oleh PIC Magang, KPS, Dosen Pembimbing, dan PIC Perusahaan.

- Menghitung nilai akhir secara otomatis dari nilai PIC Perusahaan dan Dosen Pembimbing.

# Fitur Utama

- Pengajuan magang berkelompok (1 sampai 4 mahasiswa) beserta usulan perusahaan baru.

- Verifikasi berkas oleh PIC Magang dan persetujuan oleh KPS, termasuk pengembalian, revisi, dan penolakan.

- Pembuatan draf Surat Permohonan PKL dan Surat Pengantar Pelaksanaan (PDF) dengan nomor surat yang diinput manual.

- Pencatatan penerimaan, penetapan Dosen Pembimbing, dan pengumuman jadwal serta penempatan.

- Jurnal harian dan mingguan dengan persetujuan Dosen Pembimbing dan PIC Perusahaan.

- Pengajuan bimbingan (offline atau daring) dengan persetujuan dosen dan unggah foto bukti.

- Laporan akhir, legalisasi, dan penilaian (nilai lapangan, nilai pembimbing, nilai akhir otomatis).

- Dashboard pemantauan KPS, rekap permohonan (ekspor Excel/PDF), notifikasi, dan log audit.

# Teknologi

- Laravel 12 (PHP Framework)

- PostgreSQL (Database)

- MaterialPro Bootstrap 5 v10

- Autentikasi bawaan Laravel (halaman login sendiri, tanpa Laravel Breeze atau paket autentikasi siap pakai lainnya)

- Eloquent ORM, Policy/Gate (RBAC), dan Notification System Laravel (notifikasi di aplikasi dan email)

- Penyimpanan berkas privat (storage privat dengan URL bertanda tangan) dan pembuatan PDF untuk draf surat.

Di luar lingkup: layanan SSO (Single Sign-On) kampus yang disediakan pihak kampus, serta seleksi dan keputusan penerimaan oleh perusahaan. Selama SSO belum tersedia, semua peran memakai login lokal, dan alur login disiapkan agar dapat dialihkan ke SSO tanpa mengubah modul lain.

# Role

**Admin Sistem**

- Mengelola peran pengguna, master data, dan pengaturan sistem.

- Mengisi status aktif dan IPK mahasiswa selama SSO belum tersedia.

- Melihat log audit.

**Mahasiswa**

- Melengkapi profil dan mengajukan magang bersama kelompok.

- Mengusulkan perusahaan baru dan mengunggah dokumen pendukung.

- Mengisi jurnal, mengajukan bimbingan, serta mengunggah laporan akhir.

**PIC Magang**

- Memverifikasi berkas dan data perusahaan, menolak atau meminta revisi.

- Mengunggah surat bertanda tangan, mencatat penerimaan, membuat akun PIC Perusahaan, dan menetapkan Dosen Pembimbing.

- Membatalkan pengajuan, mengumumkan jadwal dan penempatan, serta memantau jurnal dan bimbingan.

**KPS**

- Menyetujui atau mengembalikan pengajuan dan mengisi nomor Surat Permohonan PKL.

- Menandatangani surat, memantau progres mahasiswa, dan melegalisasi laporan akhir.

- Menerima laporan dan nilai, lalu menandai magang Selesai.

**Dosen Pembimbing**

- Menyetujui atau meminta revisi jurnal, pengajuan bimbingan, dan laporan akhir mahasiswa bimbingannya.

- Memberi nilai pembimbing.

**PIC Perusahaan**

- Menyetujui jurnal dan laporan akhir mahasiswa.

- Memberi nilai lapangan dan mengisi lembar saran dan masukan.

- Memakai akun lokal (satu per perusahaan) yang dibuat PIC Magang.

# Fitur

## 1. Admin Sistem

### Manajemen Pengguna dan Peran

- Mengelola akun pengguna dan peran (RBAC).

- Mengelola status aktif dan IPK mahasiswa selama SSO belum tersedia.

### Master Data

- Mengelola data master yang dipakai modul lain.

### Pengaturan Sistem

- Periode pengajuan (tanggal buka dan tutup, bawaan dua minggu).

- Jadwal Pelaksanaan PKL.

- Bobot nilai akhir (bawaan 50% nilai lapangan, 50% nilai dosen).

- Template surat.

### Log Audit

- Melihat riwayat seluruh aksi penting di sistem.

## 2. Mahasiswa

### Profil

- Melengkapi nama, jenis kelamin, program studi, kelas, NPM, alamat sesuai KTP, alamat tempat tinggal saat studi, nomor HP, dan email.

- Data profil dipakai otomatis untuk Biodata Mahasiswa (FORM PKL 003).

### Pengajuan Magang Kelompok

- Memilih periode pengajuan yang sedang dibuka dan memilih perusahaan.

- Membentuk kelompok 1 sampai 4 mahasiswa; anggota dipilih dari mahasiswa terdaftar lewat pencarian NPM.

- Sistem menolak anggota yang tidak aktif, ber-IPK di bawah 2,00, atau sudah tergabung di kelompok aktif lain.

- Mengeluarkan anggota selama pengajuan masih draft.

- Mengisi form pengajuan (pengganti FORM PKL 001), mengunggah proposal, lalu menekan Ajukan.

### Usulan Perusahaan Baru

- Mengisi nama, alamat, kota/kabupaten, provinsi, telepon, bidang pekerjaan, serta contact person, jabatan, dan nomor HP jika ada.

- Pengajuan boleh diajukan sebelum usulan diverifikasi.

### Dokumen Pendukung

- Mengunggah PDF (dengan batas ukuran): proposal, Surat Jawaban Perusahaan, surat balasan penolakan, FORM PKL 004, dan FORM PKL 009.

### Status, Riwayat, dan Surat

- Melihat status dan riwayat catatan penolakan, serta mengajukan ulang pada pengajuan yang sama.

- Mengunduh Surat Permohonan PKL bertanda tangan setelah diunggah PIC Magang.

### Jurnal Harian dan Mingguan

- Mengisi jurnal harian (tanggal, uraian kegiatan, jam, lampiran foto).

- Mengajukan jurnal satu minggu untuk disetujui Dosen Pembimbing dan PIC Perusahaan.

### Bimbingan

- Mengajukan bimbingan dengan jadwal dan tipe offline atau daring.

- Bimbingan daring wajib mencantumkan tautan Google Meet atau Zoom; bimbingan offline wajib mencantumkan tanggal dan hari pelaksanaan.

- Mengunggah foto bukti bimbingan (JPG/PNG) hanya setelah pengajuan disetujui Dosen Pembimbing.

### Laporan Akhir

- Mengunggah laporan akhir dan merevisinya berulang kali sesuai catatan.

## 3. PIC Magang

### Antrean Berkas

- Daftar antrean berkas dan pratinjau detail berkas.

- Aksi: setujui, tolak dengan catatan, tolak seutuhnya, serta meninjau ulang pengajuan yang dikembalikan KPS.

### Manajemen Perusahaan

- CRUD perusahaan dan verifikasi usulan perusahaan baru sebelum berkas dinilai.

### Surat dan Penerimaan

- Mengunggah hasil pindai surat bertanda tangan basah.

- Mencatat penerimaan, membuat akun PIC Perusahaan bila belum ada, serta mengisi nomor dan membuat draf Surat Pengantar Pelaksanaan.

- Membatalkan pengajuan (dari status surat_terbit atau diterima) dengan catatan dan surat balasan penolakan.

### Penempatan dan Pengumuman

- Menetapkan Dosen Pembimbing bagi tiap mahasiswa dalam kelompok.

- Mengumumkan Jadwal Pelaksanaan PKL, daftar Dosen Pembimbing, dan daftar perusahaan.

### Rekap dan Pemantauan

- Rekap Permohonan untuk PIC Magang dan KPS, dengan ekspor Excel/PDF.

- Memantau jurnal dan bimbingan semua mahasiswa.

## 4. KPS

### Dashboard

Menampilkan ringkasan pemantauan, seperti:

- Statistik per periode, perusahaan, dan status.

- Progres jurnal, bimbingan, dan keterlambatan.

### Persetujuan Pengajuan

- Melihat pengajuan yang disetujui PIC Magang, lalu menyetujuinya dan mengisi nomor surat.

- Sistem membuat draf Surat Permohonan PKL (FORM PKL 002) otomatis tanpa tanda tangan.

- Mengembalikan pengajuan ke PIC Magang dengan catatan (status dikembalikan_kps).

### Laporan Akhir dan Penyelesaian

- Melegalisasi laporan akhir.

- Menerima laporan dan kedua nilai, lalu menandai magang mahasiswa Selesai.

## 5. Dosen Pembimbing

### Mahasiswa Bimbingan

- Daftar mahasiswa bimbingan; semua proses jurnal, bimbingan, laporan, dan nilai dilakukan per mahasiswa.

### Jurnal Mingguan

- Menyetujui atau meminta revisi jurnal mingguan.

### Bimbingan

- Meninjau pengajuan bimbingan, lalu menyetujui (approve) atau meminta revisi dengan catatan.

### Laporan Akhir dan Nilai

- Menyetujui atau meminta revisi laporan akhir.

- Menginput nilai pembimbing (FORM PKL 007): kelengkapan jurnal 40% dan tata tulis laporan 60%.

## 6. PIC Perusahaan

### Akses

- Login dengan akun lokal (email dan kata sandi) dan wajib mengganti kata sandi awal.

### Jurnal dan Laporan Akhir

- Menyetujui atau meminta revisi jurnal mingguan.

- Menyetujui atau meminta revisi laporan akhir.

### Penilaian dan Masukan

- Menginput nilai lapangan (FORM PKL 006) dengan enam kriteria berbobot.

- Mengisi lembar saran dan masukan (FORM PKL 008).

## 7. Fitur Umum (Semua Role)

- Login dengan email dan kata sandi, serta kontrol akses berbasis peran dan policy.

- Notifikasi di aplikasi dan email untuk setiap perubahan status dan keterlambatan tenggat.

- Responsif untuk ponsel dan desktop, berbahasa Indonesia.

# Implementasi dengan Laravel:

- Gunakan Controller untuk menangani request dari user, Service untuk logika bisnis, dan Repository untuk interaksi dengan database.

- Manfaatkan fitur Eloquent ORM Laravel untuk memudahkan manipulasi data, dengan soft delete pada data transaksi.

- Gunakan MaterialPro Bootstrap 5 v10 untuk styling dan komponen UI interaktif.

- Implementasikan autentikasi bawaan Laravel (tanpa Breeze) dan otorisasi berbasis Policy untuk membatasi akses berdasarkan role; alur login disiapkan untuk dialihkan ke SSO kampus.

- Menggunakan Notification System Laravel untuk notifikasi dalam aplikasi dan email.

- Simpan berkas di storage privat dan sajikan lewat URL bertanda tangan; validasi MIME dan ukuran lewat Form Request.

- Tegakkan aturan satu kelompok aktif per mahasiswa di Service dan lewat constraint database; catat aksi penting ke log audit.

# Contoh Implementasi Alur dengan Controller-Service-Repository:

- **Controller:** PengajuanController (menerima request Ajukan dari mahasiswa)

- **Service:** PengajuanService (memvalidasi anggota kelompok: aktif, IPK minimal 2,00, belum di kelompok aktif lain; mengubah status; mengirim notifikasi ke PIC Magang)

- **Repository:** PengajuanRepository (menyimpan pengajuan, anggota kelompok, dan riwayat status ke database)

Dengan alur ini, kode program akan lebih terstruktur, mudah diuji, dan dipelihara.
