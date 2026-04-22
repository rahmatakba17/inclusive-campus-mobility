@extends('layouts.user', ['view_name' => 'Dashboard'])

@section('title', __('Dashboard'))

@section('page-title', __('Home'))
@section('page-desc', __('Welcome back') . ', ' . auth()->user()->name)

@section('user-content')
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-100 text-rose-700 px-6 py-4 rounded-2xl text-sm mb-8 flex gap-3 items-center shadow-sm relative overflow-hidden" role="alert">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500 rounded-l-2xl"></div>
            <i class="fas fa-exclamation-circle text-lg flex-shrink-0 text-rose-500"></i>
            <span class="font-bold flex-1">{{ session('error') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-700 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl text-sm mb-8 flex gap-3 items-center shadow-sm relative overflow-hidden" role="alert">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500 rounded-l-2xl"></div>
            <i class="fas fa-check-circle text-lg flex-shrink-0 text-emerald-500"></i>
            <span class="font-bold flex-1">{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-700 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Welcome Banner (PREMIUM) --}}
    <div class="relative rounded-[3rem] p-10 mb-10 text-white overflow-hidden shadow-2xl group" style="background: linear-gradient(135deg, #1e3a5f, #0f2137);">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/5 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-[#c41e3a]/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        
        <div class="flex items-center justify-between relative z-10">
            <div>
                <div class="flex items-center gap-2 mb-4 bg-white/10 backdrop-blur-md w-fit px-4 py-1.5 rounded-full border border-white/10">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#ffd700]">{{ __('Welcome back') }} 👋</p>
                </div>
                <h2 class="text-4xl font-black tracking-tighter mb-2">{{ auth()->user()->name }}</h2>
                <p class="text-white/40 text-xs font-medium max-w-sm tracking-tight leading-relaxed">{{ __('Book Kampus Non-Merdeka campus bus tickets easily') }}</p>
            </div>
            
            <div class="hidden lg:block relative">
                <div class="w-32 h-32 bg-white/5 backdrop-blur-xl rounded-[2.5rem] flex items-center justify-center border border-white/10 shadow-2xl relative group-hover:rotate-6 transition-transform duration-500">
                    <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" alt="Logo" class="w-20 h-auto opacity-70 grayscale invert drop-shadow-lg">
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-[#ffd700] rounded-2xl flex items-center justify-center shadow-lg transform group-hover:-translate-y-2 transition-transform">
                    <i class="fas fa-star text-[#1e3a5f] text-sm"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
        @php
            $stat_items = [
                ['key' => 'total_bookings', 'label' => 'Total Tickets', 'icon' => 'fa-ticket-alt', 'color' => 'navy', 'val' => $stats['total_bookings']],
                ['key' => 'confirmed',  'label' => 'Confirmed',  'icon' => 'fa-check-circle',  'color' => 'emerald', 'val' => $stats['confirmed']],
                ['key' => 'completed',  'label' => 'Completed',  'icon' => 'fa-flag-checkered','color' => 'violet',  'val' => $stats['completed']],
                ['key' => 'cancelled',  'label' => 'Cancelled',  'icon' => 'fa-times-circle',  'color' => 'rose',    'val' => $stats['cancelled']],
            ];
        @endphp

        @foreach($stat_items as $item)
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 transition-all group-hover:scale-110
                        {{ $item['color'] === 'navy'   ? 'bg-navy-50 text-[#1e3a5f]' : '' }}
                        {{ $item['color'] === 'emerald'? 'bg-emerald-50 text-emerald-600' : '' }}
                        {{ $item['color'] === 'amber'  ? 'bg-amber-50 text-amber-600' : '' }}
                        {{ $item['color'] === 'rose'   ? 'bg-rose-50 text-rose-600' : '' }}
                        {{ $item['color'] === 'violet' ? 'bg-violet-50 text-violet-600' : '' }}">
                        <i class="fas {{ $item['icon'] }} text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-800 tracking-tight leading-none mb-1">{{ $item['val'] }}</p>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __($item['label']) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Live Radar Map --}}
    <div class="bg-white p-2 rounded-[3rem] shadow-xl border border-slate-100 mb-10 overflow-hidden h-[400px] relative group">
        <div class="absolute top-6 left-6 z-10 bg-white/90 backdrop-blur px-5 py-3 rounded-2xl shadow-lg border border-slate-100 transform group-hover:scale-105 transition-transform duration-300">
            <h3 class="text-xs font-black text-[#1e3a5f] uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-satellite-dish text-blue-500 animate-pulse"></i> Live Radar
            </h3>
            <p class="text-[9px] text-slate-500 mt-1 uppercase font-bold tracking-wider">{{ __('Real-Time Fleet Monitoring') }}</p>
        </div>
        <iframe src="{{ route('map', ['embed' => true]) }}" class="w-full h-full rounded-[2.5rem]" frameborder="0"></iframe>
    </div>

    {{-- 3-col: Riwayat gabungan (1 col) + Bus Tamalanrea (1 col) + Bus Gowa (1 col) --}}
    <div x-data="liveAvailableBuses()" x-init="startPolling()" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 xl:gap-8 items-start">

        {{-- ===== Riwayat Tiket (gabungan semua rute) ===== --}}
        <div class="bg-white rounded-[2.5rem] p-7 border border-slate-100 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-black text-slate-800 tracking-tight uppercase">{{ __('Recent Tickets') }}</h3>
                <a href="{{ route('user.bookings.index') }}" class="text-[9px] font-black uppercase tracking-widest text-[#c41e3a] hover:text-slate-900 transition-colors flex items-center gap-1">
                    {{ __('View all') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="space-y-3 flex-1">
                @forelse($recent_bookings as $booking)
                    @php
                        $isGowa = str_contains(strtolower($booking->notes ?? ''), 'gowa -> kampus perintis')
                               || str_contains(strtolower($booking->notes ?? ''), 'gowa->kampus perintis')
                               || str_contains(strtolower($booking->notes ?? ''), 'gowa -> perintis');
                    @endphp
                    <a href="{{ route('user.bookings.show', $booking) }}"
                       class="flex items-center gap-4 p-3.5 bg-[#fafbfc] hover:bg-white hover:shadow-lg hover:shadow-slate-100 rounded-2xl border border-transparent hover:border-slate-100 transition-all duration-300 group/item">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform group-hover/item:scale-105"
                             style="background: linear-gradient(135deg, {{ $isGowa ? '#c41e3a, #8b0f24' : '#1e3a5f, #0f2137' }});">
                            <i class="fas fa-arrow-{{ $isGowa ? 'up' : 'down' }} text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black text-slate-800 truncate tracking-tight uppercase">{{ $booking->bus->name }}</p>
                            <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">
                                {{ $booking->booking_date->translatedFormat('d M Y') }}
                                <span class="ml-1 px-1.5 py-0.5 rounded text-[7px] font-black {{ $isGowa ? 'bg-[#c41e3a]/10 text-[#c41e3a]' : 'bg-[#1e3a5f]/10 text-[#1e3a5f]' }}">
                                    {{ $isGowa ? __('Gowa→Perintis') : __('Perintis→Gowa') }}
                                </span>
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border flex-shrink-0
                            {{ $booking->is_completed ? 'bg-violet-50 text-violet-600 border-violet-100' : '' }}
                            {{ !$booking->is_completed && $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                            {{ !$booking->is_completed && $booking->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}">
                            {{ __($booking->status_badge) }}
                        </span>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 opacity-30">
                        <i class="fas fa-ticket-alt text-3xl mb-3"></i>
                        <p class="text-[9px] font-black uppercase tracking-widest">{{ __('No tickets yet') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ===== Available Buses: Tamalanrea → Gowa ===== --}}
        <div class="bg-white rounded-[2.5rem] p-7 border border-slate-100 shadow-sm flex flex-col relative overflow-hidden">
            <div class="flex items-center justify-between mb-5 relative z-10">
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        <h3 class="text-sm font-black text-slate-800 tracking-tight uppercase">Tamalanrea → Gowa</h3>
                    </div>
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">{{ __('Perintis Departure Queue') }}</p>
                </div>
                <a href="{{ route('user.buses') }}" class="text-[9px] font-black uppercase tracking-widest text-[#c41e3a] hover:text-slate-900 transition-colors flex items-center gap-1">
                    {{ __('View all') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="space-y-2 relative z-10 flex-1" x-cloak>
                <template x-for="(bus, index) in busesTamalanrea" :key="'tamal-'+bus.id">
                    <div class="flex flex-col">
                        <div x-show="index === 1" class="flex items-center gap-2 py-2 opacity-60">
                            <div class="h-px bg-gradient-to-r from-transparent to-slate-200 flex-1"></div>
                            <span class="text-[7px] font-black uppercase tracking-widest text-slate-500">{{ __('Queue') }}</span>
                            <div class="h-px bg-gradient-to-l from-transparent to-slate-200 flex-1"></div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl border transition-all duration-300 relative overflow-hidden"
                             :class="bus.trip_status === 'jalan' ? 'border-blue-400 bg-blue-50 shadow-sm' 
                                 : (index === 0 ? 'border-[#ffd700] bg-[rgba(255,215,0,0.08)] shadow-sm'
                                 : 'border-slate-100 bg-[#fafbfc] hover:bg-white hover:shadow-md')">

                            <div class="absolute right-0 top-0 bottom-0 w-1"
                                 :class="bus.trip_status === 'jalan' ? 'bg-blue-500' : (index === 0 ? 'bg-[#1e3a5f]' : 'bg-slate-200')"></div>

                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 relative"
                                 :class="bus.trip_status === 'jalan' ? 'bg-blue-500' : (index === 0 ? 'bg-[#ffd700]' : 'bg-slate-100')">
                                <i class="fas fa-bus text-xs" :class="bus.trip_status === 'jalan' ? 'text-white' : (index === 0 ? 'text-[#1e3a5f]' : 'text-slate-500')"></i>
                                <span x-show="bus.trip_status === 'jalan'" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-[9px] font-black text-slate-800 truncate tracking-tight uppercase" x-text="bus.name"></p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="text-[7px] font-bold text-white px-1.5 py-0.5 rounded uppercase flex-shrink-0"
                                          :class="bus.trip_status === 'jalan' ? 'bg-blue-500' : (index === 0 && bus.direction !== 'rest_tamal' ? 'bg-[#1e3a5f]' : (bus.direction === 'rest_tamal' ? 'bg-slate-500' : 'bg-amber-500'))"
                                          x-text="bus.trip_status === 'jalan' ? window._t.ON_WAY : (index === 0 && bus.direction !== 'rest_tamal' ? window._t.READY : (bus.direction === 'rest_tamal' ? window._t.JUST_ARRIVED : window._t.QUEUED))">
                                    </span>
                                    <p class="text-[7px] font-bold uppercase truncate"
                                       :class="bus.trip_status === 'jalan' ? 'text-blue-500' : (index === 0 ? 'text-[#1e3a5f]' : 'text-slate-500')"
                                       x-text="bus.true_eta !== undefined && bus.trip_status !== 'jalan' ? '~' + bus.true_eta + ' ' + window._t.MIN : (bus.trip_status === 'jalan' ? window._t.TO_GOWA : '')"></p>
                                </div>
                            </div>
                            <a :href="bus.trip_status === 'jalan' ? '#' : '/user/bookings/create/' + bus.id"
                               class="text-white font-black py-1.5 px-3 rounded-lg text-[7px] transition-all uppercase tracking-widest flex-shrink-0"
                               :class="bus.trip_status === 'jalan' ? 'bg-blue-300 opacity-70 cursor-not-allowed' : (index === 0 ? 'bg-[#1e3a5f] hover:bg-slate-900 shadow-md shadow-navy-600/20' : 'bg-slate-400 hover:bg-slate-500')"
                               @click="bus.trip_status === 'jalan' ? $event.preventDefault() : (bus.trip_status !== 'standby' && $event.preventDefault())"
                               x-text="bus.trip_status === 'jalan' ? window._t.ON_ROAD : (index === 0 ? window._t.BOOK : window._t.QUEUED)">
                            </a>
                        </div>
                    </div>
                </template>

                <div x-show="busesTamalanrea.length === 0" class="py-10 flex flex-col items-center opacity-30">
                    <i class="fas fa-bus-slash text-2xl mb-2"></i>
                    <p class="text-[8px] font-bold uppercase tracking-widest">{{ __('No buses available') }}</p>
                </div>
            </div>
        </div>

        {{-- ===== Available Buses: Gowa → Tamalanrea ===== --}}
        <div class="bg-white rounded-[2.5rem] p-7 border border-slate-100 shadow-sm flex flex-col relative overflow-hidden">
            <div class="flex items-center justify-between mb-5 relative z-10">
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        <h3 class="text-sm font-black text-slate-800 tracking-tight uppercase">Gowa → Tamalanrea</h3>
                    </div>
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">{{ __('Gowa Departure Queue') }}</p>
                </div>
                <a href="{{ route('user.buses') }}" class="text-[9px] font-black uppercase tracking-widest text-[#c41e3a] hover:text-slate-900 transition-colors flex items-center gap-1">
                    {{ __('View all') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="space-y-2 relative z-10 flex-1" x-cloak>
                <template x-for="(bus, index) in busesGowa" :key="'gowa-'+bus.id">
                    <div class="flex flex-col mb-2">
                        <div class="flex items-center gap-3 p-3 rounded-xl border transition-all duration-300 relative overflow-hidden"
                             :class="bus.trip_status === 'jalan' ? 'border-orange-400 bg-orange-50 shadow-sm'
                                 : (index === 0 ? 'border-[#ffd700] bg-[rgba(255,215,0,0.08)] shadow-sm'
                                 : 'border-slate-100 bg-[#fafbfc] hover:bg-white hover:shadow-md')">

                            <div class="absolute right-0 top-0 bottom-0 w-1"
                                 :class="bus.trip_status === 'jalan' ? 'bg-orange-500' : (index === 0 ? 'bg-[#c41e3a]' : 'bg-slate-200')"></div>

                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 relative"
                                 :class="bus.trip_status === 'jalan' ? 'bg-orange-500' : (index === 0 ? 'bg-[#ffd700]' : 'bg-slate-100')">
                                <i class="fas fa-bus text-xs" :class="bus.trip_status === 'jalan' ? 'text-white' : (index === 0 ? 'text-[#c41e3a]' : 'text-slate-500')"></i>
                                <span x-show="bus.trip_status === 'jalan'" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-[9px] font-black text-slate-800 truncate tracking-tight uppercase" x-text="bus.name"></p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="text-[7px] font-bold text-white px-1.5 py-0.5 rounded uppercase flex-shrink-0"
                                          :class="bus.trip_status === 'jalan' ? 'bg-orange-500' : (index === 0 ? 'bg-[#c41e3a]' : 'bg-amber-500')"
                                          x-text="bus.trip_status === 'jalan' ? window._t.ON_WAY : (index === 0 ? window._t.READY : window._t.STANDBY)">
                                    </span>
                                    <p class="text-[7px] font-bold uppercase truncate"
                                       :class="bus.trip_status === 'jalan' ? 'text-orange-500' : 'text-[#c41e3a]'"
                                       x-text="bus.true_eta !== undefined && bus.trip_status !== 'jalan' ? '~' + bus.true_eta + ' ' + window._t.MIN : (bus.trip_status === 'jalan' ? window._t.TO_TAMALANREA : '')"></p>
                                </div>
                            </div>
                            <a :href="bus.trip_status === 'jalan' ? '#' : '/user/bookings/create/' + bus.id + '?from=gowa'"
                               class="text-white font-black py-1.5 px-3 rounded-lg text-[7px] transition-all uppercase tracking-widest flex-shrink-0"
                               :class="bus.trip_status === 'jalan' ? 'bg-orange-300 opacity-70 cursor-not-allowed' : (index === 0 ? 'bg-[#c41e3a] hover:bg-[#a01830] shadow-md shadow-red-600/20' : 'bg-slate-400 hover:bg-slate-500')"
                               @click="bus.trip_status === 'jalan' ? $event.preventDefault() : (bus.direction !== 'rest_gowa' && $event.preventDefault())"
                               x-text="bus.trip_status === 'jalan' ? window._t.ON_ROAD : (index === 0 ? window._t.BOOK : window._t.STANDBY)">
                            </a>
                        </div>
                    </div>
                </template>

                <div x-show="busesGowa.length === 0" class="py-10 flex flex-col items-center opacity-30">
                    <i class="fas fa-bus-slash text-2xl mb-2"></i>
                    <p class="text-[8px] font-bold uppercase tracking-widest">{{ __('No buses available') }}</p>
                </div>
            </div>
        </div>

        <script>
            // i18n translation map for Alpine.js dynamic strings
            window._t = {
                ON_WAY:       '{{ __('ON WAY') }}',
                ON_ROAD:      '{{ __('EN ROUTE') }}',
                READY:        '{{ __('READY') }}',
                JUST_ARRIVED: '{{ __('JUST ARRIVED') }}',
                QUEUED:       '{{ __('QUEUED') }}',
                STANDBY:      '{{ __('STANDBY') }}',
                BOOK:         '{{ __('BOOK') }}',
                MIN:          '{{ __('min') }}',
                TO_GOWA:      '{{ __('To Gowa') }}',
                TO_TAMALANREA:'{{ __('To Tamalanrea') }}',
            };
            document.addEventListener('alpine:init', () => {
                Alpine.data('liveAvailableBuses', () => ({
                    buses: [],

                    get busesTamalanrea() {
                        return [...this.buses]
                            .filter(b => 
                                (b.trip_status === 'standby' && b.direction !== 'rest_gowa') ||
                                (b.trip_status === 'jalan' && b.direction === 'go')
                            )
                            .map(b => {
                                let true_eta = b.eta_minutes || 0;
                                if (b.trip_status === 'jalan') true_eta -= 1000;
                                else if (b.direction === 'rest_tamal') true_eta += 106;
                                return { ...b, true_eta };
                            })
                            .sort((a, b) => a.true_eta - b.true_eta)
                            .slice(0, 5);
                    },

                    get busesGowa() {
                        return [...this.buses]
                            .filter(b => 
                                b.direction === 'rest_gowa' ||
                                (b.trip_status === 'jalan' && b.direction === 'return')
                            )
                            .map(b => {
                                let true_eta = b.eta_minutes || 0;
                                if (b.trip_status === 'jalan') true_eta -= 1000;
                                return { ...b, true_eta };
                            })
                            .sort((a, b) => a.true_eta - b.true_eta)
                            .slice(0, 5);
                    },

                    startPolling() {
                        this.buses = @json($available_buses);
                        window.addEventListener('message', (e) => {
                            if (e.data && e.data.type === 'BUS_UPDATE') {
                                this.buses = e.data.buses;
                            } else if (e.data && e.data.type === 'TRIP_COMPLETED') {
                                window.location.reload();
                            }
                        });
                    }
                }));
            });
        </script>
    </div>
@endsection
