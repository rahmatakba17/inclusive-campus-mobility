<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Dalam Perbaikan | Bus Kampus UNHAS</title>
    
    <!-- Menggunakan CDN Tailwind khusus untuk halaman maintenance agar layout tetap aman -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
        }
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.4;
            animation: float 10s infinite ease-in-out alternate;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -50px) scale(1.1); }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center relative overflow-hidden text-slate-800">

    <!-- Background Blobs -->
    <div class="blob bg-red-400 w-96 h-96 rounded-full top-[-10%] left-[-10%]"></div>
    <div class="blob bg-amber-300 w-80 h-80 rounded-full bottom-[-10%] right-[-10%]" style="animation-delay: -5s;"></div>

    <div class="relative z-10 w-full max-w-2xl p-6">
        <div class="bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl p-8 md:p-12 text-center border border-white/50">
            
            <!-- Icon/Logo Area -->
            <div class="mx-auto w-24 h-24 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-8 shadow-inner">
                <i class="fas fa-tools text-5xl"></i>
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900 mb-4">
                Pembaruan Sistem Berlangsung
            </h1>
            
            <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                Halo Sivitas Akademika! Kami sedang melakukan pemeliharaan dan peningkatan server <b class="text-red-700">Bus Kampus UNHAS</b> untuk memberikan pelayanan yang lebih baik dan lebih cepat. 
                <br><br>
                Sistem akan kembali online sesaat lagi. Terima kasih atas kesabaran Anda.
            </p>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col md:flex-row items-center justify-center gap-6">
                <div class="flex items-center gap-4 text-left">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Estimasi Waktu</h3>
                        <p class="text-sm text-slate-500">Beberapa menit</p>
                    </div>
                </div>
                
                <div class="hidden md:block w-px h-12 bg-slate-200"></div>
                
                <div class="flex items-center gap-4 text-left">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-hard-hat text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Status Server</h3>
                        <p class="text-sm text-slate-500">Dalam Penanganan</p>
                    </div>
                </div>
            </div>
            
            <!-- Reload Button -->
            <div class="mt-8">
                <button onclick="window.location.reload()" class="bg-red-700 hover:bg-red-800 text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:shadow-red-500/30 transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-sync-alt mr-2"></i> Coba Muat Ulang
                </button>
            </div>
            
        </div>
        
        <div class="text-center mt-8 text-slate-500 text-sm font-medium">
            &copy; 2026 Bus Kampus Integrated System • Universitas Hasanuddin
        </div>
    </div>

</body>
</html>
