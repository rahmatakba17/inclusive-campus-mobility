# LAPORAN RENCANA PENGUJIAN dan PERFORMA SISTEM
## Sistem Informasi Tiket Bus Kampus Inclusive — Bus Kampus

**Disusun Oleh:**

Rahmat Akba  
NIM. D082251006

---

## 1. Riwayat Revisi
| Versi | Tanggal | Penyusun | Deskripsi |
|---|---|---|---|
| 2.0 | 25 Apr 2026 | Rahmat Akba | Pembaruan total metrik dan skenario menyesuaikan fitur riil Bus Kampus Inclusive (Telemetri, Akses Tamu, Dasbor Sopir, dan Auto-Cancel). |

## 2. Ruang Lingkup Pengujian (Black-Box Testing)
Pengujian difokuskan pada fungsionalitas riil yang telah dikembangkan di sistem **Bus Kampus Inclusive**.

### 2.1 Di Dalam Cakupan
1) Modul Autentikasi (Admin, Sopir, User).
2) Modul Manajemen Dasbor Admin & Dasbor Sopir.
3) Modul Pemesanan (Sivitas dan Akses Tamu).
4) Modul Telemetri (Pemetaan real-time, pergerakan simulasi halte).
5) Modul UI/UX dan Aksesibilitas (Geofencing Auto-Cancel 15-detik, Priority Seat 5-detik).
6) Fitur Keamanan dan Halaman Error Kustom.

### 2.2 Di Luar Cakupan
1) Integrasi gateway pembayaran (Sistem ini **tidak** menggunakan pembayaran komersial/gratis untuk kampus).
2) Instalasi hardware GPS fisik pada bus (menggunakan simulasi perangkat lunak).

## 3. Lingkungan Pengujian
| Komponen | Spesifikasi |
|---|---|
| Metode Pengujian | Fungsional Black-Box |
| Framework | Laravel 12, Tailwind CSS, AlpineJS |
| URL Dasar | https://bus-inclusive.my.id |
| Akun Penguji | admin@unhas.ac.id, user@unhas.ac.id, sopir08@unhas.ac.id |

## 4. Kriteria Masuk dan Keluar
**Kriteria Keluar (Exit Criteria):** Seluruh kasus uji (28 TC Spesifik Bus Kampus) telah Lulus tanpa ada cacat Kritis yang menghalangi fungsionalitas pemesanan, pelacakan, dan otorisasi.

## 5. Ringkasan Kasus Uji (Black-Box)
| TC-ID | Modul | Deskripsi | Prioritas | Tanda |
|---|---|---|---|---|
| TC-001 | Autentikasi | Login Kredensial Valid (Admin) | Kritis | [+] |
| TC-002 | Autentikasi | Login Kredensial Valid (Penumpang) | Kritis | [+] |
| TC-003 | Autentikasi | Login Kredensial Valid (Sopir) | Kritis | [+] |
| TC-004 | Autentikasi | Akses Tamu (Guest Booking) tanpa Login | Tinggi | [+] |
| TC-005 | Autentikasi | Logout Menghapus Sesi | Tinggi | [+] |
| TC-006 | Manajemen Admin | Tambah Data Bus Baru | Tinggi | [+] |
| TC-007 | Manajemen Admin | Edit Kapasitas & Jadwal | Sedang | [+] |
| TC-008 | Manajemen Admin | Lihat Laporan Pemesanan | Tinggi | [+] |
| TC-009 | Dasbor Sopir | Sopir Memulai Perjalanan (Start Route) | Kritis | [+] |
| TC-010 | Dasbor Sopir | Pembaruan Koordinat Lokasi | Tinggi | [+] |
| TC-011 | Dasbor Sopir | Manual Override Status Kapasitas | Tinggi | [+] |
| TC-012 | Dasbor Sopir | Sopir Mengakhiri Perjalanan | Kritis | [+] |
| TC-013 | Pemesanan Sivitas | Lihat Daftar Bus Polling Real-time | Tinggi | [+] |
| TC-014 | Pemesanan Sivitas | Pemesanan Tiket Standar | Kritis | [+] |
| TC-015 | Pemesanan Sivitas | Batas Waktu Auto-Cancel Geofencing | Tinggi | [+] |
| TC-016 | Pemesanan Sivitas | Kursi Prioritas (Priority Seat Timer) | Tinggi | [+] |
| TC-017 | Pemesanan Sivitas | Pembatalan Tiket oleh Penumpang | Sedang | [+] |
| TC-018 | Akses Tamu | Pengisian Form Akses Tamu | Kritis | [+] |
| TC-019 | Akses Tamu | Validasi Kuota Tamu | Tinggi | [-] |
| TC-020 | Telemetri | Peta Interaktif Menampilkan Marker | Tinggi | [+] |
| TC-021 | Telemetri | Simulasi Halte dan Pemberhentian | Sedang | [+] |
| TC-022 | Notifikasi | Render Notifikasi Real-time | Sedang | [+] |
| TC-023 | UI/UX | Aksesibilitas (A11y) Contrast Ratio | Sedang | [+] |
| TC-024 | Keamanan | Proteksi SQL Injection pada Form Pencarian/Tamu | Kritis | [-] |
| TC-025 | Keamanan | Proteksi XSS pada Nama Tamu | Kritis | [-] |
| TC-026 | Keamanan | Proteksi Endpoint Admin dari Sivitas/Tamu | Kritis | [-] |
| TC-027 | Keamanan | Error 404 Kustom (Branding) | Sedang | [+] |
| TC-028 | Keamanan | Isolasi Race Condition pada Pemesanan | Kritis | [-] |
| TC-029 | Dasbor Sopir | Penyelamatan Sopir (Driver Revive/Override) | Tinggi | [+] |

## 6. Kasus Uji Terperinci dan Instruksi Screenshot Lengkap

### **[TC-001] Login Kredensial Valid (Admin) [+]**
- **Modul**: Autentikasi | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Buka /login
  2. Masukkan email admin@unhas.ac.id
  3. Klik Login
- **Hasil yang Diharapkan**: Diarahkan ke Dasbor Admin.
- **Instruksi Screenshot**: > Screenshot halaman dashboard admin.
- **Hasil Aktual**: TBD

---
### **[TC-002] Login Kredensial Valid (Penumpang) [+]**
- **Modul**: Autentikasi | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Buka /login
  2. Masukkan email user@unhas.ac.id
  3. Klik Login
- **Hasil yang Diharapkan**: Diarahkan ke Dasbor Penumpang.
- **Instruksi Screenshot**: > Screenshot beranda penumpang.
- **Hasil Aktual**: TBD

---
### **[TC-003] Login Kredensial Valid (Sopir) [+]**
- **Modul**: Autentikasi | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Buka /login
  2. Masukkan email sopir08@unhas.ac.id
  3. Klik Login
- **Hasil yang Diharapkan**: Diarahkan ke Dasbor Sopir.
- **Instruksi Screenshot**: > Screenshot halaman kontrol sopir.
- **Hasil Aktual**: TBD

---
### **[TC-004] Akses Tamu (Guest Booking) tanpa Login [+]**
- **Modul**: Autentikasi | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Buka halaman utama sebagai Guest
  2. Klik Pesanan Akses Tamu
- **Hasil yang Diharapkan**: Diarahkan ke form pemesanan tamu tanpa harus login.
- **Instruksi Screenshot**: > Screenshot form pesanan tamu.
- **Hasil Aktual**: TBD

---
### **[TC-005] Logout Menghapus Sesi [+]**
- **Modul**: Autentikasi | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Buka dropdown profil
  2. Klik Logout
- **Hasil yang Diharapkan**: Sesi terhapus dan kembali ke Beranda tamu.
- **Instruksi Screenshot**: > Screenshot halaman beranda pasca logout.
- **Hasil Aktual**: TBD

---
### **[TC-006] Tambah Data Bus Baru [+]**
- **Modul**: Manajemen Admin | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Buka menu Armada
  2. Klik Tambah
  3. Isi detail bus
- **Hasil yang Diharapkan**: Data bus baru tersimpan.
- **Instruksi Screenshot**: > Screenshot daftar bus dengan bus baru.
- **Hasil Aktual**: TBD

---
### **[TC-007] Edit Kapasitas & Jadwal [+]**
- **Modul**: Manajemen Admin | **Prioritas**: Sedang
- **Langkah Pengujian**:
  1. Edit jadwal bus
  2. Ubah kapasitas dari 30 ke 40
- **Hasil yang Diharapkan**: Perubahan tersimpan ke database.
- **Instruksi Screenshot**: > Screenshot notifikasi pembaruan data sukses.
- **Hasil Aktual**: TBD

---
### **[TC-008] Lihat Laporan Pemesanan [+]**
- **Modul**: Manajemen Admin | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Buka menu Laporan Pemesanan
- **Hasil yang Diharapkan**: Daftar seluruh riwayat tiket penumpang dan tamu ditampilkan.
- **Instruksi Screenshot**: > Screenshot tabel laporan pemesanan.
- **Hasil Aktual**: TBD

---
### **[TC-009] Sopir Memulai Perjalanan (Start Route) [+]**
- **Modul**: Dasbor Sopir | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Buka Dasbor Sopir
  2. Klik tombol 'Mulai Perjalanan'
- **Hasil yang Diharapkan**: Status bus berubah menjadi Aktif/Berjalan.
- **Instruksi Screenshot**: > Screenshot tombol kontrol sopir dengan status berjalan.
- **Hasil Aktual**: TBD

---
### **[TC-010] Pembaruan Koordinat Lokasi [+]**
- **Modul**: Dasbor Sopir | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Sistem sopir mengirim koordinat lintang/bujur secara berkala.
- **Hasil yang Diharapkan**: Posisi bus terupdate di database.
- **Instruksi Screenshot**: > Screenshot log jaringan/API update lokasi.
- **Hasil Aktual**: TBD

---
### **[TC-011] Manual Override Status Kapasitas [+]**
- **Modul**: Dasbor Sopir | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Sopir menekan tombol 'Penuh'
- **Hasil yang Diharapkan**: Penumpang tidak bisa lagi memesan bus tersebut.
- **Instruksi Screenshot**: > Screenshot indikator Penuh pada UI sopir.
- **Hasil Aktual**: TBD

---
### **[TC-012] Sopir Mengakhiri Perjalanan [+]**
- **Modul**: Dasbor Sopir | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Sopir tiba di tujuan
  2. Klik 'Selesaikan Perjalanan'
- **Hasil yang Diharapkan**: Bus masuk status Idle, tiket penumpang diarsipkan.
- **Instruksi Screenshot**: > Screenshot armada kembali ke status standby.
- **Hasil Aktual**: TBD

---
### **[TC-013] Lihat Daftar Bus Polling Real-time [+]**
- **Modul**: Pemesanan Sivitas | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Buka halaman utama penumpang
- **Hasil yang Diharapkan**: Daftar bus tampil dengan indikator sisa kursi yang berubah real-time.
- **Instruksi Screenshot**: > Screenshot daftar armada.
- **Hasil Aktual**: TBD

---
### **[TC-014] Pemesanan Tiket Standar [+]**
- **Modul**: Pemesanan Sivitas | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Pilih Bus yang Tersedia
  2. Klik Pesan
  3. Konfirmasi
- **Hasil yang Diharapkan**: Tiket diterbitkan dengan QR Code/Status Confirmed.
- **Instruksi Screenshot**: > Screenshot tiket pesanan.
- **Hasil Aktual**: TBD

---
### **[TC-015] Batas Waktu Auto-Cancel Geofencing [+]**
- **Modul**: Pemesanan Sivitas | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Lakukan pemesanan
  2. Jangan naik bus selama 15 detik
- **Hasil yang Diharapkan**: Pesanan otomatis batal berdasarkan simulasi geofence timer.
- **Instruksi Screenshot**: > Screenshot tiket yang batal otomatis.
- **Hasil Aktual**: TBD

---
### **[TC-016] Kursi Prioritas (Priority Seat Timer) [+]**
- **Modul**: Pemesanan Sivitas | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Bus update status baru
  2. Kursi prioritas tertahan selama 5 detik
- **Hasil yang Diharapkan**: Selama 5 detik kursi tidak bisa dipesan reguler.
- **Instruksi Screenshot**: > Screenshot timer 5-detik di UI prioritas.
- **Hasil Aktual**: TBD

---
### **[TC-017] Pembatalan Tiket oleh Penumpang [+]**
- **Modul**: Pemesanan Sivitas | **Prioritas**: Sedang
- **Langkah Pengujian**:
  1. Buka tiket aktif
  2. Klik Batal
- **Hasil yang Diharapkan**: Kursi dilepas kembali.
- **Instruksi Screenshot**: > Screenshot modal konfirmasi pembatalan.
- **Hasil Aktual**: TBD

---
### **[TC-018] Pengisian Form Akses Tamu [+]**
- **Modul**: Akses Tamu | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Klik Pesanan Tamu
  2. Isi Nama & Tujuan
- **Hasil yang Diharapkan**: Tiket tamu diterbitkan.
- **Instruksi Screenshot**: > Screenshot konfirmasi tiket tamu.
- **Hasil Aktual**: TBD

---
### **[TC-019] Validasi Kuota Tamu [-]**
- **Modul**: Akses Tamu | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Pesan tiket tamu jika bus sudah penuh
- **Hasil yang Diharapkan**: Sistem menolak dengan peringatan bus penuh.
- **Instruksi Screenshot**: > Screenshot error bus penuh untuk tamu.
- **Hasil Aktual**: TBD

---
### **[TC-020] Peta Interaktif Menampilkan Marker [+]**
- **Modul**: Telemetri | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Buka halaman Peta / Map
  2. Amati pergerakan
- **Hasil yang Diharapkan**: Marker bus bergerak di atas peta sesuai koordinat.
- **Instruksi Screenshot**: > Screenshot peta dengan pin marker bus.
- **Hasil Aktual**: TBD

---
### **[TC-021] Simulasi Halte dan Pemberhentian [+]**
- **Modul**: Telemetri | **Prioritas**: Sedang
- **Langkah Pengujian**:
  1. Jalankan SimulationController
  2. Cek update status di Halte 1 ke Halte 2
- **Hasil yang Diharapkan**: Bus tercatat singgah di halte yang diwajibkan.
- **Instruksi Screenshot**: > Screenshot log terminal / UI halte.
- **Hasil Aktual**: TBD

---
### **[TC-022] Render Notifikasi Real-time [+]**
- **Modul**: Notifikasi | **Prioritas**: Sedang
- **Langkah Pengujian**:
  1. Trigger notifikasi baru
- **Hasil yang Diharapkan**: Notifikasi muncul tanpa terpotong.
- **Instruksi Screenshot**: > Screenshot panel notifikasi.
- **Hasil Aktual**: TBD

---
### **[TC-023] Aksesibilitas (A11y) Contrast Ratio [+]**
- **Modul**: UI/UX | **Prioritas**: Sedang
- **Langkah Pengujian**:
  1. Jalankan audit Lighthouse Accessability
- **Hasil yang Diharapkan**: Teks terbaca sesuai standar WCAG AA.
- **Instruksi Screenshot**: > Screenshot skor Lighthouse 100 A11y.
- **Hasil Aktual**: TBD

---
### **[TC-024] Proteksi SQL Injection pada Form Pencarian/Tamu [-]**
- **Modul**: Keamanan | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Masukkan input SQL: ' OR 1=1 --
- **Hasil yang Diharapkan**: Input ditolak atau disanitasi.
- **Instruksi Screenshot**: > Screenshot form menolak injeksi.
- **Hasil Aktual**: TBD

---
### **[TC-025] Proteksi XSS pada Nama Tamu [-]**
- **Modul**: Keamanan | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Masukkan <script>alert(1)</script>
- **Hasil yang Diharapkan**: Teks dirender secara harfiah tanpa eksekusi JS.
- **Instruksi Screenshot**: > Screenshot riwayat tiket menampilkan tag mentah.
- **Hasil Aktual**: TBD

---
### **[TC-026] Proteksi Endpoint Admin dari Sivitas/Tamu [-]**
- **Modul**: Keamanan | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Login sebagai penumpang
  2. Paksa akses rute /admin/buses
- **Hasil yang Diharapkan**: Mendapat error 403 Forbidden.
- **Instruksi Screenshot**: > Screenshot halaman 403 berlogo kampus.
- **Hasil Aktual**: TBD

---
### **[TC-027] Error 404 Kustom (Branding) [+]**
- **Modul**: Keamanan | **Prioritas**: Sedang
- **Langkah Pengujian**:
  1. Kunjungi rute acak /tidak-ada
- **Hasil yang Diharapkan**: Halaman 404 berlogo Bus Kampus Inclusive tampil.
- **Instruksi Screenshot**: > Screenshot halaman 404.
- **Hasil Aktual**: TBD

---
### **[TC-028] Isolasi Race Condition pada Pemesanan [-]**
- **Modul**: Keamanan | **Prioritas**: Kritis
- **Langkah Pengujian**:
  1. Dua pengguna melakukan booking secara bersamaan dalam milidetik.
- **Hasil yang Diharapkan**: Satu pengguna sukses, lainnya ditolak.
- **Instruksi Screenshot**: > Screenshot percobaan ganda (split screen).
- **Hasil Aktual**: TBD

---
### **[TC-029] Penyelamatan Sopir (Driver Revive/Override) [+]**
- **Modul**: Dasbor Sopir | **Prioritas**: Tinggi
- **Langkah Pengujian**:
  1. Tiket batal otomatis oleh timer 15s
  2. Sopir menekan 'Hadir' pada Manifest
- **Hasil yang Diharapkan**: Sistem menerima aksi override, status tiket kembali menjadi 'Tervalidasi'.
- **Instruksi Screenshot**: > Screenshot tombol Hadir yang meng-override timer.
- **Hasil Aktual**: TBD

---
