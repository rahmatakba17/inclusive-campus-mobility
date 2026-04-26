@extends('layouts.app')

@section('title', 'Daftar Armada - Tamu')

@push('styles')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')

<main class="min-h-screen flex flex-col pt-32 pb-16 bg-[#fafbfc]" aria-label="{{ __('Halaman Daftar Bus Kampus Tamu') }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl text-sm mb-8 flex gap-3 items-center shadow-sm">
            <i class="fas fa-exclamation-circle text-lg flex-shrink-0"></i>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
        @endif

        <div class="mb-16 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-[#1e3a5f] mb-6 uppercase tracking-tighter">{{ __('Pilih Armada Perjalanan') }}</h1>
            <p class="text-slate-500 max-w-2xl mx-auto font-medium text-lg leading-relaxed">
                Selamat datang tamu Kampus Non-Merdeka! Silakan pilih armada bus yang sedang bersiap di titik terminal untuk melakukan reservasi tiket sistem lepas (tanpa akun) dengan tarif flat <span class="bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded ml-1 tracking-widest text-sm">Rp 6.000</span>.
            </p>
            <div class="mt-6">
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border border-emerald-100 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Diurutkan Sesuai Antrean Real-Time
                </span>
            </div>
        </div>

        {{-- Live Radar Map --}}
        <section class="mb-12" aria-label="{{ __('Live Radar Peta Posisi Bus') }}">
            <div class="bg-white p-2 rounded-[3rem] shadow-xl border border-slate-100 overflow-hidden h-[400px] relative group mx-auto">
                <div class="absolute top-6 left-6 z-10 bg-white/90 backdrop-blur px-5 py-3 rounded-2xl shadow-lg border border-slate-100 transform group-hover:scale-105 transition-transform duration-300">
                    <h3 class="text-xs font-black text-[#1e3a5f] uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-satellite-dish text-blue-500 animate-pulse"></i> Live Radar
                    </h3>
                    <p class="text-[9px] text-slate-500 mt-1 uppercase font-bold tracking-wider">Pantauan Armada Real-time</p>
                </div>
                <iframe src="{{ route('map', ['embed' => true]) }}" class="w-full h-full rounded-[2.5rem]" frameborder="0"></iframe>
            </div>
        </section>

        {{-- Slider Section: Perintis -> Gowa --}}
        <section class="mb-16" aria-label="{{ __('Slider Daftar Armada Perintis ke Gowa') }}" x-data="busSlider()">
            
            <div class="flex items-center justify-between mb-6 px-1 bg-transparent border-b border-slate-200 pb-3">
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ __('Rute Perintis → Gowa') }}</h3>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">
                        <span x-text="Object.values(dynamicRouteGroup).filter(r => r === 'perintis_to_gowa').length || 0" class="text-[#c41e3a]"></span> Armada
                    </p>
                </div>
                
                {{-- Custom Slider Navigation --}}
                <div class="flex items-center gap-3">
                    <button @click="scrollLeft()" type="button" aria-label="Geser ke Kiri"
                            class="w-12 h-12 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-[#1e3a5f] hover:border-[#1e3a5f] hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20">
                        <i class="fas fa-chevron-left text-sm" aria-hidden="true"></i>
                    </button>
                    <button @click="scrollRight()" type="button" aria-label="Geser ke Kanan"
                            class="w-12 h-12 rounded-full bg-[#1e3a5f] flex items-center justify-center text-white hover:bg-slate-900 transition-all shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/50">
                        <i class="fas fa-chevron-right text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            {{-- Slider Container --}}
            <div x-ref="sliderContainer" 
                 class="flex overflow-x-auto snap-x snap-mandatory gap-6 hide-scrollbar pb-10 pt-4 scroll-smooth pr-4 relative"
                 style="display: flex;">
                
                @foreach($buses as $bus)
                    @include('partials.bus-card', ['bus' => $bus, 'targetRoute' => 'perintis_to_gowa', 'bookingRouteName' => 'guest.booking.create'])
                @endforeach
                
                {{-- Empty state handled by Alpine when no buses have this state --}}
                <div x-show="Object.keys(dynamicRouteGroup).length > 0 && Object.values(dynamicRouteGroup).filter(r => r === 'perintis_to_gowa').length === 0" 
                     x-cloak class="w-full bg-white rounded-[2rem] p-16 text-center border border-slate-100 shadow-sm order-first pb-12 flex flex-col items-center justify-center border-dashed">
                    <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-6 shadow-inner">
                        <i class="fas fa-bus-slash text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight mb-2">{{ __('Belum Ada Armada') }}</h3>
                    <p class="text-sm text-slate-500 max-w-sm">{{ __('Saat ini tidak ada bus kampus yang dapat dipesan untuk rute ini.') }}</p>
                </div>
                
                {{-- Spacer --}}
                <div class="flex-none w-6" aria-hidden="true" style="order: 9999;"></div>
            </div>
        </section>

        {{-- Slider Section: Gowa -> Perintis --}}
        <section class="mb-12" aria-label="{{ __('Slider Daftar Armada Gowa ke Perintis') }}" x-data="busSlider()">
            
            <div class="flex items-center justify-between mb-6 px-1 bg-transparent border-b border-slate-200 pb-3">
                <div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ __('Rute Gowa → Perintis') }}</h3>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-1">
                        <span x-text="Object.values(dynamicRouteGroup).filter(r => r === 'gowa_to_perintis').length || 0" class="text-[#c41e3a]"></span> Armada
                    </p>
                </div>
                
                {{-- Custom Slider Navigation --}}
                <div class="flex items-center gap-3">
                    <button @click="scrollLeft()" type="button" aria-label="Geser ke Kiri"
                            class="w-12 h-12 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-[#1e3a5f] hover:border-[#1e3a5f] hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20">
                        <i class="fas fa-chevron-left text-sm" aria-hidden="true"></i>
                    </button>
                    <button @click="scrollRight()" type="button" aria-label="Geser ke Kanan"
                            class="w-12 h-12 rounded-full bg-[#1e3a5f] flex items-center justify-center text-white hover:bg-slate-900 transition-all shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/50">
                        <i class="fas fa-chevron-right text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            {{-- Slider Container --}}
            <div x-ref="sliderContainer" 
                 class="flex overflow-x-auto snap-x snap-mandatory gap-6 hide-scrollbar pb-10 pt-4 scroll-smooth pr-4 relative"
                 style="display: flex;">
                
                @foreach($buses as $bus)
                    @include('partials.bus-card', ['bus' => $bus, 'targetRoute' => 'gowa_to_perintis', 'bookingRouteName' => 'guest.booking.create'])
                @endforeach
                
                {{-- Empty State --}}
                <div x-show="Object.keys(dynamicRouteGroup).length > 0 && Object.values(dynamicRouteGroup).filter(r => r === 'gowa_to_perintis').length === 0" 
                     x-cloak class="w-full bg-white rounded-[2rem] p-16 text-center border border-slate-100 shadow-sm order-first pb-12 flex flex-col items-center justify-center border-dashed">
                    <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-6 shadow-inner">
                        <i class="fas fa-bus-slash text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight mb-2">{{ __('Belum Ada Armada') }}</h3>
                    <p class="text-sm text-slate-500 max-w-sm">{{ __('Saat ini tidak ada bus kampus yang dapat dipesan untuk rute ini.') }}</p>
                </div>
                
                <div class="flex-none w-6" aria-hidden="true" style="order: 9999;"></div>
            </div>
        </section>

        <div class="mt-8 text-center border-t border-slate-200/60 pt-10">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-full bg-white border border-slate-200 text-[#1e3a5f] font-black tracking-widest uppercase text-xs hover:bg-[#1e3a5f] hover:text-white transition-all shadow-sm hover:shadow-xl">
                <i class="fas fa-arrow-left mr-3"></i>{{ __('Kembali ke Beranda') }}
            </a>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('busSlider', () => ({
            busOrder: {},
            dynamicETA: {},
            dynamicStatus: {},
            dynamicDirection: {},
            dynamicRouteGroup: {},
            
            init() {
                this.fetchAndSortBuses();
                setInterval(() => this.fetchAndSortBuses(), 5000);
            },
            
            async fetchAndSortBuses() {
                try {
                    const res = await fetch('/api/simulation/buses');
                    const data = await res.json();
                    
                    BusSimulation.init(data.buses);
                    const positions = BusSimulation.getAllPositions();
                    
                    const dbStatusMap = {};
                    data.buses.forEach(b => { dbStatusMap[b.id] = b.trip_status; });
                    
                    let newOrders      = {};
                    let newETAs        = {};
                    let newStatus      = {};
                    let newDirections  = {};
                    let newGroups      = {};
                    
                    positions.forEach((pos, idx) => {
                        const isGoingToGowa = ['go', 'queue', 'rest_tamal'].includes(pos.direction);
                        newGroups[pos.id] = isGoingToGowa ? 'perintis_to_gowa' : 'gowa_to_perintis';

                        // Urutkan murni berdasarkan ETA (waktu tunggu paling sedikit di awal)
                        // Kalikan 100 untuk memberikan ruang bagi index sebagai tie-breaker
                        newOrders[pos.id]      = (pos.eta_minutes * 100) + idx;
                        newETAs[pos.id]        = pos.eta_minutes;
                        const dbStat           = dbStatusMap[pos.id];
                        // [SIMULATION FIX] Hanya hormati status 'istirahat' dari DB, selebihnya percaya pada mesin simulasi map
                        newStatus[pos.id]      = (dbStat === 'istirahat') ? 'istirahat' : pos.trip_status;
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
            
            canBook(busId, targetRoute) {
                const stat = this.dynamicStatus[busId] || '';
                if (stat !== 'standby') return false;
                const dir = this.dynamicDirection[busId] || '';
                if (targetRoute === 'perintis_to_gowa') return ['queue', 'rest_tamal', 'standby', ''].includes(dir);
                if (targetRoute === 'gowa_to_perintis')  return ['rest_gowa', 'standby', ''].includes(dir);
                return false;
            },

            bookLabel(busId, targetRoute) {
                const stat = this.dynamicStatus[busId] || '';
                if (stat === 'jalan')     return '🚌 Sedang Beroperasi';
                if (stat === 'istirahat') return '⏸ Sedang Istirahat';
                return this.canBook(busId, targetRoute) ? 'Beli Tiket Flat Rp 6k →' : 'Belum Tersedia';
            },

            bookClass(busId, targetRoute) {
                const stat = this.dynamicStatus[busId] || '';
                if (stat === 'jalan')
                    return 'bg-blue-50 text-blue-500 cursor-not-allowed border-blue-100';
                if (stat === 'istirahat')
                    return 'bg-orange-50 text-orange-400 cursor-not-allowed border-orange-100';
                return this.canBook(busId, targetRoute)
                    ? 'bg-[#1e3a5f] border-[#1e3a5f] hover:bg-[#c41e3a] hover:border-[#c41e3a] text-white shadow-[0_5px_15px_rgba(0,0,0,0.2)]'
                    : 'bg-slate-100 text-slate-500 cursor-not-allowed border-transparent';
            },

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