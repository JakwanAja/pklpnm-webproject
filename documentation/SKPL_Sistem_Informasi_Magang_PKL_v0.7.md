**SKPL -Sistem Informasi Magang/PKL**

Laravel 12 + PostgreSQL + MaterialPro Bootstrap 5 v10 · Versi 0.7

# 1. Pendahuluan

## 1.1 Tujuan

Mendigitalkan proses magang/PKL yang sebelumnya manual: pengajuan, verifikasi, persetujuan dan surat, penempatan, pemantauan jurnal dan bimbingan, laporan akhir, sampai penilaian.

## 1.2 Ruang Lingkup

Website responsif untuk 6 peran pengguna, dengan satu basis data PostgreSQL, penyimpanan berkas privat, notifikasi, dan pembuatan draf surat PDF. Tampilan antarmuka dikembangkan memakai template MaterialPro Bootstrap 5 v10 sebagai acuan desain.

Di luar lingkup dokumen ini: layanan SSO (Single Sign-On) kampus, yang nantinya disediakan dan ditangani oleh pihak kampus, serta seleksi dan keputusan penerimaan oleh perusahaan. Selama SSO belum tersedia, sistem memakai halaman login sendiri yang dibuat khusus untuk aplikasi ini, tanpa Laravel Breeze maupun paket autentikasi siap pakai lainnya (lihat F-02 sampai F-04). PIC Perusahaan memakai akun lokal yang dibuat PIC Magang. Acuan alur dan formulir adalah Panduan Penulisan PKL, Tugas Akhir dan Skripsi Politeknik Negeri Madiun.

# 2. Pemetaan Formulir Kampus ke Sistem Digital

Formulir FORM PKL 001–010 dari lampiran Panduan PKL ditangani sebagai berikut.

| **Formulir** | **Fungsi**                               | **Penanganan di sistem**                                                                                                                                 |
|--------------|------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------|
| FORM PKL 001 | Permohonan penempatan PKL                | Form pengajuan digital; data mahasiswa dari profil.                                                                                                      |
| FORM PKL 002 | Surat Permohonan PKL ke perusahaan       | Draf PDF dari sistem; tanda tangan basah di luar sistem (lihat 4.6).                                                                                     |
| FORM PKL 003 | Biodata mahasiswa                        | Dibuat otomatis dari profil sebagai lampiran Surat Pengantar Pelaksanaan.                                                                                |
| FORM PKL 004 | Surat pernyataan bermeterai              | Ditandatangani mahasiswa di luar sistem; hasil pindai diunggah.                                                                                          |
| FORM PKL 005 | Jurnal kegiatan mingguan                 | Modul jurnal; legalisasi diganti persetujuan Dosen Pembimbing dan PIC Perusahaan.                                                                        |
| FORM PKL 006 | Penilaian pembimbing lapangan            | Form penilaian PIC Perusahaan.                                                                                                                           |
| FORM PKL 007 | Penilaian Dosen Pembimbing               | Form penilaian Dosen Pembimbing.                                                                                                                         |
| FORM PKL 008 | Saran, tanggapan, dan masukan perusahaan | Form isian PIC Perusahaan setelah PKL.                                                                                                                   |
| FORM PKL 009 | Surat keterangan dari perusahaan         | Diterbitkan perusahaan; hasil pindai diunggah mahasiswa.                                                                                                 |
| FORM PKL 010 | Monitoring dan bimbingan                 | Modul bimbingan: mahasiswa mengajukan jadwal dan tipe bimbingan (offline atau daring), disetujui Dosen Pembimbing, lalu mengunggah foto bukti bimbingan. |

# 3. Aktor dan Hak Akses (RBAC)

| **Aktor**        | **Autentikasi**                                     | **Tugas utama**                                                                                                                                                                                                                                                                                                  |
|------------------|-----------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Mahasiswa        | SSO kampus (sementara: login lokal)                 | Melengkapi profil, mengajukan magang bersama kelompok, mengusulkan perusahaan baru, mengisi jurnal harian, mengajukan bimbingan (jadwal dan tipe offline atau daring), mengunggah foto bukti bimbingan setelah disetujui, mengajukan jurnal mingguan, mengunggah proposal, laporan akhir, dan dokumen pendukung. |
| PIC Magang       | SSO kampus (sementara: login lokal)                 | Memverifikasi berkas dan data perusahaan, menolak atau meminta revisi, mengunggah surat bertanda tangan, mencatat penerimaan, membuat akun PIC Perusahaan, menetapkan Dosen Pembimbing, membatalkan pengajuan, mengumumkan jadwal dan penempatan, memantau jurnal dan bimbingan.                                 |
| KPS              | SSO kampus (sementara: login lokal)                 | Menyetujui atau mengembalikan pengajuan, menandatangani surat, memantau progres mahasiswa, melegalisasi laporan akhir, menerima laporan dan nilai, lalu menandai Selesai.                                                                                                                                        |
| Dosen Pembimbing | SSO kampus (sementara: login lokal)                 | Menyetujui atau meminta revisi jurnal, pengajuan bimbingan, dan laporan akhir mahasiswa bimbingannya, serta memberi nilai.                                                                                                                                                                                       |
| PIC Perusahaan   | Akun lokal (satu per perusahaan, dibuat PIC Magang) | Menyetujui jurnal dan laporan akhir, memberi nilai lapangan, mengisi lembar saran dan masukan.                                                                                                                                                                                                                   |
| Admin Sistem     | SSO kampus (sementara: login lokal)                 | Mengelola peran pengguna, master data, pengaturan sistem, dan log audit.                                                                                                                                                                                                                                         |

# 4. Alur Proses Bisnis

## 4.1 Tahap 1 -Pengajuan, Surat Permohonan, dan Penerimaan

<img src="./media/image1.png" title="Gambar 4.1 Alur pengajuan, surat permohonan, dan penerimaan magang" style="width:3.58392in;height:4.21637in" alt="Gambar 4.1 Alur pengajuan, surat permohonan, dan penerimaan magang" />

**Gambar 4.1 Alur pengajuan, surat permohonan, dan penerimaan magang**

*Biru: di dalam sistem; oranye: di luar sistem; merah: ditolak atau dibatalkan. "Surat pengantar" pada diagram adalah Surat Permohonan PKL. Pengembalian oleh KPS (langkah 5) tidak digambarkan.*

1.  Mahasiswa memilih periode dan perusahaan, lalu mencantumkan seluruh anggota kelompok (1 sampai 4 mahasiswa, termasuk dirinya) dengan memilihnya dari mahasiswa yang sudah terdaftar. Nama anggota tercantum pada pengajuan, berkas, dan surat. Sistem menolak anggota yang tidak aktif, ber-IPK di bawah 2,00, atau sudah tergabung di kelompok aktif lain. Status aktif dan IPK dicek otomatis dari data SSO/kampus.

2.  Jika perusahaan belum ada, mahasiswa mengisi usulan perusahaan baru (nama, alamat, kota/kabupaten, provinsi, telepon, bidang pekerjaan, serta contact person, jabatan, dan nomor HP jika ada). Pengajuan boleh diajukan sebelum usulan diverifikasi, tetapi PIC Magang memverifikasi perusahaan lebih dulu sebelum menilai berkas.

3.  Mahasiswa mengisi form pengajuan (pengganti FORM PKL 001), mengunggah proposal, lalu menekan Ajukan.

4.  PIC Magang memeriksa kelengkapan dan kesesuaian berkas, lalu memilih:

    - Sesuai: status disetujui_pic, pengajuan diteruskan ke KPS.

    - Tolak dengan catatan: mahasiswa memperbaiki dan mengajukan ulang pada pengajuan yang sama; riwayat revisi tersimpan.

    - Tolak seutuhnya: status ditolak_final; mahasiswa membuat pengajuan baru.

5.  KPS menyetujui pengajuan, atau mengembalikannya ke PIC Magang dengan catatan (status dikembalikan_kps). Setelah dikembalikan, PIC Magang meninjau ulang lalu meneruskan kembali, meminta revisi, atau menolak. Saat menyetujui, KPS mengisi nomor surat; sistem menyusun Rekap Permohonan dan draf Surat Permohonan PKL (FORM PKL 002) tanpa tanda tangan.

6.  Surat ditandatangani basah di luar sistem, lalu PIC Magang mengunggah hasil pindainya (lihat 4.5) dan mahasiswa dapat mengunduhnya.

7.  Mahasiswa menyerahkan surat ke perusahaan. Seleksi berlangsung di luar sistem dan perusahaan menjawab secara tertulis (Surat Jawaban Perusahaan).

8.  Jika diterima, mahasiswa melapor dan mengunggah Surat Jawaban Perusahaan. PIC Magang mencatat penerimaan (status diterima), membuat akun PIC Perusahaan bila belum ada, dan menetapkan Dosen Pembimbing bagi tiap mahasiswa. Jadwal Pelaksanaan PKL, daftar Dosen Pembimbing, dan daftar perusahaan diumumkan di sistem.

9.  PIC Magang mengisi nomor surat, lalu sistem membuat draf Surat Pengantar Pelaksanaan berlampiran Biodata Mahasiswa (FORM PKL 003), yang ditandatangani KPS (lihat 4.5). Mahasiswa mengunggah hasil pindai FORM PKL 004, lalu menyerahkan surat dan lampirannya ke perusahaan saat PKL dimulai.

10. Jika ditolak, mahasiswa atau PIC Magang mengunggah surat balasan penolakan dan PIC Magang mengubah status menjadi dibatalkan. Mahasiswa membuat pengajuan baru ke perusahaan lain dengan melampirkan surat balasan tersebut.

## 4.2 Tahap 2 -Pelaksanaan Magang

<img src="./media/image2.png" title="Gambar 4.2 Alur pelaksanaan dan penilaian magang" style="width:4.07116in;height:4.72681in" alt="Gambar 4.2 Alur pelaksanaan dan penilaian magang" />

**Gambar 4.2 Alur pelaksanaan dan penilaian magang**

*"Dosen" adalah Dosen Pembimbing mahasiswa tersebut; "perusahaan" adalah PIC Perusahaan. Gambar ini mencakup tahap 4.2 dan 4.3.*

1.  Mahasiswa mengisi jurnal harian (tanggal, uraian kegiatan, jam, lampiran foto).

2.  Tiap minggu, jurnal harian diajukan sebagai jurnal mingguan. Dosen Pembimbing dan PIC Perusahaan masing-masing menyetujui atau meminta revisi. Persetujuan ini menggantikan legalisasi FORM PKL 005.

3.  Mahasiswa mengajukan bimbingan dengan menentukan jadwal dan memilih tipe bimbingan, yaitu offline atau daring. Untuk bimbingan daring, mahasiswa wajib mencantumkan tautan Google Meet atau Zoom yang akan digunakan. Untuk bimbingan offline, mahasiswa wajib mencantumkan tanggal dan hari pelaksanaan bimbingan.

4.  Dosen Pembimbing meninjau pengajuan bimbingan, lalu menyetujui (approve) atau meminta revisi dengan catatan. Jika diminta revisi, mahasiswa memperbaiki dan mengajukan ulang.

5.  Setelah pengajuan disetujui Dosen Pembimbing, mahasiswa baru dapat mengunggah foto sebagai bukti bimbingan. Selama belum disetujui, unggah foto bukti tidak dapat dilakukan. Bimbingan yang disetujui beserta foto buktinya menggantikan FORM PKL 010.

6.  PIC Magang memantau jurnal dan bimbingan semua mahasiswa; KPS memantau progres (jurnal, bimbingan, keterlambatan) lewat dashboard.

## 4.3 Tahap 3 -Laporan dan Penilaian

1.  Setelah PKL berakhir, tiap mahasiswa mengunggah laporan akhirnya. Laporan dikonsultasikan ke Dosen Pembimbing dan dapat direvisi berulang kali.

2.  PIC Perusahaan, Dosen Pembimbing, dan KPS masing-masing menyetujui (melegalisasi) atau meminta revisi laporan.

3.  PIC Perusahaan mengisi nilai lapangan (FORM PKL 006), setiap kriteria dinilai 1 sampai 100:

| **Kriteria nilai lapangan**                | **Bobot** |
|--------------------------------------------|-----------|
| Penguasaan materi dan pengetahuan          | 10%       |
| Kedisiplinan dan ketekunan                 | 20%       |
| Tanggung jawab dan kejujuran               | 20%       |
| Keterampilan dan hasil pekerjaan           | 15%       |
| Kerja sama tim, kreativitas, dan inisiatif | 15%       |
| Kepribadian                                | 20%       |

4.  Dosen Pembimbing mengisi nilai pembimbing (FORM PKL 007), setiap kriteria dinilai 1 sampai 100:

| **Kriteria nilai pembimbing** | **Bobot** |
|-------------------------------|-----------|
| Kelengkapan jurnal            | 40%       |
| Tata tulis laporan            | 60%       |

5.  Sistem menghitung nilai akhir: 50% nilai PIC Perusahaan + 50% nilai Dosen Pembimbing. Bobot dapat diubah Admin Sistem.

6.  PIC Perusahaan mengisi lembar saran dan masukan (FORM PKL 008); mahasiswa mengunggah surat keterangan perusahaan (FORM PKL 009).

7.  KPS menerima laporan beserta kedua nilai, lalu menandai magang mahasiswa tersebut Selesai.

## 4.4 Aturan Pengajuan dan Kelompok

- Satu mahasiswa hanya boleh tergabung di satu kelompok magang yang aktif; kelompok aktif adalah yang pengajuannya bukan ditolak_final dan bukan dibatalkan. Aturan ini ditegakkan di aplikasi dan di database. Pembuat kelompok mencantumkan seluruh anggota sejak pengajuan dibuat, dan dapat menambah atau mengeluarkan anggota selama pengajuan masih draft.

- Periode pengajuan ditetapkan Admin Sistem (tanggal buka dan tutup), bawaan dua minggu.

- Mahasiswa harus berstatus aktif dan ber-IPK minimal 2,00, diambil dari SSO/data kampus (selama SSO belum tersedia, diisi Admin Sistem).

- Satu kelompok terdiri dari 1 sampai 4 mahasiswa dan dapat memiliki lebih dari satu Dosen Pembimbing; tiap mahasiswa dibimbing satu Dosen Pembimbing yang ditetapkan PIC Magang.

- Pengajuan, surat, dan penempatan berlaku per kelompok. Jurnal, bimbingan, laporan, dan nilai berlaku per mahasiswa dan diproses Dosen Pembimbingnya.

## 4.5 Mekanisme Surat (Tanda Tangan Basah)

Kampus mewajibkan tanda tangan basah, sehingga sistem hanya menyiapkan dan mencatat surat.

1.  Aktor pemroses mengisi nomor surat secara manual: KPS untuk Surat Permohonan PKL, PIC Magang untuk Surat Pengantar Pelaksanaan. Sistem hanya memastikan nomor terisi dan unik, lalu membuat draf PDF berisi nomor surat serta data kelompok dan perusahaan, tanpa tanda tangan.

2.  PIC Magang mencetak draf, lalu KPS menandatangani dan menstempelnya.

3.  PIC Magang mengunggah hasil pindai surat bertanda tangan. Untuk Surat Permohonan PKL, status menjadi surat_terbit dan mahasiswa dapat mengunduhnya.

Lampiran Surat Pengantar Pelaksanaan: Biodata Mahasiswa (FORM PKL 003, otomatis dari profil) dan Surat Pernyataan bermeterai (FORM PKL 004, hasil pindai diunggah mahasiswa).

# 5. SKPL -Kebutuhan Fungsional

## 5.1 Akses

| **Kode** | **Kebutuhan**                                                                                                                                                                                                                                                                                                        |
|----------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| F-01     | Kontrol akses berbasis peran dan policy.                                                                                                                                                                                                                                                                             |
| F-02     | Halaman login sendiri (email dan kata sandi) untuk semua peran selama SSO kampus belum tersedia, dibuat dengan fitur autentikasi bawaan Laravel tanpa Laravel Breeze maupun paket autentikasi siap pakai lainnya. Selama itu, akun pengguna serta status aktif dan IPK mahasiswa dikelola Admin Sistem (lihat F-63). |
| F-03     | Login akun lokal PIC Perusahaan (email dan kata sandi), dengan kewajiban mengganti kata sandi awal.                                                                                                                                                                                                                  |
| F-04     | Integrasi SSO kampus (identitas, peran, status aktif, dan IPK) disediakan dan ditangani pihak kampus. Alur login disiapkan agar dapat dialihkan ke SSO tanpa mengubah modul lain; akun lokal PIC Perusahaan tetap dipertahankan.                                                                                     |

## 5.2 Mahasiswa

| **Kode** | **Kebutuhan**                                                                                                                                                                                                                                                                                                                                                             |
|----------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| F-10     | Melengkapi profil (nama, jenis kelamin, program studi, kelas, NPM, alamat sesuai KTP, alamat tempat tinggal saat studi, nomor HP, email).                                                                                                                                                                                                                                 |
| F-11     | Memilih periode pengajuan yang sedang dibuka.                                                                                                                                                                                                                                                                                                                             |
| F-12     | Memilih perusahaan, atau mengusulkan perusahaan baru.                                                                                                                                                                                                                                                                                                                     |
| F-13     | Mengunggah dokumen pendukung (PDF, dengan batas ukuran): proposal, Surat Jawaban Perusahaan, surat balasan penolakan, FORM PKL 004, dan FORM PKL 009.                                                                                                                                                                                                                     |
| F-14     | Melihat status dan riwayat catatan penolakan, serta mengajukan ulang.                                                                                                                                                                                                                                                                                                     |
| F-15     | Mengunduh Surat Permohonan PKL bertanda tangan setelah diunggah PIC Magang.                                                                                                                                                                                                                                                                                               |
| F-16     | Mengisi jurnal harian (tanggal, uraian kegiatan, jam, lampiran foto).                                                                                                                                                                                                                                                                                                     |
| F-17     | Mengunggah laporan akhir dan merevisinya.                                                                                                                                                                                                                                                                                                                                 |
| F-18     | Membentuk kelompok 1 sampai 4 mahasiswa dengan mencantumkan seluruh anggota saat membuat pengajuan. Anggota dipilih dari mahasiswa yang sudah memiliki akun di sistem (dicari lewat NPM); anggota yang tidak aktif, ber-IPK di bawah 2,00, atau sudah tergabung di kelompok aktif lain ditolak. Pembuat kelompok dapat mengeluarkan anggota selama pengajuan masih draft. |
| F-19     | Mengajukan jurnal satu minggu untuk disetujui Dosen Pembimbing dan PIC Perusahaan.                                                                                                                                                                                                                                                                                        |

## 5.3 PIC Magang

| **Kode** | **Kebutuhan**                                                                                                                      |
|----------|------------------------------------------------------------------------------------------------------------------------------------|
| F-20     | Daftar antrean berkas dan pratinjau detail berkas.                                                                                 |
| F-21     | Aksi: setujui, tolak dengan catatan, tolak seutuhnya, serta meninjau ulang pengajuan yang dikembalikan KPS.                        |
| F-22     | CRUD perusahaan dan verifikasi usulan perusahaan baru sebelum berkas dinilai.                                                      |
| F-23     | Menetapkan Dosen Pembimbing bagi tiap mahasiswa dalam kelompok.                                                                    |
| F-24     | Rekap Permohonan untuk PIC Magang dan KPS, dengan ekspor Excel/PDF.                                                                |
| F-25     | Memantau jurnal dan bimbingan semua mahasiswa.                                                                                     |
| F-26     | Mengunggah hasil pindai surat bertanda tangan basah.                                                                               |
| F-27     | Mencatat penerimaan, membuat akun PIC Perusahaan bila belum ada, serta mengisi nomor dan membuat draf Surat Pengantar Pelaksanaan. |
| F-28     | Membatalkan pengajuan (dari surat_terbit atau diterima) dengan catatan dan surat balasan penolakan.                                |
| F-29     | Mengumumkan Jadwal Pelaksanaan PKL, daftar Dosen Pembimbing, dan daftar perusahaan.                                                |

## 5.4 KPS

| **Kode** | **Kebutuhan**                                                                                                                                         |
|----------|-------------------------------------------------------------------------------------------------------------------------------------------------------|
| F-30     | Melihat pengajuan yang disetujui PIC, lalu menyetujuinya dan mengisi nomor surat.                                                                     |
| F-31     | Pembuatan otomatis draf Surat Permohonan PKL (FORM PKL 002) dengan nomor dari KPS setelah persetujuan. Tanda tangan basah di luar sistem (lihat 4.5). |
| F-32     | Mengembalikan pengajuan ke PIC Magang dengan catatan.                                                                                                 |
| F-33     | Dashboard pemantauan: statistik per periode, perusahaan, dan status, serta progres jurnal, bimbingan, dan keterlambatan.                              |
| F-34     | Melegalisasi laporan akhir, menerima laporan dan kedua nilai, lalu menandai magang mahasiswa Selesai.                                                 |

## 5.5 Dosen Pembimbing dan PIC Perusahaan

| **Kode** | **Kebutuhan**                                                                                                                           |
|----------|-----------------------------------------------------------------------------------------------------------------------------------------|
| F-40     | Dosen: daftar mahasiswa bimbingan; menyetujui atau meminta revisi jurnal mingguan.                                                      |
| F-41     | Dosen: menyetujui atau meminta revisi laporan akhir dan menginput nilai (FORM PKL 007: kelengkapan jurnal 40%, tata tulis laporan 60%). |
| F-50     | PIC Perusahaan: menyetujui atau meminta revisi jurnal mingguan.                                                                         |
| F-51     | PIC Perusahaan: menginput nilai lapangan (FORM PKL 006, enam kriteria berbobot).                                                        |
| F-52     | PIC Perusahaan: menyetujui atau meminta revisi laporan akhir.                                                                           |
| F-53     | PIC Perusahaan: mengisi lembar saran dan masukan (FORM PKL 008).                                                                        |

## 5.6 Bimbingan

| **Kode** | **Kebutuhan**                                                                                                                                                 |
|----------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|
| F-54     | Mahasiswa: mengajukan bimbingan dengan menentukan jadwal dan memilih tipe bimbingan, yaitu offline atau daring.                                               |
| F-55     | Validasi tipe bimbingan: bimbingan daring wajib mencantumkan tautan Google Meet atau Zoom; bimbingan offline wajib mencantumkan tanggal dan hari pelaksanaan. |
| F-56     | Dosen: meninjau pengajuan bimbingan mahasiswa bimbingannya, lalu menyetujui (approve) atau meminta revisi dengan catatan.                                     |
| F-57     | Mahasiswa: mengunggah foto bukti bimbingan (JPG/PNG) hanya setelah pengajuan bimbingan disetujui Dosen Pembimbing.                                            |

## 5.7 Umum

| **Kode** | **Kebutuhan**                                                                                                                  |
|----------|--------------------------------------------------------------------------------------------------------------------------------|
| F-58     | Notifikasi di aplikasi dan email untuk setiap perubahan status dan keterlambatan tenggat.                                      |
| F-59     | Admin: melihat log audit seluruh aksi penting.                                                                                 |
| F-60     | Admin: pengaturan periode pengajuan, Jadwal Pelaksanaan PKL, bobot nilai (bawaan 50% lapangan, 50% dosen), dan template surat. |
| F-61     | Admin: mengelola peran pengguna dan master data.                                                                               |

# 6. SKPL -Kebutuhan Non-Fungsional

| **Kode** | **Kategori** | **Kebutuhan**                                                                                                                                                |
|----------|--------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------|
| NF-01    | Keamanan     | Perlindungan CSRF, validasi input, berkas di storage privat diakses lewat URL bertanda tangan.                                                               |
| NF-02    | Keamanan     | Validasi MIME dan ukuran unggahan (bawaan: PDF maksimal 5 MB, foto jurnal dan foto bukti bimbingan JPG/PNG maksimal 2 MB), serta pencegahan eksekusi berkas. |
| NF-03    | Kinerja      | Halaman utama termuat kurang dari 3 detik pada koneksi umum; pagination, eager loading, dan indeks DB.                                                       |
| NF-04    | Kegunaan     | Responsif (ponsel dan desktop), berbahasa Indonesia, dan memakai template MaterialPro Bootstrap 5 v10 sebagai acuan desain antarmuka.                        |
| NF-05    | Integritas   | Data transaksi tidak dihapus permanen (soft delete).                                                                                                         |
