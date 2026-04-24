<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0f172a">
    <meta name="description" content="Panel Dashboard Sopir Bus Kampus Non-Merdeka — Pantau rute, manifest penumpang, dan status perjalanan secara real-time.">
    <meta name="author" content="Bus Kampus Non-Merdeka">

    {{-- Open Graph --}}
    <meta property="og:title" content="Panel Sopir | Bus Kampus Non-Merdeka">
    <meta property="og:description" content="Dashboard real-time untuk sopir armada Bus Kampus Kampus Non-Merdeka.">
    <meta property="og:type" content="website">

    <title>@yield('title', 'Dashboard Sopir') — Bus Kampus Non-Merdeka</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/css/all.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="{{ asset('vendor/js/alpine.min.js') }}"></script>
    <script src="{{ asset('vendor/js/sweetalert2.all.min.js') }}"></script>

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        .swal2-title, .swal2-html-container, .swal2-popup { font-family: 'Inter', sans-serif !important; }

        /* Premium scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Glowing animation */
        @keyframes glow-pulse {
            0%, 100% { box-shadow: 0 0 8px rgba(245,158,11,0.4); }
            50% { box-shadow: 0 0 20px rgba(245,158,11,0.8), 0 0 40px rgba(245,158,11,0.3); }
        }
        .glow { animation: glow-pulse 2s ease-in-out infinite; }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slide-up 0.4s ease both; }

        /* Glass effect */
        .glass { background: rgba(255,255,255,0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.1); }
        .glass-dark { background: rgba(15,23,42,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); }

        /* Status pill */
        .status-jalan { background: linear-gradient(135deg, #059669, #10b981); }
        .status-standby { background: linear-gradient(135deg, #d97706, #f59e0b); }
        .status-istirahat { background: linear-gradient(135deg, #6366f1, #818cf8); }
    </style>
    @stack('styles')
</head>
<body class="bg-[#0f172a] text-slate-100 font-sans antialiased min-h-screen flex flex-col">

    {{-- BACKGROUND mesh/grid --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0" style="background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(30,58,95,0.6) 0%, transparent 70%);"></div>
        <div class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full opacity-10" style="background: radial-gradient(circle, #f59e0b 0%, transparent 70%);"></div>
        <svg class="absolute inset-0 w-full h-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>

    {{-- TOP NAVBAR --}}
    <nav class="sticky top-0 z-50 glass-dark border-b border-white/[0.08]" role="navigation" aria-label="Panel Navigasi Sopir">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center">

                {{-- Brand --}}
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl overflow-hidden bg-white/10 flex items-center justify-center border border-white/10 p-1.5">
                        <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto object-contain grayscale invert opacity-90" width="1024" height="1024" alt="Logo Kampus Non-Merdeka">
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-amber-400 uppercase tracking-[0.2em] leading-none">Panel Armada</p>
                        <p class="text-sm font-black text-white leading-tight tracking-tight">Bus Kampus Non-Merdeka</p>
                    </div>
                </div>

                {{-- Right Side --}}
                <div class="flex items-center gap-3">
                    {{-- Driver name --}}
                    <div class="hidden sm:flex items-center gap-2 glass px-3 py-1.5 rounded-xl">
                        <div class="w-6 h-6 rounded-lg bg-amber-500/20 flex items-center justify-center">
                            <i class="fas fa-steering-wheel text-amber-400 text-[10px]"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-300">{{ auth()->user()->name }}</span>
                    </div>

                    {{-- Notifications --}}
                    @include('partials.notification-bell', ['variant' => 'user'])

                    {{-- Logout --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-8 h-8 rounded-xl glass flex items-center justify-center text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-all duration-200"
                                aria-label="Keluar dari sistem">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 w-full max-w-4xl mx-auto px-4 sm:px-6 py-8 relative z-10 animate-slide-up" id="main-content">
        @yield('sopir-content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-white/[0.06] py-5 mt-auto relative z-10" role="contentinfo">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} · Bus Kampus Non-Merdeka · Sistem Transportasi Terintegrasi
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>