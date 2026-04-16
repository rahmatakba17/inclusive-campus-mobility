@extends('layouts.user')

@section('title', __('Daftar Bus'))
@section('page-title', __('Daftar Bus'))

@push('styles')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('user-content')

<main aria-label="{{ __('Halaman Daftar Bus Kampus') }}">

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-100 text-rose-700 px-6 py-4 rounded-2xl text-sm mb-8 flex gap-3 items-center shadow-sm mx-auto max-w-7xl px-4 mt-8 md:mt-0">
        <i class="fas fa-exclamation-circle text-lg flex-shrink-0"></i>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Clean Minimalistic Hero Banner --}}
    <section class="mb-10" aria-label="{{ __('Informasi Live Tracking') }}">
        <div class="bg-white border border-slate-200/60 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-6 shadow-sm relative overflow-hidden">
            
            <div class="absolute -right-20 -bottom-20 w-64 h-64 border-[40px] border-slate-50 rounded-full opacity-50 pointer-events-none" aria-hidden="true"></div>

            <div class="flex-1 relative z-10">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span>
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ __('Sistem Tracking Live') }}</span>
                </div>
                <h2 class="text-2xl font-black text-[#1e3a5f] tracking-tight uppercase mb-2">{{ __('Pantau & Pesan Tiket') }}</h2>
                <p class="text-xs text-slate-500 max-w-xl leading-relaxed font-medium">
                    Pemesanan tiket hanya dapat dilakukan pada armada dengan status <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-[0.4rem] font-bold text-[10px] uppercase tracking-wider mx-0.5">Standby</span> di titik terminal. Diurutkan secara otomatis sesuai <strong class="text-slate-700">antrian real-time</strong>.
                </p>
            </div>
            
            <a href="{{ route('map') }}" target="_blank" aria-label="{{ __('Buka Peta Live') }}"
               class="relative z-10 bg-white border border-slate-200 hover:border-[#1e3a5f] hover:text-[#1e3a5f] text-slate-700 shadow-sm hover:shadow-md px-6 py-3.5 rounded-xl font-black transition-all flex items-center justify-center gap-2 text-[10px] uppercase tracking-widest w-full md:w-auto flex-shrink-0 group">
                <i class="fas fa-map-marked-alt text-slate-400 group-hover:text-[#1e3a5f] transition-colors" aria-hidden="true"></i> {{ __('Buka Peta Live') }}
            </a>
        </div>
    </section>



    {{-- Slider Section: Perintis -> Gowa --}}
    <section class="mb-14" aria-label="{{ __('Slider Daftar Armada Perintis ke Gowa') }}" x-data="busSlider()">
        
        <div class="flex items-center justify-between mb-5 px-1 bg-transparent">
            <div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">{{ __('Rute Perintis → Gowa') }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                    <span x-text="Object.values(dynamicRouteGroup).filter(r => r === 'perintis_to_gowa').length || 0"></span> Armada
                </p>
            </div>
            
            {{-- Custom Slider Navigation --}}
            <div class="flex items-center gap-2">
                <button @click="scrollLeft()" type="button" aria-label="Geser ke Kiri"
                        class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#1e3a5f] hover:border-[#1e3a5f] transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20">
                    <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                </button>
                <button @click="scrollRight()" type="button" aria-label="Geser ke Kanan"
                        class="w-10 h-10 rounded-full bg-[#1e3a5f] flex items-center justify-center text-white hover:bg-slate-900 transition-colors shadow-sm shadow-[#1e3a5f]/20 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/50">
                    <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        {{-- Slider Container --}}
        <div x-ref="sliderContainer" 
             class="flex overflow-x-auto snap-x snap-mandatory gap-5 hide-scrollbar pb-8 pt-2 scroll-smooth pr-4 relative"
             style="display: flex;">
            
            @foreach($buses as $bus)
                @include('partials.bus-card', ['bus' => $bus, 'targetRoute' => 'perintis_to_gowa'])
            @endforeach
            
            {{-- Empty state handled by Alpine when no buses have this state --}}
            <div x-show="Object.keys(dynamicRouteGroup).length > 0 && Object.values(dynamicRouteGroup).filter(r => r === 'perintis_to_gowa').length === 0" 
                 x-cloak class="w-full bg-white rounded-3xl p-16 text-center border border-slate-100 shadow-sm order-first pb-10">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl mx-auto flex items-center justify-center mb-6">
                    <i class="fas fa-bus-slash text-2xl text-slate-300"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight mb-2">{{ __('Belum Ada Armada') }}</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">{{ __('Saat ini tidak ada bus kampus yang dapat dipesan.') }}</p>
            </div>
            
            {{-- Spacer --}}
            <div class="flex-none w-4" aria-hidden="true" style="order: 9999;"></div>
        </div>
    </section>

    {{-- Slider Section: Gowa -> Perintis --}}
    <section class="mb-10" aria-label="{{ __('Slider Daftar Armada Gowa ke Perintis') }}" x-data="busSlider()">
        
        <div class="flex items-center justify-between mb-5 px-1 bg-transparent">
            <div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">{{ __('Rute Gowa → Perintis') }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                    <span x-text="Object.values(dynamicRouteGroup).filter(r => r === 'gowa_to_perintis').length || 0"></span> Armada
                </p>
            </div>
            
            {{-- Custom Slider Navigation --}}
            <div class="flex items-center gap-2">
                <button @click="scrollLeft()" type="button" aria-label="Geser ke Kiri"
                        class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#1e3a5f] hover:border-[#1e3a5f] transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20">
                    <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                </button>
                <button @click="scrollRight()" type="button" aria-label="Geser ke Kanan"
                        class="w-10 h-10 rounded-full bg-[#1e3a5f] flex items-center justify-center text-white hover:bg-slate-900 transition-colors shadow-sm shadow-[#1e3a5f]/20 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/50">
                    <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        {{-- Slider Container --}}
        <div x-ref="sliderContainer" 
             class="flex overflow-x-auto snap-x snap-mandatory gap-5 hide-scrollbar pb-8 pt-2 scroll-smooth pr-4 relative"
             style="display: flex;">
            
            @foreach($buses as $bus)
                @include('partials.bus-card', ['bus' => $bus, 'targetRoute' => 'gowa_to_perintis'])
            @endforeach
            
            <div x-show="Object.keys(dynamicRouteGroup).length > 0 && Object.values(dynamicRouteGroup).filter(r => r === 'gowa_to_perintis').length === 0" 
                 x-cloak class="w-full bg-white rounded-3xl p-16 text-center border border-slate-100 shadow-sm order-first pb-10">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl mx-auto flex items-center justify-center mb-6">
                    <i class="fas fa-bus-slash text-2xl text-slate-300"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight mb-2">{{ __('Belum Ada Armada') }}</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">{{ __('Saat ini tidak ada bus kampus yang beroperasi di rute ini.') }}</p>
            </div>
            
            <div class="flex-none w-4" aria-hidden="true" style="order: 9999;"></div>
        </div>
    </section>
</main>

@push('scripts')
<script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('busSlider', () => ({
            busOrder: {},          // order css property per bus
            dynamicETA: {},        // display eta
            dynamicStatus: {},     // track realtime 'jalan', 'standby', 'istirahat'
            dynamicDirection: {},  // track direction: 'go','return','queue','rest_tamal','rest_gowa'
            dynamicRouteGroup: {}, // tracks which list the bus should belong to
            
            init() {
                this.fetchAndSortBuses();
                // Update sorting and ETAs every 5 seconds dynamically
                setInterval(() => this.fetchAndSortBuses(), 5000);
            },
            
            async fetchAndSortBuses() {
                try {
                    const res = await fetch('/api/simulation/buses');
                    const data = await res.json();
                    
                    BusSimulation.init(data.buses);
                    const positions = BusSimulation.getAllPositions();
                    
                    // Buat map trip_status dari DB (sumber kebenaran untuk bookability)
                    const dbStatusMap = {};
                    data.buses.forEach(b => { dbStatusMap[b.id] = b.trip_status; });
                    
                    let newOrders      = {};
                    let newETAs        = {};
                    let newStatus      = {};
                    let newDirections  = {};
                    let newGroups      = {};
                    
                    positions.forEach((pos, idx) => {
                        /**
                         * PENGELOMPOKAN RUTE REAL-TIME:
                         * Perintis → Gowa : direction = 'go' (sedang berangkat) | 'queue' (antri di Tamal) | 'rest_tamal' (baru tiba di Tamal)
                         * Gowa → Perintis  : direction = 'return' (sedang pulang)  | 'rest_gowa' (standby di Gowa, siap berangkat balik)
                         */
                        const isGoingToGowa = ['go', 'queue', 'rest_tamal'].includes(pos.direction);
                        newGroups[pos.id] = isGoingToGowa ? 'perintis_to_gowa' : 'gowa_to_perintis';

                        /**
                         * URUTAN TAMPILAN (semakin kecil = tampil lebih dulu):
                         * - Perintis→Gowa: queue standby (eta kecil) → rest_tamal → jalan (go)
                         * - Gowa→Perintis: rest_gowa (siap berangkat) → jalan (return)
                         */
                        let weight;
                        if (isGoingToGowa) {
                            if (pos.direction === 'queue')      weight = 0;     // siap antri → tampil dulu
                            else if (pos.direction === 'rest_tamal') weight = 500;  // baru tiba
                            else weight = 1000;                                      // sedang jalan ke Gowa
                        } else {
                            if (pos.direction === 'rest_gowa')  weight = 0;     // standby di Gowa → tampil dulu
                            else weight = 1000;                                      // sedang pulang ke Tamal
                        }

                        newOrders[pos.id]     = weight + pos.eta_minutes + idx;
                        newETAs[pos.id]        = pos.eta_minutes;
                        const dbStat           = dbStatusMap[pos.id];
                        newStatus[pos.id]      = (dbStat !== 'standby') ? dbStat : pos.trip_status;
                        newDirections[pos.id]  = pos.direction;
                    });
                    
                    this.busOrder          = newOrders;
                    this.dynamicETA        = newETAs;
                    this.dynamicStatus     = newStatus;
                    this.dynamicDirection  = newDirections;
                    this.dynamicRouteGroup = newGroups;
                    
                } catch(e) {
                    console.error("Failed to sync bus ordering", e);
                }
            },
            
            /** Apakah bus bisa dipesan untuk rute tertentu?
             *  Aturan: HANYA bus berstatus 'standby' di DB yang boleh dipesan.
             *  Direction digunakan untuk pengelompokan rute, namun bukan penentu bookability.
             */
            canBook(busId, targetRoute) {
                const stat = this.dynamicStatus[busId] || '';
                // Hanya izinkan jika status DB adalah standby
                if (stat !== 'standby') return false;
                // Arahkan ke rute yang sesuai berdasarkan direction
                const dir = this.dynamicDirection[busId] || '';
                if (targetRoute === 'perintis_to_gowa') return ['queue', 'rest_tamal', 'standby', ''].includes(dir);
                if (targetRoute === 'gowa_to_perintis')  return ['rest_gowa', 'standby', ''].includes(dir);
                return false;
            },

            /** Label tombol pesan */
            bookLabel(busId, targetRoute) {
                const stat = this.dynamicStatus[busId] || '';
                if (stat === 'jalan')     return '🚌 Sedang Beroperasi';
                if (stat === 'istirahat') return '⏸ Sedang Istirahat';
                return this.canBook(busId, targetRoute) ? 'Lihat & Pesan →' : 'Belum Tersedia';
            },

            /** CSS class tombol pesan */
            bookClass(busId, targetRoute) {
                const stat = this.dynamicStatus[busId] || '';
                if (stat === 'jalan')
                    return 'bg-blue-50 text-blue-500 cursor-not-allowed border-blue-100';
                if (stat === 'istirahat')
                    return 'bg-orange-50 text-orange-400 cursor-not-allowed border-orange-100';
                return this.canBook(busId, targetRoute)
                    ? 'bg-slate-900 border-slate-800 hover:bg-[#1e3a5f] hover:border-[#1e3a5f] text-white shadow-[0_5px_15px_rgba(0,0,0,0.1)]'
                    : 'bg-slate-100 text-slate-400 cursor-not-allowed border-transparent';
            },

            /** URL tujuan tombol pesan */
            bookHref(busId, targetRoute, baseUrl) {
                if (!this.canBook(busId, targetRoute)) return '#';
                return targetRoute === 'gowa_to_perintis' ? baseUrl + '?from=gowa' : baseUrl;
            },

            scrollLeft() {
                if(this.$refs.sliderContainer) {
                    this.$refs.sliderContainer.scrollBy({ left: -360, behavior: 'smooth' });
                }
            },
            
            scrollRight() {
                if(this.$refs.sliderContainer) {
                    this.$refs.sliderContainer.scrollBy({ left: 360, behavior: 'smooth' });
                }
            }
        }));
    });
</script>
@endpush
@endsection