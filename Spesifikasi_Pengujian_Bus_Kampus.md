# Spesifikasi dan Prosedur Pengujian (Sistem Bus Kampus Non-Merdeka)

Dokumen ini merupakan adaptasi pengujian formal (berdasarkan referensi format standar dari *Test Specifications & Procedures*) yang dirancang khusus untuk memvalidasi fitur-fitur pada **Sistem Informasi Tiket Bus Kampus Non-Merdeka — Versi 3.2 (April 2026)**, termasuk penambahan modul **Aksesibilitas WCAG 2.1**, **Keamanan Multi-Peran (UserMiddleware)**, dan **Pengujian Halaman Error**.

---

## 1. Kriteria Lulus/Gagal Fitur

Setiap perbedaan atau _bug_ yang teridentifikasi selama pengujian diklasifikasikan ke dalam salah satu dari tiga jenis tingkat keparahan berikut:

| Tingkat Keparahan | Keterangan | Contoh pada Sistem Bus Kampus |
| :--- | :--- | :--- |
| **Kritis (Critical)** | Ketidaksesuaian yang menghentikan sistem sepenuhnya atau menyebabkan _crash_. | Database error saat checkout tiket, _server memory leak_ pada peta simulasi 24 jam. |
| **Besar (Major)** | Fitur utama tidak berfungsi sebagaimana mestinya namun aplikasi tidak _crash_. | Pengguna berhasil memesan ganda (double-booking) padahal aturan melarang, Auto-finish tidak menghapus ikon dari peta, Toolbar aksesibilitas tidak menyimpan preferensi. |
| **Kecil (Minor)** | Kesalahan kecil yang tidak mengganggu fungsionalitas inti sistem. | Typo pada pesan notifikasi SweetAlert, jarak spasi (UI/UX layout) tidak sempurna di perangkat _mobile_, outline focus indicator tidak terlihat di satu browser. |

---

## 2. Kebutuhan Lingkungan

Berikut adalah perangkat keras dan perangkat lunak minimum yang diperlukan untuk menduplikasi (_cloning_) dan menjalankan skenario pengujian:

| Kebutuhan | Deskripsi / Versi |
| :--- | :--- |
| **Perangkat Keras** | PC/Laptop penguji (RAM 4GB+, CPU setara Core i3/Ryzen 3), Resolusi layar minimal 1024x768. |
| **Perangkat Lunak** | Sistem Operasi: Windows/Mac/Linux. <br> Browser: Google Chrome / Mozilla Firefox (Versi Terbaru). <br> Backend: PHP 8.2+, Laravel 10+, Node.js (untuk kompilasi _Vite_). |
| **Kebutuhan Lainnya** | Kredensial Akun (Tersedia di `akun_simulasi.html`). Jaringan lokal (_localhost:8181_) atau internet jika dihosting. |
| **Alat Aksesibilitas** | axe DevTools (Chrome Extension), NVDA Screen Reader atau VoiceOver (Mac), Keyboard saja (tanpa mouse). |

---

## 3. Spesifikasi dan Prosedur Pengujian

Berinteraksilah dengan aplikasi berbasis web **Bus Kampus Non-Merdeka** menggunakan _browser_ produksi reguler. Berikut adalah langkah kerja (Skenario) operasionalnya:

---

### Kasus Uji 1: Pemesanan Tiket & Proteksi *Double Booking* (UX/UI Validation)
**Keterangan:** Menguji apakah penumpang dapat sukses melakukan pemesanan (Civitas maksimal 4 kursi) dan memverifikasi bahwa sistem menolak pemesanan kedua jika tiket pertama masih berstatus aktif (belum selesai).  
**Prasyarat:** Pengguna masuk dengan akun Civitas valid (`budi@kampus-non-merdeka.ac.id`).

| Langkah | Tindakan Operator | Hasil yang Diharapkan | Hasil yang Diamati | Lulus/Gagal |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Buka menu **Daftar Bus** dan pilih armada dengan status **Antri/Standby**. | Halaman detail bus terbuka, menampilkan sisa kursi, rute keberangkatan, dan tombol _Book Now_. | | [ ] |
| 2 | Selesaikan pemesanan tiket pertama menggunakan metode **E-Tol** dan konfirmasi. | Muncul notifikasi "Sukses", lalu status tiket menjadi "Confirmed". Sistem mencatat pemesanan. | | [ ] |
| 3 | (Uji Proteksi): Kembali ke menu **Dashboard / Daftar Bus**. Klik tombol **Pesan** di bus mana saja. | Sistem harusnya **Mencegah Akses** dan mengeluarkan SweetAlert/Banner *Error*: "Akses ditolak. Anda masih memiliki tiket yang sedang aktif." | | [ ] |

---

### Kasus Uji 2: Pengujian *Auto-Finish* dan Peta Radar Real-time
**Keterangan:** Memastikan bahwa _tracking_ dan _auto-finish_ GIS berbasis waktu bekerja sinkron dengan database, tanpa bug batasan zona waktu.  
**Prasyarat:** Tidak perlu login (buka mode publik di `/map`).  

| Langkah | Tindakan Operator | Hasil yang Diharapkan | Hasil yang Diamati | Lulus/Gagal |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Buka URL `http://localhost:8181/map` pada browser. | Peta Leaflet memuat 13 icon armada. Maksimal hanya 2 bus yang berwarna Hijau (Jalan) per jalur, sisanya Kuning di terminal. | | [ ] |
| 2 | Pantau satu bus (Misal: Bus 08) yang sedang ditumpangi (icon merah muncul padanya) hingga mendekati _Rest_ Gowa / Tamalanrea. | Ikon bergerak setiap 3-5 detik di sepanjang rute poligon yang digambar di peta. | | [ ] |
| 3 | Amati perubahan pada bus tsb ketika tepat masuk ke titik akhir (Terminal Rest). | Sistem mengirim API `auto-finish`. Ikon penumpang merah **Otomatis Hilang**, status bus kembali Standby. | | [ ] |

---

### Kasus Uji 3: Limitasi Pengiriman Tip
**Keterangan:** Menjamin bahwa pengguna (Sivitas/Umum) hanya diperbolehkan memberikan 1x uang tip per minggu (Maks Rp 5.000) untuk mencegah spam donasi.  
**Prasyarat:** Pengguna (contoh: `ani@gmail.com`) telah memesan tiket dan bus telah diaktifkan ke mode "Berjalan" oleh Sopir.

| Langkah | Tindakan Operator | Hasil yang Diharapkan | Hasil yang Diamati | Lulus/Gagal |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Pengguna buka halaman "Tiket Saya", lalu usap ke bagian bawah halaman. | Form pemberian *Tip Apresiasi* muncul karena bus berstatus berjalan. | | [ ] |
| 2 | Input angka 5000 dan klik tombol **Kirim Tip**. | Muncul notifikasi berhasil. Form langsung terkunci otomatis. | | [ ] |
| 3 | Lakukan _refresh_ halaman atau buka tiket lain. | Form Tip tidak muncul lagi. Digantikan oleh _banner_ hijau yang bertuliskan: "Apresiasi Terkirim. Tip berikutnya bisa diberikan setelah hari Senin..." | | [ ] |

---

### Kasus Uji 4: Komputasi Modul Dasbor Keuangan Admin
**Keterangan:** Validasi bahwa kalkulasi rekap pendapatan *Dashboard* hanya menghitung sumbangan dari murni tiket terjual (bukan tip anonim).  
**Prasyarat:** Login sebagai Super Administrator (`admin@kampus-non-merdeka.ac.id`).

| Langkah | Tindakan Operator | Hasil yang Diharapkan | Hasil yang Diamati | Lulus/Gagal |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Arahkan ke rute menu Admin **Laporan Tiket & Keuangan** melalui *sidebar/header*. | Halaman pelaporan (*Report*) terbuka sempurna dengan tiga kotak rekap (Harian, Bulanan, Tahunan). | | [ ] |
| 2 | Verifikasi angka "Pendapatan Hari Ini" dengan total penjualan kursi saat itu. | Angka hanya menjumlahkan `harga kursi x tiket terjual`. Penerimaan donasi (Tip) dikecualikan (terpisah). | | [ ] |
| 3 | Klik opsi eksport **Cetak PDF**. | Browser memicu unduhan laporan dalam format A4 terstruktur (tabel pendapatan dan grafik). | | [ ] |

---

### Kasus Uji 5: Pengujian Halaman Error HTTP *(BARU — v3.0)*
**Keterangan:** Memverifikasi bahwa semua halaman error (401, 403, 404, 419, 500, 503) menampilkan halaman kustom berdesain Kampus Non-Merdeka yang informatif, bukan halaman error bawaan Laravel/server.  
**Prasyarat:** Aplikasi berjalan di `localhost:8181` dengan `APP_ENV=local`. Gunakan route `/test-error/` sebagai dashboard uji.

| Langkah | Tindakan Operator | Hasil yang Diharapkan | Hasil yang Diamati | Lulus/Gagal |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Buka `http://localhost:8181/test-error/` | Dashboard 6 kartu error muncul (401, 403, 404, 419, 500, 503). | | [ ] |
| 2 | Klik masing-masing kartu error secara bergantian. | Setiap error menampilkan halaman kustom berlogo Kampus Non-Merdeka dengan animasi ping, kode error besar, dan dua tombol aksi. | | [ ] |
| 3 | Klik tombol **"Beranda Utama"** di halaman error manapun. | Browser kembali ke halaman utama `/`. | | [ ] |
| 4 | Set `APP_DEBUG=false` → jalankan `php artisan config:clear` → buka `/test-error/500`. | Halaman 500 kustom muncul, **bukan** stack trace Laravel. | | [ ] |
| 5 | Login sebagai user biasa → akses `http://localhost:8181/admin/dashboard`. | Error 403 Forbidden muncul secara natural. | | [ ] |
| 6 | Akses URL tidak terdaftar: `/halaman-tidak-ada-xyzabc`. | Error 404 Not Found muncul secara natural. | | [ ] |

---

### Kasus Uji 6: Aksesibilitas WCAG 2.1 Level AA *(BARU — v3.0)*
**Keterangan:** Memverifikasi implementasi standar aksesibilitas web WCAG 2.1 Level AA pada semua layout dan halaman utama.  
**Prasyarat:** Browser Chrome/Firefox. Opsional: Extension axe DevTools, Screen Reader (NVDA/VoiceOver).

### 5. Pengujian Auto-Finish, Penjadwalan & Waktu Validasi
| ID Uji | Skenario | Langkah Pengujian | Hasil yang Diharapkan |
| :--- | :--- | :--- | :--- |
| TM-01 | Pengujian Trigger Alert Auto-Finish | 1. Sopir memulai perjalanan<br>2. Sampai di rute tujuan<br>3. Relaying map pada client mencapai radius 0. | Menampilkan SweetAlert "Tiba di Tujuan" **HANYA 1 KALI**. Trip di-flag is_completed = true. |
| TM-02 | Pengamanan Sesi Perjalanan Paralel | 1. Buka 2 tab dashboard sopir<br>2. Klik auto-finish di Tab 1 | Tab 2 auto-refresh atau menolak akses eksekusi kedua. Menghindari duplikasi pencatatan. |
| TM-03 | Pengujian Auto-Cancel Geofencing (15 Detik) | 1. Pesan tiket reguler<br>2. Biarkan selama 15 detik tanpa check-in (Validasi GPS). | Job `cleanupExpiredUnconfirmed` otomatis merubah `status` menjadi `cancelled`. Tiket dianggap hangus. |
| TM-04 | Pengujian Penyelamatan Sopir (Driver Revive) | 1. Tiket dibatalkan otomatis oleh timer (15s)<br>2. Sopir menekan "Hadir" di Manifest. | Sistem menerima aksi sopir, status dikembalikan ke `confirmed`, kursi tetap milik penumpang. *(Driver Override)* |
| TM-05 | Kekebalan Prioritas (Immunity Rule) | 1. Pesan tiket Prioritas Tinggi (Kursi Roda)<br>2. Abaikan verifikasi > 15 detik. | Tiket tidak pernah dibatalkan otomatis. *Priority Rule Bypass*. |

| Langkah | Tindakan Operator | Hasil yang Diharapkan | Hasil yang Diamati | Lulus/Gagal |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Buka halaman utama `/` → tekan **Tab** pertama kali. | Muncul link "Lewati ke konten utama" (Skip Navigation) dengan background merah dan outline kuning emas. | | [ ] |
| 2 | Tekan **Tab** berulang → navigasi seluruh navbar, tombol Login/Register tanpa mouse. | Semua elemen interaktif dapat difokus dengan outline merah 3px yang terlihat jelas. | | [ ] |
| 3 | Klik ikon **♿** (pojok kanan bawah) di halaman manapun. | Panel Aksesibilitas muncul dengan opsi: Ukuran Teks (A/A+/A++), Kontras Tinggi, Kurangi Gerak. | | [ ] |
| 4 | Klik **A+** lalu **A++** di panel aksesibilitas. | Ukuran teks seluruh halaman meningkat ke 110% lalu 125%. Preferensi tersimpan setelah reload. | | [ ] |
| 5 | Aktifkan **Kontras Tinggi** di panel aksesibilitas. | Mode kontras tinggi aktif. Teks lebih gelap, kontras meningkat. | | [ ] |
| 6 | Aktifkan **Kurangi Gerak** di panel aksesibilitas. | Semua animasi dan transisi berhenti. | | [ ] |
| 7 | Login sebagai User → pergi ke **Beranda** → periksa sidebar: link aktif. | Link aktif memiliki `aria-current="page"`. Ikon sidebar memiliki `aria-hidden="true"`. | | [ ] |
| 8 | (Opsional) Jalankan **axe DevTools** di halaman `/` dan `/admin/dashboard`. | Tidak ada violations level A atau AA pada elemen yang telah diimplementasikan. | | [ ] |

---

### Kasus Uji 7: Konsistensi Sesi Multi-Peran / *UserMiddleware* *(BARU — v3.2)*
**Keterangan:** Mencegah terjadinya inkonsistensi UI/UX jika *user* membuka *multi-tab* dengan *roles* gabungan (contoh Admin & Sopir tak dapat membuka halaman User secara bersamaan pada browser yang sama).  
**Prasyarat:** Admin login pakai `admin@kampus-non-merdeka.ac.id`.  

| Langkah | Tindakan Operator | Hasil yang Diharapkan | Hasil yang Diamati | Lulus/Gagal |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Buka rute `http://localhost:8181/user/dashboard` secara paksa di URL Bar. | Aplikasi akan melakukan *Redirect Server-Side* yang mengembalikan pengguna ke `admin/dashboard` menghindari tumpang tindih data. | | [ ] |
| 2 | Logout → Login ulang pakai `sopir01@kampus-non-merdeka.ac.id` → tekan back / buka url `/user/buses`. | Aplikasi menolak keras akses laman tersebut dan redirect ke *Dashboard* Sopir milik Hasan Basri. | | [ ] |

---

### Kasus Uji 8: Notifikasi *Smart Read* Hybrid *(BARU — v3.2)*
**Keterangan:** Menguji penghapusan indikator *Badge Unread* agar status baca yang ter-update tidak muncul lagi membebani API walaupun setelah *refresh page*.  
**Prasyarat:** Pesan minimal 1 tiket hingga muncul *badge* merah di Lonceng.  

| Langkah | Tindakan Operator | Hasil yang Diharapkan | Hasil yang Diamati | Lulus/Gagal |
| :---: | :--- | :--- | :--- | :---: |
| 1 | Lihat lonceng ber-*badge* merah/angka → klik untuk membuka Dropdown. | Dropdown akan menunda request API selama jeda 1 detik agar tidak tumpang tindih navigasi. | | [ ] |
| 2 | Setelah 1 detik. | *Badge* merah menghilang seketika ter-sinkronisasi. | | [ ] |
| 3 | *Reload* (*Refresh*) browser menggunakan **F5**. | Lonceng ter-muat ulang bersih **Tanpa** badge merah, memastikan *state read* awet (*Persistence*). | | [ ] |

---

## 4. Matriks Pemetaan Persyaratan (Traceability Matrix)

| ID Persyaratan | Deskripsi Persyaratan | Kasus Uji yang Terverifikasi |
| :--- | :--- | :---: |
| **REQ-BK-001** | Sistem tidak mengizinkan pemesanan lebih dari 1 transaksi tiket per _user_ jika tiket sebelumnya belum diselesaikan (is\_completed=false). | Kasus Uji 1 |
| **REQ-BK-002** | Pengguna umum dibatasi kapasitas tiket maksimum sebanyak 1 *seat*, sedangkan pengguna berstatus 'Civitas' maksimal 4 *seat* | Kasus Uji 1 |
| **REQ-BK-003** | Sistem peta sanggup mengirim deteksi otomatis via AJAX untuk mengubah status akhir tiket pengguna menjadi selesai tanpa tombol manual. | Kasus Uji 2 |
| **REQ-BK-004** | Form tip harus divalidasi dengan _rate-limit_ berbasis waktu (Satu minggu sekali) per _user ID_. | Kasus Uji 3 |
| **REQ-BK-005** | Administrator harus memiliki _interface_ yang khusus memisahkan data _income_ antara ongkos tiket murni versus total tip masuk dari *end-user*. | Kasus Uji 4 |
| **REQ-BK-006** | Semua kode error HTTP (401, 403, 404, 419, 500, 503) harus menampilkan halaman kustom berdesain Kampus Non-Merdeka yang informatif dan dapat diuji melalui route `/test-error/`. | Kasus Uji 5 |
| **REQ-BK-007** | Sistem harus memenuhi standar aksesibilitas WCAG 2.1 Level AA: skip navigation, ARIA landmark roles, focus indicator, accessibility toolbar, dan dukungan `prefers-reduced-motion`. | Kasus Uji 6 |
| **REQ-BK-008** | Aplikasi harus mencegah kerancuan dan tumpang tindih multi-user antar layar Sopir, Admin, dan User via Strict `UserMiddleware`. | Kasus Uji 7 |
| **REQ-BK-009** | Status belum terbaca pada notifikasi wajib otomatis disetel ke terbaca apabila dropdown telah disentuh tanpa pengulangan redundan paska refresh via `LocalStorage`. | Kasus Uji 8 |

> **Catatan Penguji:** Kolom "Hasil yang Diamati" kosong secara _default_. Tim QA/Dosen Penilai dapat mengisinya saat pengujian manual dilakukan, dan mencentang Pass/Fail sesuai hasil _behavior_ aplikasi. Route `/test-error/*` hanya tersedia di environment `local`.
