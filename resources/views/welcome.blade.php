<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="{{ __('Pesan tiket Bus Kampus Kampus Non-Merdeka secara online. Pantau armada live, pilih kursi, dan nikmati perjalanan antar-kampus Tamalanrea-Gowa yang mudah dan efisien.') }}">
    <meta name="theme-color" content="#c41e3a">
    <title>{{ __('Sistem Tiket Bus Kampus Non-Merdeka') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Slick Slider Dependencies --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* =============================================
           WCAG 2.1 LEVEL AA — ACCESSIBILITY CSS
           ============================================= */

        /* Skip Navigation Link (WCAG 2.4.1) */
        .skip-nav {
            position: fixed;
            top: -100%;
            left: 1rem;
            background: #c41e3a;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0 0 0.75rem 0.75rem;
            font-weight: 800;
            font-size: 0.9rem;
            text-decoration: none;
            z-index: 999999;
            transition: top 0.2s;
            outline: 3px solid #ffd700;
            outline-offset: 2px;
        }

        .skip-nav:focus {
            top: 0;
        }

        /* Global Focus Indicator (WCAG 2.4.7) */
        :focus-visible {
            outline: 3px solid #c41e3a !important;
            outline-offset: 3px !important;
        }

        :focus:not(:focus-visible) {
            outline: none;
        }

        /* Reduced Motion (WCAG 2.3.3) */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* High Contrast Support (WCAG 1.4.6) */
        @media (forced-colors: active) {

            .btn-kampus-primary,
            .badge-green,
            .badge-red {
                border: 2px solid ButtonText;
            }
        }

        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .card {
            @apply bg-white border border-slate-100 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-500;
        }

        .badge-green {
            @apply inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-200;
        }

        .badge-red {
            @apply inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-rose-50 text-rose-700 border border-rose-200;
        }

        .btn-kampus-primary {
            @apply bg-[#c41e3a] hover:bg-[#821326] text-white px-8 py-4 rounded-2xl font-black text-lg transition-all shadow-xl hover:shadow-[#c41e3a]/30 transform hover:-translate-y-1 flex items-center justify-center gap-2;
        }

        /* Slick Slider Custom Overrides */
        .testimonial-slider .slick-slide {
            padding: 0 15px;
        }

        .testimonial-slider .slick-list {
            margin: 0 -15px;
        }

        .testimonial-slider .slick-dots {
            bottom: -60px;
        }

        .testimonial-slider .slick-dots li button:before {
            color: #1e3a5f;
            opacity: 0.2;
            font-size: 10px;
        }

        .testimonial-slider .slick-dots li.slick-active button:before {
            color: #c41e3a;
            opacity: 1;
            font-size: 12px;
        }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .animate-float-slow {
            animation: float-slow 4s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: float-delayed 5s ease-in-out infinite 2s;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-800 bg-[#fafbfc] flex flex-col min-h-screen">

    {{-- Skip Navigation Link Target --}}
    <a href="#main-content" class="skip-nav">{{ __('Lewati ke konten utama') }}</a>

    {{-- Navbar --}}
    <nav x-data="{ mobileMenuOpen: false }"
        class="fixed w-full z-50 glass-nav border-b border-slate-100 shadow-sm transition-all duration-300"
        role="navigation" aria-label="{{ __('Navigasi Utama') }}">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
            <div class="flex justify-between h-24">
                <div class="flex items-center gap-4">
                    <div
                        class="relative w-14 h-14 bg-white rounded-full shadow-lg flex items-center justify-center p-1.5 border border-white/20 transform hover:scale-105 transition-transform duration-500">
                        <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto object-contain"
                            alt="{{ __('Logo Kampus Non-Merdeka') }}">
                    </div>
                    <span class="font-black text-base lg:text-lg xl:text-xl text-kampus-navy tracking-tight uppercase hidden md:block whitespace-nowrap"
                        aria-label="Bus Kampus Non-Merdeka">{{ __('Bus Kampus Non-Merdeka') }}</span>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-10">
                    <a href="#beranda"
                        class="text-[11px] font-black uppercase tracking-widest text-slate-500 hover:text-kampus-red transition-colors">{{ __('Home') }}</a>
                    <a href="#profil"
                        class="text-[11px] font-black uppercase tracking-widest text-slate-500 hover:text-kampus-red transition-colors">{{ __('Profile & Vision') }}</a>
                    <a href="#panduan"
                        class="text-[11px] font-black uppercase tracking-widest text-slate-500 hover:text-kampus-red transition-colors">{{ __('Guide') }}</a>
                    <a href="#testimoni"
                        class="text-[11px] font-black uppercase tracking-widest text-slate-500 hover:text-kampus-red transition-colors">{{ __('Testimonials') }}</a>

                    {{-- Language Switcher --}}
                    <div class="flex items-center bg-slate-50 p-1.5 rounded-2xl border border-slate-100 shadow-inner">
                        <a href="{{ route('lang.switch', 'id') }}"
                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ App::getLocale() === 'id' ? 'bg-white text-[#c41e3a] shadow-md border border-slate-100' : 'text-slate-500 hover:text-slate-600' }}">ID</a>
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ App::getLocale() === 'en' ? 'bg-white text-[#c41e3a] shadow-md border border-slate-100' : 'text-slate-500 hover:text-slate-600' }}">EN</a>
                    </div>

                    <div class="pl-8 border-l border-slate-100 flex items-center space-x-6">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="bg-[#c41e3a] hover:bg-[#821326] text-white px-6 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-[#c41e3a]/20">
                                    {{ __('Admin Dashboard') }}
                                </a>
                            @else
                                <a href="{{ route('user.dashboard') }}"
                                    class="bg-[#c41e3a] hover:bg-[#821326] text-white px-6 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-[#c41e3a]/20">
                                    {{ __('My Tickets') }}
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="text-kampus-navy font-black text-[11px] uppercase tracking-widest hover:text-kampus-red transition-colors">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}"
                                class="bg-gradient-to-r from-[#ffd700] to-[#f59e0b] hover:scale-105 active:scale-95 text-[#4c0b16] px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-[#ffd700]/20">
                                {{ __('Register') }}
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Mobile menu button --}}
                <div class="flex md:hidden items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-slate-500 hover:text-kampus-navy focus:outline-none"
                        :aria-expanded="mobileMenuOpen.toString()" aria-controls="mobile-menu"
                        aria-label="{{ __('Buka menu navigasi') }}">
                        <i class="fas fa-bars text-2xl" x-show="!mobileMenuOpen" aria-hidden="true"></i>
                        <i class="fas fa-times text-2xl" x-show="mobileMenuOpen" x-cloak aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" x-show="mobileMenuOpen" x-cloak x-transition
            class="md:hidden bg-white border-b border-slate-100 absolute w-full shadow-2xl rounded-b-[2rem]"
            role="navigation" aria-label="{{ __('Menu Mobile') }}">
            <div class="px-6 pt-4 pb-10 space-y-2">
                <a href="#beranda" @click="mobileMenuOpen = false"
                    class="block px-6 py-4 rounded-2xl text-base font-black text-slate-700 hover:text-kampus-red hover:bg-slate-50">{{ __('Home') }}</a>
                <a href="#profil" @click="mobileMenuOpen = false"
                    class="block px-6 py-4 rounded-2xl text-base font-black text-slate-700 hover:text-kampus-red hover:bg-slate-50">{{ __('Profile & Vision') }}</a>
                <a href="#panduan" @click="mobileMenuOpen = false"
                    class="block px-6 py-4 rounded-2xl text-base font-black text-slate-700 hover:text-kampus-red hover:bg-slate-50">{{ __('Guide') }}</a>
                <a href="#testimoni" @click="mobileMenuOpen = false"
                    class="block px-6 py-4 rounded-2xl text-base font-black text-slate-700 hover:text-kampus-red hover:bg-slate-50">{{ __('Testimonials') }}</a>
                <div class="pt-6 mt-4 border-t border-slate-100 flex flex-col gap-4">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}"
                            class="w-full text-center bg-kampus-navy text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[11px]">{{ __('Dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="w-full text-center bg-slate-50 text-kampus-navy px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[11px]">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}"
                            class="w-full text-center bg-[#c41e3a] text-white px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[11px]">{{ __('Register') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main id="main-content" role="main">

        {{-- Hero Section --}}
        <section id="beranda" class="pt-32 pb-24 lg:pt-56 lg:pb-40 relative overflow-hidden bg-[#0f172a]">
            {{-- Background Layers --}}
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=70&w=1200&fm=webp&auto=format&fit=crop" fetchpriority="high" loading="eager" decoding="async" class="w-full h-full object-cover opacity-20 mix-blend-overlay" alt="Bus Background" aria-hidden="true">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#0f172a] via-[#1e3a5f]/90 to-[#c41e3a]/70"></div>
                <div class="hero-pattern absolute inset-0 opacity-20"></div>
            </div>
            
            {{-- Floating Animated Orbs --}}
            <div class="absolute top-1/4 -left-32 w-96 h-96 bg-blue-500 rounded-full mix-blend-screen opacity-30 filter blur-[120px] animate-pulse z-0" aria-hidden="true"></div>
            <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-amber-500 rounded-full mix-blend-screen opacity-20 filter blur-[120px] animate-pulse z-0" style="animation-delay: 2s;" aria-hidden="true"></div>

            <div class="max-w-7xl mx-auto px-8 sm:px-10 lg:px-12 relative z-10">
                <div class="text-center max-w-5xl mx-auto">
                    {{-- Badge --}}
                    <div class="inline-flex flex-col sm:flex-row items-center gap-3 py-2.5 px-6 rounded-full bg-white/10 border border-white/20 text-white shadow-2xl backdrop-blur-md mb-10 transition-colors cursor-default">
                        <span class="bg-[#ffd700] text-[#1e3a5f] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full"><i class="fas fa-satellite-dish mr-1 animate-pulse"></i> Live App</span>
                        <span class="text-xs font-semibold tracking-wide">{{ __('Pusat Komando Transportasi Terintegrasi') }}</span>
                    </div>
                    
                    {{-- Heading --}}
                    <h1 class="text-5xl md:text-6xl lg:text-[5.5rem] font-black tracking-tighter mb-8 leading-[1.05] uppercase drop-shadow-2xl">
                        {{ __('Transportasi Terpadu,') }}<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-[#ffd700] to-yellow-400 drop-shadow-[0_0_30px_rgba(255,215,0,0.3)]">{{ __('Produktivitas Maksimal.') }}</span>
                    </h1>
                    
                    {{-- Subheading --}}
                    <p class="mt-6 text-lg md:text-2xl text-slate-200 mb-14 leading-relaxed max-w-3xl mx-auto font-medium tracking-tight drop-shadow-md">
                        {{ __('Sistem otomasi pintar yang menyinkronkan pemesanan kursi, manifestasi riil, dan pelacakan telemetri Kampus Non-Merdeka secara real-time—langsung dari genggaman Anda.') }}
                    </p>
                    
                    {{-- CTA Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.buses') }}"
                                class="w-full sm:w-auto px-10 py-5 rounded-[2rem] bg-[#ffd700] text-[#1e3a5f] font-black text-sm md:text-base uppercase tracking-widest hover:bg-amber-400 focus:ring-4 ring-amber-500/50 transition-all shadow-[0_20px_50px_rgba(255,215,0,0.3)] hover:-translate-y-1 flex items-center justify-center gap-3 group">
                                <i class="fas fa-ticket-alt group-hover:scale-110 transition-transform"></i> {{ __('Reservasi Tiket Anda') }}
                            </a>
                        @else
                            <a href="{{ route('guest.buses') }}"
                                class="w-full sm:w-auto px-10 py-5 rounded-[2rem] bg-[#ffd700] text-[#1e3a5f] font-black text-sm md:text-base uppercase tracking-widest hover:bg-amber-400 focus:ring-4 ring-amber-500/50 transition-all shadow-[0_20px_50px_rgba(255,215,0,0.3)] hover:-translate-y-1 flex items-center justify-center gap-3 group">
                                <i class="fas fa-bolt group-hover:-rotate-12 transition-transform"></i> {{ __('Pesanan Akses Tamu') }}
                            </a>
                            <a href="{{ route('register') }}"
                                class="w-full sm:w-auto px-10 py-5 rounded-[2rem] bg-white/10 hover:bg-white/20 backdrop-blur-xl border border-white/30 text-white font-black text-sm md:text-base uppercase tracking-widest transition-all shadow-xl hover:-translate-y-1 flex items-center justify-center gap-3 group">
                                <i class="fas fa-user-graduate group-hover:translate-x-1 transition-transform"></i> {{ __('Daftar Civitas') }}
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Floating Luxurious Stats --}}
                <div class="mt-28 grid grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto perspective-1000">
                    {{-- Stat 1 --}}
                    <div class="bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-2xl border border-white/20 rounded-[2.5rem] p-8 text-center transform md:hover:-translate-y-3 transition-all duration-500 shadow-2xl group">
                        <div class="w-14 h-14 mx-auto bg-white/10 rounded-2xl flex items-center justify-center mb-6 text-[#ffd700] border border-white/20 group-hover:bg-[#ffd700] group-hover:text-[#1e3a5f] transition-colors shadow-inner"><i class="fas fa-bus-alt text-2xl"></i></div>
                        <div class="text-4xl lg:text-5xl font-black text-white mb-2 tracking-tighter drop-shadow-md">13</div>
                        <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ __('Armada Siap') }}</div>
                    </div>
                    
                    {{-- Stat 2 --}}
                    <div class="bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-2xl border border-white/20 rounded-[2.5rem] p-8 text-center transform md:hover:-translate-y-3 transition-all duration-500 shadow-2xl group">
                        <div class="w-14 h-14 mx-auto bg-white/10 rounded-2xl flex items-center justify-center mb-6 text-[#ffd700] border border-white/20 group-hover:bg-[#ffd700] group-hover:text-[#1e3a5f] transition-colors shadow-inner"><i class="fas fa-map-signs text-2xl"></i></div>
                        <div class="text-4xl lg:text-5xl font-black text-white mb-2 tracking-tighter drop-shadow-md">15<span class="text-[#c41e3a]">+</span></div>
                        <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ __('Titik Halte') }}</div>
                    </div>
                    
                    {{-- Stat 3 --}}
                    <div class="bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-2xl border border-white/20 rounded-[2.5rem] p-8 text-center transform md:hover:-translate-y-3 transition-all duration-500 shadow-2xl group">
                        <div class="w-14 h-14 mx-auto bg-white/10 rounded-2xl flex items-center justify-center mb-6 text-[#ffd700] border border-white/20 group-hover:bg-[#ffd700] group-hover:text-[#1e3a5f] transition-colors shadow-inner"><i class="fas fa-chair text-2xl"></i></div>
                        <div class="text-4xl lg:text-5xl font-black text-white mb-2 tracking-tighter drop-shadow-md">20</div>
                        <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ __('Kapasitas Kursi') }}</div>
                    </div>
                    
                    {{-- Stat 4 --}}
                    <div class="bg-gradient-to-b from-white/10 to-white/5 backdrop-blur-2xl border border-white/20 rounded-[2.5rem] p-8 text-center transform md:hover:-translate-y-3 transition-all duration-500 shadow-2xl relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-[#ffd700]"></div>
                        <div class="w-14 h-14 mx-auto bg-white/10 rounded-2xl flex items-center justify-center mb-6 text-emerald-400 border border-white/20 group-hover:bg-emerald-400 group-hover:text-[#1e3a5f] transition-colors shadow-inner"><i class="fas fa-satellite-dish text-2xl animate-pulse"></i></div>
                        <div class="text-4xl lg:text-5xl font-black text-emerald-400 mb-2 tracking-tighter drop-shadow-md">LIVE</div>
                        <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ __('Telemetri Peta') }}</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Live Fleet Section --}}
        <section class="py-32 bg-[#fafbfc]" id="live-fleet">
            <div class="max-w-7xl mx-auto px-8 lg:px-12">
                <div class="text-center mb-16 max-w-3xl mx-auto">
                    <span
                        class="text-[#c41e3a] border border-[#c41e3a]/20 bg-[#c41e3a]/5 py-2 px-6 rounded-full font-black tracking-[0.4em] uppercase text-[10px] mb-6 inline-block"><span
                            class="w-2 h-2 rounded-full bg-red-500 inline-block mr-2 animate-pulse"></span>
                        {{ __('Live Radar System') }}</span>
                    <h2
                        class="text-4xl md:text-5xl font-black text-[#1e3a5f] tracking-tighter mb-6 leading-none uppercase">
                        {{ __('Pemantauan Armada Akurat') }}
                    </h2>
                    <p class="text-slate-500 font-medium text-lg leading-relaxed">
                        {{ __('Simulasi telemetri posisi 13 armada Bus Kampus yang terhubung terpadu dengan sistem manajemen reservasi pusat.') }}
                    </p>
                </div>

                {{-- Live Status Counters --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <div
                        class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">{{ __('Total Fleet') }}</div>
                            <div class="text-3xl font-black text-blue-600" id="w-stat-total">—</div>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                            <i class="fas fa-bus"></i>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">{{ __('On the Move') }}</div>
                            <div class="text-3xl font-black text-emerald-500" id="w-stat-jalan">—</div>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                            <i class="fas fa-route"></i>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">{{ __('Standby at Terminal') }}</div>
                            <div class="text-3xl font-black text-amber-500" id="w-stat-standby">—</div>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">{{ __('On Break') }}</div>
                            <div class="text-3xl font-black text-rose-500" id="w-stat-istirahat">—</div>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl">
                            <i class="fas fa-coffee"></i>
                        </div>
                    </div>
                </div>

                {{-- Mini Map View --}}
                <div class="bg-white p-3 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative">
                    <div
                        class="absolute top-8 left-8 z-10 bg-white/90 backdrop-blur-md px-6 py-4 rounded-2xl shadow-xl border border-slate-100 max-w-xs">
                        <h3 class="text-sm font-black text-[#1e3a5f] uppercase tracking-widest mb-2"><i
                                class="fas fa-satellite-dish text-blue-500 mr-2"></i>Live Map Engine</h3>
                        <p class="text-[10px] text-slate-500 font-medium leading-relaxed">{{ __('Map uses Leaflet.js with live event-driven data synchronization, ensuring location precision on every user device.') }}</p>
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('map') }}"
                                class="w-full bg-[#1e3a5f] hover:bg-[#c41e3a] text-white text-center py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors"><i
                                    class="fas fa-expand mr-1"></i> {{ __('Full Screen Mode') }}</a>
                        </div>
                    </div>

                    {{-- Map Container --}}
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <div id="welcome-map" class="w-full h-[500px] rounded-[2.5rem] overflow-hidden z-0 bg-slate-100">
                    </div>
                </div>
            </div>
        </section>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
        <script src="{{ asset('js/realtime-map.js') }}?v={{ filemtime(public_path('js/realtime-map.js')) }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                if (!document.getElementById('welcome-map')) return;

                RealtimeMap.initMap('welcome-map');

                // Gambar KEDUA rute dari BusSimulation (go=biru, return=oranye)
                const routes = BusSimulation.getRoutes();
                RealtimeMap.drawRoutes(routes);
                RealtimeMap.drawTerminals(BusSimulation.getTerminals());

                let dbBuses = [];
                try {
                    const res = await fetch('/api/simulation/buses');
                    const data = await res.json();
                    dbBuses = data.buses;
                    BusSimulation.init(dbBuses);
                } catch (e) { console.error('Simulasi map hero gagal dimuat', e); }

                setInterval(() => {
                    const positions = BusSimulation.getAllPositions();

                    document.getElementById('w-stat-total').textContent = positions.length;
                    document.getElementById('w-stat-jalan').textContent = positions.filter(b => b.trip_status === 'jalan').length;
                    document.getElementById('w-stat-standby').textContent = positions.filter(b => b.trip_status === 'standby').length;
                    document.getElementById('w-stat-istirahat').textContent = positions.filter(b => b.trip_status === 'istirahat').length;

                    RealtimeMap.updateBusMarkers(positions.map(b => ({
                        id: b.id,
                        bus_number: b.bus_number,
                        bus_code: b.bus_code,
                        name: b.name,
                        plate: b.plate,
                        trip_status: b.trip_status,
                        direction: b.direction,
                        lat: b.lat,
                        lng: b.lng,
                        current_terminal: b.current_terminal,
                        from_terminal: b.from_terminal,
                        next_terminal: b.next_terminal,
                        eta_minutes: b.eta_minutes,
                        db_available: b.db_available,
                        booked_passengers: b.booked_passengers,
                        is_bookable: b.is_bookable,
                        driver_name: b.driver_name,
                    })));
                }, 1500); // Polling lebih cepat 1.5 detik
            });
        </script>

        {{-- Profile & Vision Section --}}
        <section id="profil" class="py-32 bg-white relative overflow-hidden" aria-labelledby="profil-title">
            <div class="absolute top-0 inset-x-0 h-40 bg-gradient-to-b from-slate-50 to-transparent" aria-hidden="true"></div>
            
            <div class="max-w-7xl mx-auto px-8 lg:px-12 relative z-10">
                <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">

                    {{-- Left Side: Image with Glassmorphism Overlay --}}
                    <div class="order-2 lg:order-1 relative perspective-1000">
                        <div class="absolute -inset-8 bg-gradient-to-tr from-[#1e3a5f]/20 to-[#c41e3a]/10 rounded-[3rem] blur-3xl" aria-hidden="true"></div>
                        
                        {{-- Main Visual --}}
                        <div class="relative w-full aspect-[4/5] md:aspect-square lg:aspect-[4/5] rounded-[3rem] overflow-hidden shadow-2xl border border-slate-100 group">
                            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=70&w=600&fm=webp&auto=format&fit=crop" loading="lazy" decoding="async" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-1000" alt="Kampus Kampus Non-Merdeka">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#1e3a5f]/90 via-[#1e3a5f]/20 to-transparent"></div>
                            
                            {{-- Glassmorphism Visi Misi Overlay --}}
                            <div class="absolute inset-x-6 bottom-6 md:inset-x-8 md:bottom-8 bg-white/10 backdrop-blur-2xl border border-white/20 p-6 md:p-8 rounded-[2rem] shadow-xl transform translate-y-2 group-hover:translate-y-0 transition-all duration-500">
                                <h3 class="text-2xl md:text-3xl font-black text-white tracking-tighter mb-4 uppercase drop-shadow-md">{{ __('Visi & Misi Layanan') }}</h3>
                                <p class="text-sm md:text-base text-slate-200 font-medium leading-relaxed mb-6 drop-shadow-sm">
                                    {{ __('Menjadi platform pionir sistem transportasi edukasi cerdas di kawasan timur, yang merampingkan konektivitas logistik dan menjamin pergerakan elemen akademik.') }}
                                </p>
                                <ul class="space-y-3" aria-label="{{ __('Poin keunggulan layanan') }}">
                                    <li class="flex items-start gap-4">
                                        <div class="w-6 h-6 rounded-full bg-[#ffd700] text-[#1e3a5f] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm" aria-hidden="true"><i class="fas fa-check text-[10px]"></i></div>
                                        <span class="text-xs md:text-sm font-medium text-white drop-shadow-sm">{{ __('Digitalisasi administratif validasi manifes penumpang.') }}</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="w-6 h-6 rounded-full bg-[#ffd700] text-[#1e3a5f] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm" aria-hidden="true"><i class="fas fa-check text-[10px]"></i></div>
                                        <span class="text-xs md:text-sm font-medium text-white drop-shadow-sm">{{ __('Ketersediaan kursi secara presisi demi efisiensi optimal.') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Profile Description and Stats --}}
                    <div class="order-1 lg:order-2">
                        <span class="inline-flex items-center gap-2 text-[#c41e3a] font-black tracking-[0.4em] uppercase text-[10px] mb-6 px-4 py-1.5 rounded-full bg-rose-50 border border-rose-100">
                            <i class="fas fa-university"></i> {{ __('Dedikasi Institusional') }}
                        </span>
                        <h2 id="profil-title" class="text-4xl md:text-5xl font-black text-[#1e3a5f] tracking-tighter leading-[1.1] mb-8 uppercase">
                            {{ __('PROFIL TRANSPORTASI UNIVERSITAS') }}
                        </h2>
                        <p class="text-lg text-slate-500 font-medium leading-relaxed mb-10">
                            {{ __('Bus Kampus Non-Merdeka berdiri melampaui sekadar sarana sirkulasi gratis; ia merupakan wujud konkret komitmen institusi untuk mendobrak batasan aksesibilitas akademik antara kampus utama Tamalanrea dan Fakultas Teknik Gowa.') }}
                        </p>
                        
                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 pt-10 border-t border-slate-100">
                            {{-- Stat 1 --}}
                            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-5 hover:bg-white hover:shadow-xl transition-all duration-300 group">
                                <i class="fas fa-history text-2xl text-slate-300 group-hover:text-[#c41e3a] transition-colors mb-3"></i>
                                <p class="text-3xl font-black text-[#1e3a5f]">2006</p>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Diinisiasi') }}</p>
                            </div>
                            
                            {{-- Stat 2 --}}
                            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-5 hover:bg-white hover:shadow-xl transition-all duration-300 group">
                                <i class="fas fa-users text-2xl text-slate-300 group-hover:text-[#1e3a5f] transition-colors mb-3"></i>
                                <p class="text-3xl font-black text-[#1e3a5f]">1.5k<span class="text-[#c41e3a]">+</span></p>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Mobilitas Harian') }}</p>
                            </div>

                            {{-- Stat 3 --}}
                            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-5 hover:bg-white hover:shadow-xl transition-all duration-300 group col-span-2 md:col-span-1">
                                <i class="fas fa-route text-2xl text-slate-300 group-hover:text-[#ffd700] transition-colors mb-3"></i>
                                <p class="text-3xl font-black text-[#1e3a5f]">2</p>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Rute Aktif') }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- System Mechanics & Procedure --}}
        <section id="panduan" class="py-32 bg-[#1e3a5f] text-white relative overflow-hidden" aria-labelledby="panduan-title">
            <div class="hero-pattern absolute inset-0 opacity-10" aria-hidden="true"></div>
            <div class="max-w-7xl mx-auto px-8 lg:px-12 relative z-10">
                <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                    {{-- Left Side: Procedures Content --}}
                    <article>
                        <header class="mb-10">
                            <span class="text-[#ffd700] font-black tracking-[0.4em] uppercase text-[10px] mb-6 block" aria-label="Kategori Section">{{ __('Prosedur Sistem Operasional') }}</span>
                            <h2 id="panduan-title" class="text-4xl md:text-5xl font-black tracking-tighter leading-[1.1] mb-6 uppercase">
                                {{ __('Cara Memesan Tiket') }}<br class="hidden md:block">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">{{ __('Bus Kampus Non-Merdeka') }}</span>
                            </h2>
                            <p class="text-lg text-slate-300 font-medium leading-relaxed">
                                {{ __('Sistem terpadu kami merampingkan antrean fisik menjadi reservasi digital waktu-nyata. Ikuti tiga langkah mudah berikut untuk mendapatkan kursi Anda.') }}
                            </p>
                        </header>

                        <ol class="space-y-6 mb-12 relative" role="list">
                            <div class="absolute left-10 top-10 bottom-10 w-0.5 bg-gradient-to-b from-white/20 to-transparent hidden md:block" aria-hidden="true"></div>
                            
                            <li class="relative flex items-start gap-6 group">
                                <div class="w-14 h-14 rounded-full bg-white/10 border border-white/20 backdrop-blur-xl flex flex-shrink-0 items-center justify-center text-xl font-black text-[#ffd700] shadow-lg group-hover:bg-[#ffd700] group-hover:text-[#1e3a5f] transition-colors relative z-10">1</div>
                                <div class="pt-2">
                                    <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tight">{{ __('Autentikasi / Akses Tamu') }}</h3>
                                    <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ __('Log in using your official @kampus-non-merdeka.ac.id email for academic staff, or use the Guest Order form for the general public without registration.') }}</p>
                                </div>
                            </li>

                            <li class="relative flex items-start gap-6 group">
                                <div class="w-14 h-14 rounded-full bg-white/10 border border-white/20 backdrop-blur-xl flex flex-shrink-0 items-center justify-center text-xl font-black text-[#ffd700] shadow-lg group-hover:bg-[#ffd700] group-hover:text-[#1e3a5f] transition-colors relative z-10">2</div>
                                <div class="pt-2">
                                    <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tight">{{ __('Pilih Armada Standby') }}</h3>
                                    <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ __('Browse the fleet telemetry map live. Tap View & Book on your preferred route\'s fleet that is on standby at the departure terminal.') }}</p>
                                </div>
                            </li>

                            <li class="relative flex items-start gap-6 group">
                                <div class="w-14 h-14 rounded-full bg-white/10 border border-white/20 backdrop-blur-xl flex flex-shrink-0 items-center justify-center text-xl font-black text-[#ffd700] shadow-lg group-hover:bg-[#ffd700] group-hover:text-[#1e3a5f] transition-colors relative z-10">3</div>
                                <div class="pt-2">
                                    <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tight">{{ __('Pindai E-Ticket') }}</h3>
                                    <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ __('The system will instantly provide a unique barcode on your profile. Show and scan it on the driver\'s display device as you board the bus.') }}</p>
                                </div>
                            </li>
                        </ol>

                        <a href="{{ route('guide') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-[#ffd700] hover:bg-white text-[#1e3a5f] rounded-full font-black text-[11px] uppercase tracking-widest transition-all shadow-xl hover:scale-105 active:scale-95" aria-label="{{ __('Buka halaman panduan lengkap') }}">
                            <i class="fas fa-book-open text-base" aria-hidden="true"></i> {{ __('Kebijakan & Syarat Lengkap') }}
                        </a>
                    </article>

                    {{-- Right Side: UI Mockup Visual --}}
                    <aside class="relative mt-12 md:mt-0 flex justify-center lg:justify-end perspective-1000">
                        <div class="absolute -inset-10 bg-gradient-to-tr from-[#c41e3a] to-amber-500 rounded-full blur-[100px] opacity-20 animate-pulse" aria-hidden="true"></div>
                        
                        <div class="relative w-full max-w-sm bg-gradient-to-b from-white/10 to-white/5 border border-white/10 rounded-[3rem] p-6 lg:p-8 backdrop-blur-3xl shadow-2xl transform rotate-y-[-5deg] rotate-x-[5deg] hover:rotate-y-0 hover:rotate-x-0 outline-none transition-transform duration-700">
                            
                            {{-- Header Mockup --}}
                            <div class="flex items-center justify-between mb-8 border-b border-white/10 pb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#1e3a5f] border border-white/20 flex items-center justify-center shadow-inner">
                                        <i class="fas fa-bus text-[#ffd700] text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Boarding Pass</p>
                                        <p class="text-sm font-black text-white uppercase">Bus Kampus Non-Merdeka 01</p>
                                    </div>
                                </div>
                                <span class="bg-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border border-emerald-500/30">Valid</span>
                            </div>

                            {{-- Mockup Main Content --}}
                            <div class="flex flex-col items-center mb-8">
                                <div class="w-48 h-48 bg-white p-4 rounded-[2rem] shadow-xl relative group mb-6 hover:shadow-2xl hover:-translate-y-1 transition-all">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" class="w-full h-full object-contain mix-blend-multiply opacity-90 group-hover:opacity-100" alt="{{ __('Contoh Integrasi Validasi Barcode') }}" loading="lazy">
                                    <div class="absolute inset-0 border-2 border-dashed border-[#1e3a5f]/10 rounded-[2rem] m-2 pointer-events-none"></div>
                                </div>
                                
                                <h4 class="text-xl font-black text-white tracking-tighter uppercase mb-2">TIKET B02-X9</h4>
                                <p class="text-xs text-slate-500 font-medium">Fakultas Teknik - Gowa</p>
                            </div>

                            {{-- Floating Elements --}}
                            <div class="absolute -left-6 top-1/4 bg-white border border-slate-100 shadow-xl rounded-2xl p-3 flex items-center gap-3 animate-float-slow">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-xs"><i class="fas fa-check"></i></div>
                                <div>
                                    <p class="text-[8px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Status Validation') }}</p>
                                    <p class="text-[10px] font-black text-slate-700 uppercase">{{ __('Seat Confirmed') }}</p>
                                </div>
                            </div>
                            
                            <div class="absolute -right-8 bottom-1/4 bg-[#1e3a5f] border border-white/20 shadow-xl rounded-2xl p-3 flex items-center gap-3 animate-float-delayed">
                                <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs shadow-inner"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <p class="text-[8px] font-bold text-blue-200 uppercase tracking-wider">{{ __('Live Telemetry') }}</p>
                                    <p class="text-[10px] font-black text-white uppercase">{{ __('Connected GPS') }}</p>
                                </div>
                            </div>

                        </div>
                    </aside>

                </div>
            </div>
        </section>

        {{-- Testimonials --}}
        <section id="testimoni" class="py-32 bg-[#fafbfc]">
            <div class="max-w-7xl mx-auto px-8 lg:px-12">
                <div class="text-center mb-20 max-w-3xl mx-auto">
                    <span
                        class="text-[#c41e3a] font-black tracking-[0.4em] uppercase text-[10px] mb-4 block underline underline-offset-8">{{ __('User Voice') }}</span>
                    <h2
                        class="text-4xl md:text-5xl font-black text-[#1e3a5f] tracking-tighter mt-6 mb-6 leading-none uppercase">
                        {{ __('PENGALAMAN CIVITAS') }}
                    </h2>
                </div>

                <div class="testimonial-slider px-4">
                    <div class="card p-10 mx-2">
                        <div class="flex items-center gap-1 text-[#ffd700] mb-6"
                            aria-label="{{ __('Rating 5 bintang') }}">
                            <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i
                                class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i>
                        </div>
                        <p class="text-slate-500 font-medium italic mb-10 leading-relaxed">"{{ __('This ticketing system greatly helps my daily mobility from Tamalanrea to Gowa. Precise and organised!') }}"</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-[#1e3a5f] shadow-inner">
                                R</div>
                            <div>
                                <h4 class="font-black text-[#1e3a5f] text-sm uppercase">Rahmat</h4>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Teknik
                                    Informatika</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-10 mx-2">
                        <div class="flex items-center gap-1 text-[#ffd700] mb-6"
                            aria-label="{{ __('Rating 5 bintang') }}">
                            <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i
                                class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i>
                        </div>
                        <p class="text-slate-500 font-medium italic mb-10 leading-relaxed">"{{ __('Seat allocation via the web means I no longer have to rush when the bus arrives. Very innovative!') }}"</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-[#1e3a5f] shadow-inner">
                                S</div>
                            <div>
                                <h4 class="font-black text-[#1e3a5f] text-sm uppercase">Siti Nurhaliza</h4>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">
                                    Kedokteran Gigi</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-10 mx-2">
                        <div class="flex items-center gap-1 text-[#ffd700] mb-6"
                            aria-label="{{ __('Rating 5 bintang') }}">
                            <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i
                                class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i>
                        </div>
                        <p class="text-slate-500 font-medium italic mb-10 leading-relaxed">"{{ __('Thank you Non-Merdeka Campus! Inter-campus travel is now far more comfortable and modern with this digital system.') }}"</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-[#1e3a5f] shadow-inner">
                                B</div>
                            <div>
                                <h4 class="font-black text-[#1e3a5f] text-sm uppercase">Budi Santoso</h4>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Dosen
                                    Ekonomi</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-10 mx-2">
                        <div class="flex items-center gap-1 text-[#ffd700] mb-6"
                            aria-label="{{ __('Rating 5 bintang') }}">
                            <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i
                                class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i>
                        </div>
                        <p class="text-slate-500 font-medium italic mb-10 leading-relaxed">"{{ __('I no longer need to queue physically. Everything is accurately monitored right from my smartphone. Amazing!') }}"</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-[#1e3a5f] shadow-inner">
                                F</div>
                            <div>
                                <h4 class="font-black text-[#1e3a5f] text-sm uppercase">Faisal Ridho</h4>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Hukum</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-10 mx-2">
                        <div class="flex items-center gap-1 text-[#ffd700] mb-6"
                            aria-label="{{ __('Rating 5 bintang') }}">
                            <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i
                                class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star-half-alt"
                                aria-hidden="true"></i>
                        </div>
                        <p class="text-slate-500 font-medium italic mb-10 leading-relaxed">"{{ __('Very informative. The notification feature is very helpful so I know when to head to the terminal.') }}"</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-[#1e3a5f] shadow-inner">
                                A</div>
                            <div>
                                <h4 class="font-black text-[#1e3a5f] text-sm uppercase">Andi Riska</h4>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Sospol</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-10 mx-2">
                        <div class="flex items-center gap-1 text-[#ffd700] mb-6"
                            aria-label="{{ __('Rating 5 bintang') }}">
                            <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i
                                class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star"
                                aria-hidden="true"></i>
                        </div>
                        <p class="text-slate-500 font-medium italic mb-10 leading-relaxed">"{{ __('Integration is 100% seamless. The driver can check my e-Ticket from their smartphone in just two seconds!') }}"</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-[#1e3a5f] shadow-inner">
                                W</div>
                            <div>
                                <h4 class="font-black text-[#1e3a5f] text-sm uppercase">Wahyu Pratama</h4>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Kehutanan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    <footer class="bg-gray-950 pt-32 pb-16 text-white overflow-hidden relative"
        aria-label="{{ __('Footer Informasi') }}">
        <div class="max-w-7xl mx-auto px-8 lg:px-12 grid md:grid-cols-2 lg:grid-cols-4 gap-16 relative z-10">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-4 mb-8">
                    <div
                        class="relative w-16 h-16 bg-white rounded-full shadow-xl flex items-center justify-center p-2 border border-white/10 transform hover:scale-105 transition-transform duration-500">
                        <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto object-contain"
                            alt="Logo Kampus Non-Merdeka">
                    </div>
                    <span class="text-3xl font-black tracking-tighter uppercase leading-none">BUS KAMPUS<br><span
                            class="text-[#c41e3a]">Kampus Non-Merdeka</span></span>
                </div>
                <p class="text-slate-500 font-medium leading-relaxed max-w-sm">{{ __('Modern and integrated mobility solution to support academic activities at the Non-Merdeka Campus.') }}</p>
            </div>

            <div class="space-y-6">
                <h4 class="text-sm font-black uppercase tracking-[0.3em] text-[#ffd700]">{{ __('Official Contact') }}</h4>
                <ul class="space-y-4 text-sm text-slate-500 font-medium">
                    <li class="flex gap-4"><i class="fas fa-map-marker-alt text-[#c41e3a] mt-1 pr-1"
                            aria-hidden="true"></i>Jl. Perintis Kemerdekaan KM.10, Makassar</li>
                    <li class="flex gap-4"><i class="fas fa-phone text-[#c41e3a] mt-1 pr-1" aria-hidden="true"></i>+62
                        411 586200</li>
                    <li class="flex gap-4"><i class="fas fa-envelope text-[#c41e3a] mt-1 pr-1"
                            aria-hidden="true"></i>transport@kampus-non-merdeka.ac.id</li>
                </ul>
            </div>

            <div class="space-y-8">
                <h4 class="text-sm font-black uppercase tracking-[0.3em] text-[#ffd700]">{{ __('Follow Activity') }}
                </h4>
                <div class="flex gap-4">
                    <a href="#"
                        class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center hover:bg-[#c41e3a] transition-all duration-500 border border-white/5"
                        aria-label="{{ __('Ikuti kami di Instagram') }}">
                        <i class="fab fa-instagram text-xl" aria-hidden="true"></i>
                    </a>
                    <a href="#"
                        class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center hover:bg-[#c41e3a] transition-all duration-500 border border-white/5"
                        aria-label="{{ __('Ikuti kami di Facebook') }}">
                        <i class="fab fa-facebook-f text-xl" aria-hidden="true"></i>
                    </a>
                    <a href="#"
                        class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center hover:bg-[#c41e3a] transition-all duration-500 border border-white/5"
                        aria-label="{{ __('Ikuti kami di Twitter') }}">
                        <i class="fab fa-twitter text-xl" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-8 lg:px-12 mt-32 pt-10 border-t border-white/5 text-center">
            <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.5em]">&copy; {{ date('Y') }}
                Kampus Non-Merdeka Transport System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.testimonial-slider').slick({
                dots: true,
                infinite: true,
                speed: 800,
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                arrows: false,
                responsive: [
                    { breakpoint: 1024, settings: { slidesToShow: 2 } },
                    { breakpoint: 640, settings: { slidesToShow: 1 } }
                ]
            });
        });
    </script>

    {{-- Accessibility Toolbar (WCAG 2.1 Level AA) --}}
    @include('partials.accessibility-toolbar')

</body>

</html>