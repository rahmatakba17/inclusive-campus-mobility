@extends('layouts.app')

@section('title', __('Panduan Sistem Operasional Bus Kampus'))

@section('content')
<div class="min-h-screen bg-[#fafbfc] flex flex-col font-sans">
    
    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-xl border-b border-slate-100 shadow-sm" role="navigation" aria-label="{{ __('Navigasi Panduan') }}">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center p-1 border border-slate-100">
                    <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto object-contain" alt="{{ __('Logo Bus Kampus') }}">
                </div>
                <div>
                    <h1 class="text-[#1e3a5f] font-black text-sm uppercase tracking-widest leading-none">Bus Kampus</h1>
                    <p class="text-slate-500 text-[9px] font-bold uppercase tracking-[0.2em] mt-0.5">{{ __('Pusat Bantuan & Panduan') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Language Switcher --}}
                <div class="flex items-center bg-slate-50 p-1 rounded-xl border border-slate-100 shadow-inner">
                    <a href="{{ route('lang.switch', 'id') }}"
                       class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ App::getLocale() === 'id' ? 'bg-white text-[#c41e3a] shadow-sm border border-slate-100' : 'text-slate-500 hover:text-slate-600' }}">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ App::getLocale() === 'en' ? 'bg-white text-[#c41e3a] shadow-sm border border-slate-100' : 'text-slate-500 hover:text-slate-600' }}">EN</a>
                </div>

                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#c41e3a] transition-colors bg-slate-50 hover:bg-red-50 px-4 py-2 rounded-xl">
                    <i class="fas fa-arrow-left"></i> <span class="hidden sm:inline">{{ __('Kembali ke Beranda') }}</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Header --}}
    <header class="bg-[#1e3a5f] text-white pt-24 pb-28 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/30" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-[#1e3a5f] via-[#2a528a] to-[#c41e3a]/80 opacity-90" aria-hidden="true"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#c41e3a]/40 rounded-full blur-[100px]" aria-hidden="true"></div>
        
        <div class="max-w-4xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block py-1.5 px-4 rounded-full bg-white/10 text-[#ffd700] text-[10px] font-black uppercase tracking-widest mb-6 border border-white/20 shadow-lg">
                <i class="fas fa-book-open mr-2"></i> {{ __('Prosedur Operasional Standar (SOP)') }}
            </span>
            <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-6 leading-tight drop-shadow-md">
                {{ __('Tata Tertib Reservasi &') }}<br>
                <span class="text-[#ffd700]">{{ __('Validasi Tiket Elektronik') }}</span>
            </h2>
            <p class="text-slate-200 text-base md:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                {{ __('Dokumen teknis ini memuat instruksi dan regulasi resmi penggunaan layanan otomasi keberangkatan armada bus kampus lintas rute Perintis–Gowa bagi seluruh kategori penumpang.') }}
            </p>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 max-w-4xl mx-auto px-6 lg:px-8 -mt-16 relative z-20 pb-24" id="main-content">
        
        {{-- Section 1: Passenger Categories --}}
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 mb-8 relative overflow-hidden group hover:shadow-2xl hover:shadow-[#1e3a5f]/5 transition-shadow duration-500">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-full blur-3xl -z-10 group-hover:bg-blue-100/50 transition-colors"></div>
            
            <h3 class="text-2xl font-black text-[#1e3a5f] uppercase tracking-tight mb-8 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black shadow-sm text-lg border border-blue-100/50">1</div>
                {{ __('Kategori Penumpang & Hak Akses') }}
            </h3>
            
            <div class="grid md:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-blue-800 font-bold flex items-center gap-2 mb-2"><i class="fas fa-user-graduate"></i> {{ __('Sivitas Akademika') }}</h4>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium">{{ __('Requires University SSO login (email @kampus-non-merdeka.ac.id). Receives a subsidised fare (Rp3,000) and payment options using E-Toll Card or QRIS. Maximum of 4 seats per transaction.') }}</p>
                    </div>
                    <div>
                        <h4 class="text-emerald-700 font-bold flex items-center gap-2 mb-2"><i class="fas fa-globe"></i> {{ __('Masyarakat Umum / Tamu') }}</h4>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium">{{ __('Uses the Guest Access feature without login. Subject to a base fare (Rp5,000) to support bus operations. Payment must be via QRIS only (E-Toll is exclusively designed for Civitas ID Cards). Maximum of 1 seat per transaction.') }}</p>
                    </div>
                    <div>
                        <h4 class="text-rose-700 font-bold flex items-center gap-2 mb-2"><i class="fas fa-qrcode"></i> {{ __('Validasi E-Ticket Virtual') }}</h4>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium">{{ __('The system issues a single-use E-Ticket Barcode once the transaction is secured. Passengers must display a screenshot of the barcode on their app or phone when boarding the bus for authorisation by the officer.') }}</p>
                    </div>
                </div>
                <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-100 aspect-square md:aspect-auto">
                    <img src="{{ asset('images/external/bus-interior.jpg') }}" class="w-full h-full object-cover" alt="{{ __('Interior Bus Transportasi') }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#1e3a5f]/90 via-[#1e3a5f]/30 to-transparent flex flex-col justify-end p-6 relative z-10">
                        <i class="fas fa-id-card text-3xl text-white/90 mb-3 drop-shadow-md"></i>
                        <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-1.5 drop-shadow-md">{{ __('Identifikasi Otomatis') }}</p>
                        <p class="text-sm text-white font-medium leading-tight drop-shadow-md">{{ __('The smart system detects the user profile to differentiate order quota limits, payment method availability (E-Toll/QRIS), and integrated travel insurance limits.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Priority Seat --}}
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 mb-8 relative overflow-hidden group hover:shadow-2xl hover:shadow-amber-900/5 transition-shadow duration-500">
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-50/50 rounded-full blur-3xl -z-10 group-hover:bg-amber-100/50 transition-colors"></div>

            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/60 rounded-2xl p-6 mb-8 flex items-start sm:items-center gap-5 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex-shrink-0 flex items-center justify-center text-amber-500 shadow-inner animate-pulse">
                    <i class="fab fa-accessible-icon text-xl"></i>
                </div>
                <div>
                    <h4 class="text-amber-800 font-black text-sm uppercase tracking-wide mb-1">{{ __('Reservation Module Inclusion Update v3.0') }}</h4>
                    <p class="text-sm text-amber-900/70 font-medium leading-relaxed">{{ __('The latest campus bus online messaging system update provides automated special seat management and automatic price subsidies to support an inclusive ecosystem.') }}</p>
                </div>
            </div>

            <h3 class="text-2xl font-black text-[#1e3a5f] uppercase tracking-tight mb-8 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black shadow-sm text-lg border border-amber-100/50">2</div>
                {{ __('Prosedur Spesifik Kursi Prioritas') }}
            </h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm transition-all hover:bg-white hover:border-slate-200 hover:shadow-md">
                    <div class="w-12 h-12 mb-4 bg-white border border-rose-100 rounded-full flex items-center justify-center shadow-sm text-rose-500 font-black text-xl">
                        <i class="fas fa-lock"></i>
                    </div>
                    <p class="font-black text-slate-800 text-sm uppercase tracking-wide mb-2">{{ __('10-Second Absolute Lock Timer') }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">{{ __('For passengers with disabilities, the front 4 seats will be computationally locked for a full 10 seconds from when the page is first rendered. The general public cannot occupy the seats while the countdown is running.') }}</p>
                </div>
                
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm transition-all hover:bg-white hover:border-slate-200 hover:shadow-md">
                    <div class="w-12 h-12 mb-4 bg-white border border-emerald-100 rounded-full flex items-center justify-center shadow-sm text-emerald-500 font-black text-xl">
                        <i class="fas fa-users-viewfinder"></i>
                    </div>
                    <p class="font-black text-slate-800 text-sm uppercase tracking-wide mb-2">{{ __('Released to the PUBLIC') }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">{{ __('If 10 seconds pass and the seat is not taken by a disabled passenger, the UI animation will change the seat icon indicating a public transition. The seat is then freely available on a first-come first-served basis.') }}</p>
                </div>

                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 shadow-sm transition-all hover:bg-white hover:border-slate-200 hover:shadow-md md:col-span-2 flex flex-col md:flex-row gap-4">
                    <div class="w-12 h-12 md:w-14 md:h-14 flex-shrink-0 bg-gradient-to-br from-blue-100 to-blue-200 border border-blue-300 rounded-full flex items-center justify-center shadow-sm text-blue-700 font-black text-xl">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <p class="font-black text-slate-800 text-sm uppercase tracking-wide mb-2">{{ __('Targeted Subsidy Policy') }}</p>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">{{ __('When you activate the "Priority Need Toggle", the interface requires you to select an intensity level. For wheelchair users (High), the entire cost is fully covered (Free Rp 0) with a maximum claim of 1 priority seat per equity transaction. For the Pregnant/Elderly category, the fare returns to normal but front-zone access is freely open.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Section 3: Realtime UI & Standing Area --}}
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 mb-8 relative overflow-hidden group hover:shadow-2xl hover:shadow-teal-900/5 transition-shadow duration-500">
            <div class="absolute top-0 right-0 w-64 h-64 bg-teal-50/50 rounded-full blur-3xl -z-10 group-hover:bg-teal-100/50 transition-colors"></div>
            
            <h3 class="text-2xl font-black text-[#1e3a5f] uppercase tracking-tight mb-8 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-black shadow-sm text-lg border border-teal-100/50">3</div>
                {{ __('Navigasi Kendaraan & Kapasitas Berdiri') }}
            </h3>
            
            <div class="grid md:grid-cols-2 gap-10">
                <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-100 min-h-[300px]">
                    <img src="{{ asset('images/external/ticket-validation.jpg') }}" class="w-full h-full object-cover absolute inset-0" alt="{{ __('Sistem Validasi Tiket Elektronik') }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-teal-900/90 via-teal-900/30 to-transparent flex flex-col justify-end p-6 relative z-10">
                        <i class="fas fa-satellite-dish text-3xl text-white/90 mb-3 drop-shadow-md"></i>
                        <p class="text-[10px] font-black text-teal-200 uppercase tracking-widest mb-1.5 drop-shadow-md">{{ __('Cross-Continental Realtime Sensor') }}</p>
                        <p class="text-sm text-white font-medium leading-tight drop-shadow-md">{{ __('Utilises radar map instruments to block tickets for fleets that have already left the terminal.') }}</p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div>
                        <h4 class="text-teal-800 font-bold flex items-center gap-2 mb-2"><i class="fas fa-walking"></i> {{ __('Standing Area (Flexible Standing)') }}</h4>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium">{{ __('The bus layout accommodates 16 main seats and 4 standing spots in the rear cabin sector (Capacity 17-20). This area automatically becomes an option whenever passenger flow peaks, without compromising boarding scanner comfort.') }}</p>
                    </div>
                    <div>
                        <h4 class="text-cyan-700 font-bold flex items-center gap-2 mb-2"><i class="fas fa-shield-alt"></i> {{ __('Moving Reservation Lock') }}</h4>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium">{{ __('If a fleet has transitioned to travel mode (Moving/Resting), the frontend system asynchronously disables the reservation button and instantly resets all booking simulations to prevent phantom queues.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-16 pb-8 border-t border-slate-200 border-dashed pt-12">
            <p class="text-sm font-medium text-slate-500 mb-6">{{ __('Experiencing operational confusion or a critical technical issue?') }}</p>
            <a href="mailto:transport@kampus-non-merdeka.ac.id" class="inline-flex items-center gap-3 px-8 py-4 bg-[#c41e3a] hover:bg-[#821326] text-white rounded-full font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-[#c41e3a]/20 hover:scale-[1.02] active:scale-[0.98]">
                <i class="fas fa-headset text-lg"></i> {{ __('Contact the Help Center') }}
            </a>
        </div>

    </main>

    <footer class="bg-white border-t border-slate-100 py-8 text-center relative z-20 mt-auto">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">&copy; {{ date('Y') }} {{ __('Campus Transport Operations Division') }}.</p>
    </footer>

</div>

<style>
@keyframes busping {
    0% { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(1.6); opacity: 0; }
}
</style>
@endsection
