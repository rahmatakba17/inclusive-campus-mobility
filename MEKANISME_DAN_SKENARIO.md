# Mekanisme dan Skenario Sistem Bus Kampus Inclusive (Versi 3.2)

Dokumen ini merangkum seluruh kerangka logika, mekanisme kerja arsitektur, dan skenario penggunaan yang ada di dalam aplikasi **Bus Kampus Terintegrasi Universitas Hasanuddin**. Aplikasi ini telah bertransformasi sepenuhnya memfasilitasi kebutuhan transportasi harian menggunakan telemetri *real-time* dan pelacakan asinkron multi-kategori serta sistem pelaporan inspeksi fisik yang mumpuni.

---

## 🏗️ 1. Arsitektur Mekanisme Inti (Core Mechanisms)

Sistem ini ditopang oleh beberapa mekanisme "di balik layar" untuk menjamin operasional bus yang berkelanjutan (*continuous relay*).

### A. Mekanisme Telemetri & Sinkronisasi Radar (*Live Tracking*)
- **Deskripsi:** Semua status kendaraan dipantau secara mandiri lewat `BusSimulationController` dan digambarkan visualnya memanfaatkan **Leaflet.js** pada sisi klien.
- **Polling Otomatis (Asinkron):** Sisi klien (browser pengguna/sopir/admin) melakukan *fetching* asinkron menggunakan AJAX ke *endpoint* `/api/simulation/buses` secara berkala (interval ~1 hingga ~1.5 detik) untuk menghindari perlunya memuat ulang halaman.
- **Isolasi Peta Visual:** Entitas *Marker* bus bergerak dinamis menyesuaikan data lintang/bujur (Latitude/Longitude).

### B. Mekanisme Automasi Penyelesaian Perjalanan (Trip Auto-Finish)
- **Logika Perpindahan Rute:** Jika armada berangkat dari "Terminal Perintis", rute tujuannya otomatis dikalkulasi ke "Gowa", begitu pula sebaliknya.
- **Trigger `autoFinish`:** Ketika sopir menekan tombol "Selesai", sistem akan:
  1. Menandai semua tiket pemesanan yang masih *pending* untuk bus bersangkutan menjadi **Selesai (Completed)** dalam riwayat manifes penumpang secara otomatis.
  2. Ikon penumpang yang terpasang pada map (`Anda di sini`) akan dihilangkan seketika.
  3. Status operasional bus pada basis data dialihkan dari `jalan` beralih istirahat sejenak ke `standby`.

### C. Mekanisme Keuangan & Tip (*Revenue System*)
- Admin dapat memantau akumulasi total jumlah *Seat/Booking*.
- **Pemisahan Finansial:** Tip/Hadiah sukarela dari penumpang kepada sang Sopir **tidak** dimasukkan ke dalam Kalkulasi Pendapatan Bersih Admin Kampus, sehingga hak privasi tunjangan pengemudi tetap terjaga penuh. Fitur anonimitas nama penumpang diberlakukan dalam penyerahan tip agar mencegah diskriminasi antara yang memberi tip dan tidak.

### D. Manajemen Inspeksi Audit & Rekam Jejak Fisik
- **Laporan Harian:** Sistem memandatkan sopir untuk menggunakan fitur Dasbor "Inspeksi Laporan Harian" saat menyudahi serah terima operasional.
- **Rantai Audit Perawatan:** Jika admin panel menangani keluhan, sistem front-end *Alpine.js* segera mem-pop up _text area_ yang mengharuskan pencatatan rekam log rincian teknis "Maintenance" sebelum bus dapat dialih-fungsikan.
- **Isolasi Hapus Cerdas (Smart Delete):** Database takkan mengizinkan admin memusnahkan bus aktif yang sedang merondas secara langsung melainkan divalidasi silang.

---

## 🚦 2. Skenario Perjalanan & Interaksi Pengguna (User Scenarios)

### Skenario 1. Reservasi Reguler Sivitas Akademika (Protokol Baku)
**Konteks:** Seorang mahasiswa jurusan Teknik berada di Tamalanrea dan ingin menuju kampus Teknik di Gowa, tetapi halte terlalu jauh dari posisinya saat ini dan ia belum siap naik bus sekarang, ia butuh kursi kosong terjadwal.
**Alur Kerja:**
1. Mahasiswa mengautentikasi (Log-in) menggunakan `email@unhas.ac.id` ke dalam sistem.
2. Lewat menu **Daftar Bus**, ia melihat telemetri ketersediaan bus beserta parameter kapasitas (misal: Sisa Kursi 15/20).
3. Ia memilih bus terdekat yang berstatus `Standby`.
4. Sistem memverifikasi limitasi dan menukarkan kupon kursi untuk dikonversi menjadi sebuah tiket barcode **(E-Ticket)** seketika ke dalam profil pengguna.
5. **[PENTING - ATURAN SIMULASI 15 DETIK]:** Begitu tiket diterbitkan, argo *grace period* 15 detik mulai berjalan. Mahasiswa/Tamu wajib memencet tombol "Validasi Check-in" di aplikasinya (Geofencing GPS) dalam batas waktu tersebut.
6. Jika melebihi 15 detik dan belum check-in, peladen (server) mengeksekusi otomatis aturan **Auto-Cancel** dan menghanguskan tiket sehingga kursi langsung "hijau" kembali dan bisa direbut penumpang lain.
7. **Penyelamatan Sopir (Driver Override):** Jika penumpang tiba secara fisik pada detik-detik saat sudah di-*cancel*, Sopir dapat menekan tombol hijau bertuliskan **"Hadir (<i class=\"fas fa-check\"></i>)"** pada daftar Manifest Dashboard Sopir untuk mengesampingkan sistem, memulihkan (*revive*) tiket kembali menjadi aktif dan permanen.

### Skenario 2. Akses Insidental Masyarakat Eksternal (Guest Booking)
**Konteks:** Suatu orang tua wali mahasiswa sedang berada di Universitas Hasanuddin tanpa menginstall aplikasi penuh maupun login sistem (Non-Sivitas), lalu ada bus singgah dan ingin segera naik agar tidak diusir sekuriti tanpa kebingungan membuat akun panjang lebar.
**Alur Kerja:**
1. Wali/Tamu menelusuri pranala singkat `guest/buses` atau menggunakan Menu Cepat pada beranda.
2. Memasukkan Nama Perwakilan saja sebagai pendaftar instan (*walk-in user*).
3. Mendapatkan tiket generik **Tanpa Login** yang bersifat satu-kali-pakai (*one-time disposable link*).
4. Penumpang (Tamu) hanya perlu men-*screenshot* halaman konfirmasi hijau tersebut dan menunjukkannya kepada sopir sebagai bukti bahwa ia telah tercatat di *Manifest Guest*.

### Skenario 3. Skenario Penanganan Prioritas Inklusif Berbasis Kredensial
**Konteks:** Seorang penumpang berkursi roda atau lansia memerlukan kursi terdepan yang direkomendasikan WCAG tanpa harus berebut dengan civitas umum, dan ingin kursi tersebut selalu terjamin di tiap armada.
**Alur Kerja:**
1. Penumpang khusus meng-autentikasi (Log-in) menggunakan akun tervalidasi yang memiliki label hak akses Prioritas (misal: kelas `Disabilitas`, `Lansia`, atau `Ibu Hamil`). Khusus pengguna **Prioritas Tinggi (Kursi Roda)**, tiket disubsidi secara penuh menjadi **GRATIS (Rp 0)**.
2. Masuk ke laman pemesanan. *Switch/Toggle* identifikasi diri akan otomatis memverifikasi profil mereka dan **membuka kunci (unlock)** untuk "4 Kursi Eksklusif" di deretan terdepan setiap armada.
3. Sebaliknya, saat kursi prioritas terkunci (*lock* 5-10 detik), UI akan berubah dengan animasi transisi **15 detik khusus mendemonstrasikan status prioritas**.
4. Skenario Kekebalan Khusus (*Immunity Rule*): Tiket pesanan yang mengatasnamakan kategori **Prioritas Tinggi (Kursi Roda)** DIKECUALIKAN dari pembatalan hangus otomatis (*auto-cancel*) 15 detik. Tiket mereka adalah mutlak milik mereka tanpa perlu panik berebut waktu ke terminal geofencing.

### Skenario 4. Sinkronisasi Trip Transisi (Ujung ke Ujung Ekosistem Pelacakan)
**Konteks:** Sopir menancap gas dan memulai perjalanan. Bagaimana layar Map dari seluruh penumpang meresponsnya?
**Alur Kerja:**
1. Pramudi masuk ke akun spesifik `Role: Driver` dan mengeklik "Mulai Perjalanan".
2. Secara simultan dalam rentang kurang dari dua detik (*Polling Relay*):
   - Layar admin meregister siklus mobilitas operasional sebagai indikator "Sedang Jalan / Sedang Meronda".
   - Layar *Handphone* dari seluruh penumpang yang menunggu di halte akan otomatis mendemonstrasikan pergerakan armada menjauhi radius awal (via *Websocket/AJAX pinging Leaflet marker iteration*).
3. Begitu menyentuh palang destinasi, armada auto-terminasi tanpa membingungkan penumpang dengan notifikasi basi. Penumpang auto-menerima piala penyelesaian layanan (Trip Sukses Terdata di Riwayat Akun Penumpang).
4. Usai menyelesaikan semua tugas pada shift tersebut, pada Panel Dasbor, Sopir diwajibkan untuk merepresentasikan Kondisi Akhir Bus dengan cara menjabarkan keluhan dan men-submit "Inspeksi Laporan Harian".

### Skenario 5. Audit Keselamatan Ekosistem dan Evaluasi oleh Administrator
**Konteks:** Sopir men-submit "Perlu Servis" pada laporan inspeksi harian. Bagaimana respon dari Administratif Sistem dalam menyikapi ini?
**Alur Kerja:**
1. Administrator merespons laporan di Panel Fleet Data (Data Bus).
2. Tiba saatnya melakukan konfigurasi, Admin menekan tombok Edit dan merevisi Profil Bus tersebut ke dalam status "Perawatan".
3. Form interaktif dinamis menuntut Administrator mendeskripsikan secara konkrit kerusakan yang perlu diaudit.
4. Laporan akan terus diabadikan pada lembaran Riwayat Perawatan yang terkonsolidasi pada Detail Bus tersebut selamanya, memudahkan *tracking lifetime component*.

---

*(Catatan versi pengembang: Dokumen skenario ini ditulis berdasarkan basis repositori iterasi Bus Kampus Versi 3.2).*
