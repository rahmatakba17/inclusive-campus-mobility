@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden" x-data="{ adminMobileMenuOpen: false }">

    {{-- Mobile Sidebar Backdrop --}}
    <div x-show="adminMobileMenuOpen" 
         @click="adminMobileMenuOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden" 
         aria-hidden="true" x-cloak>
    </div>

    {{-- ===== SIDEBAR ADMIN ===== --}}
    <aside :class="adminMobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-72 lg:relative flex-shrink-0 bg-[#a51c24] flex flex-col shadow-2xl transition-transform duration-300 ease-in-out overflow-hidden"
           aria-label="{{ __('Sidebar Navigasi Admin') }}">

        {{-- Decorative background element (hidden from screen readers) --}}
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-black/10 rounded-full blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl" aria-hidden="true"></div>

        {{-- Logo --}}
        <div class="p-6 md:p-10 relative flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="relative w-12 h-12 md:w-16 md:h-16 bg-white rounded-full shadow-lg flex items-center justify-center p-2 border-2 border-white/20 transform hover:scale-105 transition-transform duration-500">
                    <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto object-contain"
                         width="1024" height="1024"
                         alt="{{ __('Logo Kampus Non-Merdeka') }}">
                </div>
                <div>
                    <p class="text-white font-black text-lg md:text-xl tracking-tighter leading-none" aria-label="Bus Kampus Non-Merdeka Admin Panel">BUS Kampus Non-Merdeka</p>
                    <p class="text-white/70 text-[8px] md:text-[9px] uppercase tracking-[0.3em] font-black mt-1 md:mt-2">{{ __('Admin Panel') }}</p>
                </div>
            </div>
            
            {{-- Close button for mobile only --}}
            <button @click="adminMobileMenuOpen = false" class="lg:hidden text-white/50 hover:text-white p-2" aria-label="{{ __('Tutup Menu') }}">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 lg:px-6 py-4 space-y-6 md:space-y-8 overflow-y-auto custom-scrollbar relative"
             role="navigation"
             aria-label="{{ __('Menu Utama Admin') }}">

            <div>
                <p class="text-white/60 text-[10px] font-black uppercase tracking-[0.4em] mb-4 md:mb-6 px-4" aria-hidden="true">{{ __('Main Menu') }}</p>
                <div class="space-y-1.5 md:space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-link group {{ request()->routeIs('admin.dashboard') ? 'active bg-white/10' : '' }} flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/5 transition-colors">
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-xl flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 shadow-lg' : '' }}" aria-hidden="true">
                            <i class="fas fa-th-large text-sm text-white" aria-hidden="true"></i>
                        </div>
                        <span class="font-bold tracking-tight text-white/90 group-hover:text-white">{{ __('Dashboard') }}</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="text-white/60 text-[10px] font-black uppercase tracking-[0.4em] mb-4 md:mb-6 px-4" aria-hidden="true">{{ __('Management') }}</p>
                <div class="space-y-1.5 md:space-y-2">
                    <a href="{{ route('admin.buses.index') }}"
                       class="sidebar-link group {{ request()->routeIs('admin.buses*') ? 'active bg-white/10' : '' }} flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/5 transition-colors">
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-xl flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('admin.buses*') ? 'bg-white/20 shadow-lg' : '' }}" aria-hidden="true">
                            <i class="fas fa-bus text-sm text-white" aria-hidden="true"></i>
                        </div>
                        <span class="font-bold tracking-tight text-white/90 group-hover:text-white">{{ __('Fleet Data') }}</span>
                    </a>

                    <a href="{{ route('admin.bookings.index') }}"
                       class="sidebar-link group {{ request()->routeIs('admin.bookings*') ? 'active bg-white/10' : '' }} flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/5 transition-colors text-white">
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-xl flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('admin.bookings*') ? 'bg-white/20 shadow-lg' : '' }}" aria-hidden="true">
                            <i class="fas fa-ticket text-sm text-white" aria-hidden="true"></i>
                        </div>
                        <span class="font-bold tracking-tight text-white/90 group-hover:text-white">{{ __('Bookings') }}</span>
                        @php $pendingCount = \App\Models\Booking::where('status', 'pending')->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="ml-auto bg-[#ffd700] text-[#821326] text-[10px] font-black px-2 py-0.5 rounded-lg shadow-sm"
                                  aria-label="{{ $pendingCount }} {{ __('pemesanan menunggu') }}">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.revenue.index') }}"
                       class="sidebar-link group {{ request()->routeIs('admin.revenue*') ? 'active bg-white/10' : '' }} flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/5 transition-colors">
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-xl flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('admin.revenue*') ? 'bg-white/20 shadow-lg' : '' }}" aria-hidden="true">
                            <i class="fas fa-money-bill-wave text-sm text-white" aria-hidden="true"></i>
                        </div>
                        <span class="font-bold tracking-tight text-white/90 group-hover:text-white">{{ __('Laporan Pemasukan') }}</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="sidebar-link group {{ request()->routeIs('admin.users*') ? 'active bg-white/10' : '' }} flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/5 transition-colors">
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-xl flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('admin.users*') ? 'bg-white/20 shadow-lg' : '' }}" aria-hidden="true">
                            <i class="fas fa-users text-sm text-white" aria-hidden="true"></i>
                        </div>
                        <span class="font-bold tracking-tight text-white/90 group-hover:text-white">{{ __('Passengers') }}</span>
                    </a>

                    <a href="{{ route('admin.drivers.index') }}"
                       class="sidebar-link group {{ request()->routeIs('admin.drivers*') ? 'active bg-white/10' : '' }} flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/5 transition-colors">
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-xl flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('admin.drivers*') ? 'bg-white/20 shadow-lg' : '' }}" aria-hidden="true">
                            <i class="fas fa-id-card text-sm text-white" aria-hidden="true"></i>
                        </div>
                        <span class="font-bold tracking-tight text-white/90 group-hover:text-white">{{ __('Drivers') }}</span>
                    </a>
                </div>
            </div>
        </nav>

        {{-- User info --}}
        <div class="p-6 md:p-8 relative">
            <div class="bg-black/10 rounded-2xl md:rounded-[2rem] p-4 md:p-5 border border-white/5 flex items-center gap-3 md:gap-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-white/10 rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/10"
                     aria-hidden="true">
                    <span class="text-white font-black text-base md:text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-[10px] md:text-xs font-black truncate tracking-tight">{{ auth()->user()->name }}</p>
                    <p class="text-white/70 text-[8px] md:text-[9px] font-bold uppercase tracking-widest mt-0.5">Administrator</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-8 h-8 md:w-10 md:h-10 rounded-xl md:rounded-2xl bg-white/10 text-white hover:bg-white hover:text-[#a51c24] transition-all duration-300 flex items-center justify-center group"
                            aria-label="{{ __('Keluar dari akun') }}">
                        <i class="fas fa-power-off text-[10px] md:text-xs group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden w-full bg-[#fafbfc] relative">
        {{-- Background Pattern (decorative) --}}
        <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none" aria-hidden="true"
             style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        {{-- Topbar --}}
        <header class="bg-white/95 backdrop-blur-xl border-b border-slate-100 px-4 md:px-8 lg:px-12 py-4 md:py-6 flex flex-wrap items-center justify-between z-30 sticky top-0 gap-y-4"
                role="banner">
            <div class="flex items-center gap-3 md:gap-4 w-full sm:w-auto justify-between sm:justify-start">
                <div class="flex items-center gap-3 md:gap-4">
                    {{-- Hamburger Menu (Mobile/Tablet) --}}
                    <button @click="adminMobileMenuOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-[#1e3a5f] bg-slate-50 rounded-xl flex-shrink-0" aria-label="{{ __('Buka Menu Navigasi') }}">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="min-w-0">
                        <h1 class="text-xl md:text-2xl font-black text-[#1e3a5f] tracking-tighter line-clamp-1 break-all sm:break-normal">{{ __($view_name ?? 'Dashboard') }}</h1>
                        <div class="flex items-center gap-2 mt-1 md:mt-1.5" aria-hidden="true">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse hidden sm:block"></div>
                            <p class="text-[9px] md:text-[10px] font-black text-slate-500 uppercase tracking-widest hidden sm:block">{{ __('Administrasi Bus Kampus Non-Merdeka') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6 lg:gap-8 min-w-0 flex-shrink-0">
                {{-- Language Switcher --}}
                <div class="hidden sm:flex items-center bg-slate-50 p-1 md:p-1.5 rounded-xl md:rounded-2xl border border-slate-100 shadow-sm">
                    <a href="{{ route('lang.switch', 'id') }}"
                       class="px-2 md:px-4 py-1.5 md:py-2 rounded-lg md:rounded-xl text-[10px] md:text-[11px] font-black uppercase tracking-widest transition-all
                              {{ App::getLocale() === 'id' ? 'bg-white text-[#c41e3a] shadow-md border border-slate-100' : 'text-slate-500 hover:text-slate-600' }}">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="px-2 md:px-4 py-1.5 md:py-2 rounded-lg md:rounded-xl text-[10px] md:text-[11px] font-black uppercase tracking-widest transition-all
                              {{ App::getLocale() === 'en' ? 'bg-white text-[#c41e3a] shadow-md border border-slate-100' : 'text-slate-500 hover:text-slate-600' }}">EN</a>
                </div>

                <div class="hidden lg:flex flex-col items-end">
                    <p class="text-xs md:text-sm font-black text-[#1e3a5f] tracking-tight">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-[8px] md:text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Status Sistem: Optimal') }}</p>
                </div>

                <div class="hidden md:block w-px h-8 md:h-10 bg-slate-100"></div>

                @include('partials.notification-bell', ['variant' => 'admin'])
            </div>
        </header>

        {{-- Content area --}}
        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-8 lg:p-12 custom-scrollbar z-10 w-full" id="main-content" role="main">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 md:mb-10 flex flex-col sm:flex-row items-center gap-4 md:gap-5 bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 md:px-8 md:py-5 rounded-[1.5rem] md:rounded-[2rem] animate-slide-up shadow-sm w-full"
                     id="flash-msg"
                     role="alert"
                     aria-live="polite"
                     aria-atomic="true">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-emerald-500 rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/20" aria-hidden="true">
                        <i class="fas fa-check text-white text-sm md:text-lg" aria-hidden="true"></i>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <p class="font-black text-sm tracking-tight">{{ __('Operasi Berhasil') }}</p>
                        <p class="text-xs font-medium text-emerald-700">{{ session('success') }}</p>
                    </div>
                    <button onclick="document.getElementById('flash-msg').remove()"
                            class="w-full sm:w-8 sm:h-8 p-2 sm:p-0 rounded-xl hover:bg-emerald-100 flex items-center justify-center text-emerald-400 hover:text-emerald-600 transition-all mt-2 sm:mt-0 bg-emerald-100/50 sm:bg-transparent"
                            aria-label="{{ __('Tutup notifikasi') }}">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>
            @endif

            @yield('admin-content')
        </main>
    </div>
</div>
@endsection