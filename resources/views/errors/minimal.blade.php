<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Bus Kampus Kampus Non-Merdeka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('vendor/css/all.min.css') }}">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased font-sans text-gray-800 bg-[#fafbfc] min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    {{-- Background Decals --}}
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[60%] rounded-full bg-gradient-to-br from-[#c41e3a]/10 to-transparent blur-3xl"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[40%] h-[50%] rounded-full bg-gradient-to-tl from-[#1e3a5f]/10 to-transparent blur-3xl"></div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="max-w-2xl w-full relative z-10 text-center flex flex-col items-center">
        {{-- Floating Logo --}}
        <div class="relative w-32 h-32 bg-white rounded-full shadow-2xl flex items-center justify-center p-4 mb-10 border-4 border-[#fafbfc] mx-auto transform hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 rounded-full border-2 border-[#1e3a5f]/10 animate-ping" style="animation-duration: 3s;"></div>
            <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" alt="Logo Kampus Non-Merdeka" class="w-full h-auto object-contain">
        </div>

        {{-- Error Card --}}
        <div class="bg-white/80 backdrop-blur-xl w-full p-12 md:p-16 rounded-[3rem] shadow-2xl border border-white/50 relative overflow-hidden group">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-[#1e3a5f] via-[#c41e3a] to-[#ffd700]"></div>
            
            <i class="fas fa-exclamation-triangle absolute -right-10 -bottom-10 text-9xl text-slate-50 opacity-50 transform -rotate-12 group-hover:scale-110 group-hover:rotate-0 transition-transform duration-700"></i>

            <div class="relative z-10">
                <span class="inline-block bg-rose-50 text-rose-600 font-black px-5 py-2 rounded-full text-xs uppercase tracking-[0.3em] mb-4 border border-rose-100">
                    System Error Exception
                </span>
                
                <h1 class="text-7xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-b from-[#1e3a5f] to-[#4a6b98] tracking-tighter leading-none mb-4 drop-shadow-sm">
                    @yield('code')
                </h1>

                <div class="w-16 h-1 bg-gradient-to-r from-[#c41e3a] to-[#ffd700] mx-auto rounded-full mb-8"></div>

                <h2 class="text-2xl md:text-3xl font-black text-slate-800 uppercase tracking-tight mb-2">
                    @yield('message')
                </h2>

                <p class="text-slate-500 font-medium text-sm md:text-base leading-relaxed max-w-md mx-auto mb-10">
                    Sistem mendeteksi adanya kendala akses. Jika masalah ini terus berlanjut, silakan hubungi administrator layanan transportasi Bus Kampus.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="window.history.back()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold px-8 py-4 rounded-2xl transition-all shadow-sm flex items-center justify-center gap-3 text-sm">
                        <i class="fas fa-arrow-left"></i> Kembali Coba
                    </button>
                    <a href="/" class="bg-[#1e3a5f] hover:bg-[#0f2137] text-white font-black uppercase tracking-widest px-8 py-4 rounded-2xl transition-all shadow-xl hover:shadow-[#1e3a5f]/40 flex items-center justify-center gap-3 text-sm transform hover:-translate-y-1 block">
                        <i class="fas fa-home"></i> Beranda Utama
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-12 text-[11px] text-slate-500 font-black uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} Layanan Transportasi Kampus Non-Merdeka
        </div>
    </div>
</body>
</html>
