@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden bg-[#fafbfc]" x-data="{ mobileMenuOpen: false }">

    {{-- Mobile Sidebar Backdrop --}}
    <div x-show="mobileMenuOpen" 
         @click="mobileMenuOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden" 
         aria-hidden="true" x-cloak>
    </div>

    {{-- ===== SIDEBAR USER (RESPONSIVE) ===== --}}
    <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-64 lg:relative flex-shrink-0 bg-gradient-to-b from-[#821326] to-[#4c0b16] flex flex-col shadow-2xl transition-transform duration-300 ease-in-out overflow-hidden"
           aria-label="{{ __('Sidebar Navigasi User') }}">

        {{-- Decorative background elements --}}
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-black/20 rounded-full blur-[80px]" aria-hidden="true"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-[80px]" aria-hidden="true"></div>

        {{-- Logo Area + Mobile Close Button --}}
        <div class="p-6 md:p-8 relative border-b border-white/5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative w-10 h-10 md:w-12 md:h-12 bg-white rounded-full shadow-lg flex items-center justify-center p-1.5 border border-white/20 transform hover:scale-105 transition-transform duration-500">
                    <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto object-contain"
                         width="1024" height="1024"
                         alt="{{ __('Logo Kampus Non-Merdeka') }}">
                </div>
                <div>
                    <p class="text-white font-black text-base md:text-lg tracking-tighter leading-none">BUS Kampus Non-Merdeka</p>
                    <p class="text-white/60 text-[8px] uppercase tracking-[0.3em] font-black mt-1.5">{{ __('User Portal') }}</p>
                </div>
            </div>
            
            {{-- Close button for mobile only --}}
            <button @click="mobileMenuOpen = false" class="lg:hidden text-white/50 hover:text-white p-2" aria-label="{{ __('Tutup Menu') }}">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-8 space-y-8 overflow-y-auto custom-scrollbar relative"
             role="navigation"
             aria-label="{{ __('Menu Utama User') }}">
             
            <div class="space-y-1.5">
                <a href="{{ route('user.dashboard') }}"
                   class="sidebar-link group {{ request()->routeIs('user.dashboard') ? 'active' : '' }} flex items-center gap-3 p-3 rounded-2xl hover:bg-white/5 transition-colors"
                   {{ request()->routeIs('user.dashboard') ? 'aria-current=page' : '' }}>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('user.dashboard') ? 'bg-white/20 shadow-md text-white' : 'text-white/70' }}" aria-hidden="true">
                        <i class="fas fa-home text-[12px]" aria-hidden="true"></i>
                    </div>
                    <span class="font-bold tracking-tight text-sm text-white/90 group-hover:text-white {{ request()->routeIs('user.dashboard') ? 'text-white' : '' }}">{{ __('Beranda') }}</span>
                </a>

                <a href="{{ route('user.buses') }}"
                   class="sidebar-link group {{ request()->routeIs('user.buses*') ? 'active' : '' }} flex items-center gap-3 p-3 rounded-2xl hover:bg-white/5 transition-colors"
                   {{ request()->routeIs('user.buses*') ? 'aria-current=page' : '' }}>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('user.buses*') ? 'bg-white/20 shadow-md text-white' : 'text-white/70' }}" aria-hidden="true">
                        <i class="fas fa-bus text-[12px]" aria-hidden="true"></i>
                    </div>
                    <span class="font-bold tracking-tight text-sm text-white/90 group-hover:text-white {{ request()->routeIs('user.buses*') ? 'text-white' : '' }}">{{ __('Daftar Bus') }}</span>
                </a>

                <a href="{{ route('user.bookings.index') }}"
                   class="sidebar-link group {{ request()->routeIs('user.bookings*') ? 'active' : '' }} flex items-center gap-3 p-3 rounded-2xl hover:bg-white/5 transition-colors"
                   {{ request()->routeIs('user.bookings*') ? 'aria-current=page' : '' }}>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all bg-white/5 group-hover:bg-white/10 {{ request()->routeIs('user.bookings*') ? 'bg-white/20 shadow-md text-white' : 'text-white/70' }}" aria-hidden="true">
                        <i class="fas fa-ticket text-[12px]" aria-hidden="true"></i>
                    </div>
                    <span class="font-bold tracking-tight text-sm text-white/90 group-hover:text-white {{ request()->routeIs('user.bookings*') ? 'text-white' : '' }}">{{ __('Tiket Saya') }}</span>
                </a>
            </div>
        </nav>

        {{-- User info + logout --}}
        <div class="p-6 relative">
            <div class="bg-black/20 md:rounded-3xl p-4 border border-white/5 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0 border border-white/10"
                     aria-hidden="true">
                    <span class="text-white font-black text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-[10px] sm:text-xs font-black truncate tracking-tight">{{ auth()->user()->name }}</p>
                    <p class="text-white/70 text-[8px] font-bold uppercase tracking-widest mt-0.5">{{ auth()->user()->role }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-8 h-8 rounded-xl bg-white/10 text-white hover:bg-white hover:text-[#821326] transition-all duration-300 flex items-center justify-center group"
                            aria-label="{{ __('Keluar dari akun') }}">
                        <i class="fas fa-power-off text-[10px] group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content Window --}}
    <div class="flex-1 flex flex-col overflow-hidden w-full relative">
        
        {{-- Background Pattern (decorative) --}}
        <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none" aria-hidden="true"
             style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        {{-- Top Header --}}
        <header class="bg-white/95 backdrop-blur-xl border-b border-slate-100 px-4 md:px-8 py-4 flex items-center justify-between z-30 sticky top-0"
                role="banner">
            
            <div class="flex items-center gap-3 md:gap-4 lg:gap-0">
                {{-- Hamburger Menu (Mobile/Tablet) --}}
                <button @click="mobileMenuOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-[#1e3a5f] bg-slate-50 rounded-xl" aria-label="{{ __('Buka Menu Navigasi') }}">
                    <i class="fas fa-bars"></i>
                </button>
            
                <div>
                    <h1 class="text-lg md:text-xl font-black text-[#1e3a5f] tracking-tighter uppercase line-clamp-1">{{ __($view_name ?? 'Dashboard') }}</h1>
                    <div class="flex items-center gap-2 mt-0.5 md:mt-1 hover:opacity-80 transition-opacity" aria-hidden="true">
                        <div class="w-1 h-1 rounded-full bg-emerald-500 shadow-[0_0_5px_#10b981] animate-pulse"></div>
                        <p class="text-[8px] md:text-[9px] font-black text-slate-500 uppercase tracking-widest hidden sm:block">{{ __('Bus Kampus Integrated System') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6">
                {{-- Language Switcher (Hidden on strictly small mobile, visible on sm+) --}}
                <div class="hidden sm:flex items-center bg-slate-50 p-1 rounded-[15px] border border-slate-100">
                    <a href="{{ route('lang.switch', 'id') }}"
                       class="px-2 md:px-3 py-1.5 rounded-xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all
                              {{ App::getLocale() === 'id' ? 'bg-white text-[#c41e3a] shadow-sm' : 'text-slate-500 hover:text-slate-600' }}">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="px-2 md:px-3 py-1.5 rounded-xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all
                              {{ App::getLocale() === 'en' ? 'bg-white text-[#c41e3a] shadow-sm' : 'text-slate-500 hover:text-slate-600' }}">EN</a>
                </div>

                {{-- Status Info (Desktop Only) --}}
                <div class="hidden lg:flex flex-col items-end border-r border-slate-100 pr-6 mr-1">
                    <p class="text-[11px] font-black text-[#1e3a5f] tracking-tight">{{ now()->translatedFormat('d M Y') }}</p>
                    <p class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">LIVE STATUS</p>
                </div>

                {{-- Notification / Alerts --}}
                <div class="flex-shrink-0">
                    @include('partials.notification-bell', ['variant' => 'user'])
                </div>

                {{-- User Avatar (Compact on Mobile) --}}
                <div class="flex items-center gap-3 ml-1">
                    <div class="text-right hidden xl:block">
                        <p class="text-[10px] font-black text-slate-700 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] text-slate-500 font-bold uppercase tracking-tighter mt-1">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-[#1e3a5f] to-[#0f2137] text-white flex items-center justify-center font-black shadow-lg shadow-navy-900/10 cursor-pointer hover:shadow-xl transition-all">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Interaction Area --}}
        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 lg:p-10 custom-scrollbar z-10 w-full" id="main-content" role="main">
            {{-- Flash Messages (Responsive container) --}}
            @if(session('success'))
                <div class="mb-6 md:mb-8 flex flex-col sm:flex-row items-center gap-4 bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 md:px-6 md:py-4 rounded-[1.5rem] md:rounded-[2rem] shadow-sm w-full"
                     id="flash-msg"
                     role="alert"
                     aria-live="polite"
                     aria-atomic="true">
                    <div class="w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/20" aria-hidden="true">
                        <i class="fas fa-check text-white text-sm" aria-hidden="true"></i>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <p class="font-black text-xs md:text-sm tracking-tight">{{ session('success') }}</p>
                    </div>
                    <button onclick="document.getElementById('flash-msg').remove()"
                            class="text-emerald-400 hover:text-emerald-600 bg-emerald-100/50 p-2 rounded-xl transition-all w-full sm:w-auto mt-2 sm:mt-0"
                            aria-label="{{ __('Tutup notifikasi') }}">
                        <i class="fas fa-times text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            @endif

            @yield('user-content')
        </main>
    </div>
</div>
@endsection