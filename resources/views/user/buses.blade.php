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
                    {{ __('Tickets can only be ordered for fleets with Standby status at the terminal. Sorted automatically by real-time queue.') }}
                </p>
            </div>
            
            <a href="{{ route('map') }}" target="_blank" aria-label="{{ __('Buka Peta Live') }}"
               class="relative z-10 bg-white border border-slate-200 hover:border-[#1e3a5f] hover:text-[#1e3a5f] text-slate-700 shadow-sm hover:shadow-md px-6 py-3.5 rounded-xl font-black transition-all flex items-center justify-center gap-2 text-[10px] uppercase tracking-widest w-full md:w-auto flex-shrink-0 group">
                <i class="fas fa-map-marked-alt text-slate-500 group-hover:text-[#1e3a5f] transition-colors" aria-hidden="true"></i> {{ __('Buka Peta Live') }}
            </a>
        </div>
    </section>



    {{-- Slider Section: Perintis -> Gowa --}}
    <section class="mb-14" aria-label="{{ __('Slider Daftar Armada Perintis ke Gowa') }}" x-data="busSlider()">
        
        <div class="flex items-center justify-between mb-5 px-1 bg-transparent">
            <div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">{{ __('Rute Perintis → Gowa') }}</h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">
                    <span x-text="Object.values(dynamicRouteGroup).filter(r => r === 'perintis_to_gowa').length || 0"></span> {{ __('Fleet') }}
                </p>
            </div>
            
            {{-- Custom Slider Navigation --}}
            <div class="flex items-center gap-2">
                <button @click="scrollLeft()" type="button" aria-label="{{ __('Scroll Left') }}"
                        class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-[#1e3a5f] hover:border-[#1e3a5f] transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20">
                    <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                </button>
                <button @click="scrollRight()" type="button" aria-label="{{ __('Scroll Right') }}"
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
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">
                    <span x-text="Object.values(dynamicRouteGroup).filter(r => r === 'gowa_to_perintis').length || 0"></span> {{ __('Fleet') }}
                </p>
            </div>
            
            {{-- Custom Slider Navigation --}}
            <div class="flex items-center gap-2">
                <button @click="scrollLeft()" type="button" aria-label="{{ __('Scroll Left') }}"
                        class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-[#1e3a5f] hover:border-[#1e3a5f] transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20">
                    <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                </button>
                <button @click="scrollRight()" type="button" aria-label="{{ __('Scroll Right') }}"
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
    // i18n for dynamic bus labels
    window._busT = {
        OPERATING:        '🚌 {{ __('Currently Operating') }}',
        ON_BREAK:         '⏸ {{ __('On Break') }}',
        VIEW_BOOK:        '{{ __('View & Book') }} →',
        NOT_AVAILABLE:    '{{ __('Not Available') }}',
        STATUS_JALAN:     '{{ __('On the Way') }}',
        STATUS_ISTIRAHAT: '{{ __('Resting') }}',
        STATUS_STANDBY:   '{{ __('Ready') }}',
    };
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
            
            fetchAndSortBuses() {
                var self = this;
                fetch('/api/simulation/buses')
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (!data || !data.buses || data.buses.length === 0) return;

                        BusSimulation.init(data.buses);
                        var positions = BusSimulation.getAllPositions();

                        // Map trip_status dari DB (sumber kebenaran bookability)
                        var dbStatusMap = {};
                        data.buses.forEach(function(b) { dbStatusMap[b.id] = b.trip_status; });

                        var newOrders      = {};
                        var newETAs        = {};
                        var newStatus      = {};
                        var newDirections  = {};
                        var newGroups      = {};

                        positions.forEach(function(pos, idx) {
                            var isGoingToGowa = ['go', 'queue', 'rest_tamal'].includes(pos.direction);
                            newGroups[pos.id] = isGoingToGowa ? 'perintis_to_gowa' : 'gowa_to_perintis';

                            var weight;
                            if (isGoingToGowa) {
                                if (pos.direction === 'queue')         weight = 0;
                                else if (pos.direction === 'rest_tamal') weight = 500;
                                else weight = 1000;
                            } else {
                                if (pos.direction === 'rest_gowa') weight = 0;
                                else weight = 1000;
                            }

                            newOrders[pos.id]    = weight + pos.eta_minutes + idx;
                            newETAs[pos.id]       = pos.eta_minutes;
                            var dbStat            = dbStatusMap[pos.id];
                            newStatus[pos.id]     = (dbStat !== 'standby') ? dbStat : pos.trip_status;
                            newDirections[pos.id] = pos.direction;
                        });

                        self.busOrder          = newOrders;
                        self.dynamicETA        = newETAs;
                        self.dynamicStatus     = newStatus;
                        self.dynamicDirection  = newDirections;
                        self.dynamicRouteGroup = newGroups;
                    })
                    .catch(function(e) {
                        console.error('Failed to sync bus ordering', e);
                    });
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
                if (stat === 'jalan')     return window._busT.OPERATING;
                if (stat === 'istirahat') return window._busT.ON_BREAK;
                return this.canBook(busId, targetRoute) ? window._busT.VIEW_BOOK : window._busT.NOT_AVAILABLE;
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
                    : 'bg-slate-100 text-slate-500 cursor-not-allowed border-transparent';
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