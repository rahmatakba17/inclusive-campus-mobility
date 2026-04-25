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
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 xl:gap-8 items-start">

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

            <div id="queue-tamalanrea" class="space-y-2 relative z-10 flex-1">
                <div id="queue-tamalanrea-empty" class="py-10 flex flex-col items-center opacity-30">
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

            <div id="queue-gowa" class="space-y-2 relative z-10 flex-1">
                <div id="queue-gowa-empty" class="py-10 flex flex-col items-center opacity-30">
                    <i class="fas fa-bus-slash text-2xl mb-2"></i>
                    <p class="text-[8px] font-bold uppercase tracking-widest">{{ __('No buses available') }}</p>
                </div>
            </div>
        </div>

        <script>
        // i18n
        var _t = {
            ON_WAY:'{{ __('ON WAY') }}', ON_ROAD:'{{ __('EN ROUTE') }}',
            READY:'{{ __('READY') }}', JUST_ARRIVED:'{{ __('JUST ARRIVED') }}',
            QUEUED:'{{ __('QUEUED') }}', STANDBY:'{{ __('STANDBY') }}',
            BOOK:'{{ __('BOOK') }}', MIN:'{{ __('min') }}',
            TO_GOWA:'{{ __('To Gowa') }}', TO_TAMALANREA:'{{ __('To Tamalanrea') }}',
            NO_BUSES: '{{ __('No buses available') }}',
        };

        function buildBusCard(bus, index, isTamal) {
            var isJalan  = bus.trip_status === 'jalan';
            var isFirst  = index === 0;
            var isRest   = bus.direction === 'rest_tamal' || bus.direction === 'rest_gowa';
            var color    = isTamal ? '#1e3a5f' : '#c41e3a';
            var movColor = isTamal ? '#3b82f6' : '#f97316';
            var borderCls= isJalan ? 'border-color:'+movColor+';background:'+movColor+'11'
                         : isFirst ? 'border-color:#ffd700;background:rgba(255,215,0,0.08)'
                         : 'border-color:#e2e8f0;background:#fafbfc';
            var iconBg   = isJalan ? movColor : isFirst ? '#ffd700' : '#f1f5f9';
            var iconClr  = isJalan ? '#fff'   : isFirst ? color     : '#94a3b8';
            var badgeBg  = isJalan ? movColor : isFirst && !isRest ? color : '#f59e0b';
            var badgeTxt = isJalan ? _t.ON_WAY
                         : (isFirst && bus.direction !== 'rest_tamal') ? _t.READY
                         : bus.direction === 'rest_tamal' ? _t.JUST_ARRIVED : _t.QUEUED;
            if (!isTamal) badgeTxt = isJalan ? _t.ON_WAY : isFirst ? _t.READY : _t.STANDBY;
            var etaTxt   = (!isJalan && bus.eta_minutes != null)
                         ? ('~' + Math.round(bus.eta_minutes) + ' ' + _t.MIN)
                         : (isJalan ? (isTamal ? _t.TO_GOWA : _t.TO_TAMALANREA) : '');
            var btnHref  = isJalan ? '#' : ('/user/bookings/create/' + bus.id + (isTamal ? '' : '?from=gowa'));
            var btnLabel = isJalan ? _t.ON_ROAD : (isFirst ? _t.BOOK : (isTamal ? _t.QUEUED : _t.STANDBY));
            var btnBg    = isJalan ? movColor+'88' : isFirst ? color : '#94a3b8';
            var ping     = isJalan ? '<span style="position:absolute;top:-4px;right:-4px;width:10px;height:10px;background:red;border-radius:50%;animation:ping 1s infinite"></span>' : '';
            return '<div class="flex items-center gap-3 p-3 rounded-xl border mb-2 relative overflow-hidden" style="' + borderCls + ';">'
                + '<div style="position:absolute;right:0;top:0;bottom:0;width:3px;background:' + (isJalan?movColor:isFirst?color:'#e2e8f0') + '"></div>'
                + '<div style="width:40px;height:40px;border-radius:12px;background:' + iconBg + ';display:flex;align-items:center;justify-content:center;position:relative;flex-shrink:0">'
                + '<i class="fas fa-bus" style="color:' + iconClr + ';font-size:12px"></i>' + ping + '</div>'
                + '<div style="flex:1;min-width:0">'
                + '<p style="font-size:9px;font-weight:900;color:#1e293b;text-transform:uppercase;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' + bus.name + '</p>'
                + '<div style="display:flex;align-items:center;gap:4px;margin-top:2px">'
                + '<span style="font-size:7px;font-weight:700;color:#fff;padding:2px 6px;border-radius:4px;background:' + badgeBg + ';text-transform:uppercase">' + badgeTxt + '</span>'
                + '<span style="font-size:7px;font-weight:700;color:' + (isJalan?movColor:isFirst?color:'#64748b') + ';text-transform:uppercase">' + etaTxt + '</span>'
                + '</div></div>'
                + '<a href="' + btnHref + '" style="color:#fff;font-weight:900;padding:6px 10px;border-radius:8px;font-size:7px;letter-spacing:.08em;text-transform:uppercase;background:' + btnBg + ';text-decoration:none;white-space:nowrap;flex-shrink:0"' + (isJalan?' onclick="return false"':'') + '>' + btnLabel + '</a>'
                + '</div>';
        }

        function renderQueues(buses) {
            var tamalEl = document.getElementById('queue-tamalanrea');
            var gowaEl  = document.getElementById('queue-gowa');
            var emptyT  = document.getElementById('queue-tamalanrea-empty');
            var emptyG  = document.getElementById('queue-gowa-empty');
            if (!tamalEl || !gowaEl) return;

            var tamal = buses.filter(function(b) {
                return (b.trip_status === 'standby' && b.direction !== 'rest_gowa')
                    || (b.trip_status === 'jalan'   && b.direction === 'go');
            }).map(function(b) {
                var eta = b.eta_minutes || 0;
                if (b.trip_status === 'jalan') eta -= 1000;
                return Object.assign({}, b, {_w: eta});
            }).sort(function(a,b){return a._w - b._w;}).slice(0,5);

            var gowa = buses.filter(function(b) {
                return b.direction === 'rest_gowa'
                    || (b.trip_status === 'jalan' && b.direction === 'return');
            }).map(function(b) {
                var eta = b.eta_minutes || 0;
                if (b.trip_status === 'jalan') eta -= 1000;
                return Object.assign({}, b, {_w: eta});
            }).sort(function(a,b){return a._w - b._w;}).slice(0,5);

            // Clear previous dynamic cards
            tamalEl.querySelectorAll('.dyn-card').forEach(function(el){el.remove();});
            gowaEl.querySelectorAll('.dyn-card').forEach(function(el){el.remove();});

            if (tamal.length > 0) {
                emptyT && (emptyT.style.display = 'none');
                tamal.forEach(function(bus, i) {
                    var wrap = document.createElement('div');
                    wrap.className = 'dyn-card';
                    wrap.innerHTML = buildBusCard(bus, i, true);
                    tamalEl.insertBefore(wrap, emptyT);
                });
            } else {
                emptyT && (emptyT.style.display = '');
            }

            if (gowa.length > 0) {
                emptyG && (emptyG.style.display = 'none');
                gowa.forEach(function(bus, i) {
                    var wrap = document.createElement('div');
                    wrap.className = 'dyn-card';
                    wrap.innerHTML = buildBusCard(bus, i, false);
                    gowaEl.insertBefore(wrap, emptyG);
                });
            } else {
                emptyG && (emptyG.style.display = '');
            }
        }

        function pollQueues() {
            fetch('/api/simulation/buses')
                .then(function(r){ return r.json(); })
                .then(function(data) {
                    var raw = (data && data.buses) ? data.buses : [];
                    if (raw.length === 0) return;
                    if (typeof BusSimulation !== 'undefined') {
                        BusSimulation.init(raw);
                        renderQueues(BusSimulation.getAllPositions());
                    } else {
                        renderQueues(raw.map(function(b){
                            return Object.assign({}, b, {
                                direction: b.trip_status === 'standby' ? 'queue'
                                         : b.trip_status === 'istirahat' ? 'rest_tamal' : 'go',
                                eta_minutes: 5
                            });
                        }));
                    }
                }).catch(function(){});
        }

        // Mulai polling setelah DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            pollQueues();
            setInterval(pollQueues, 5000);
            // Reload saat TRIP_COMPLETED dari map iframe
            window.addEventListener('message', function(e) {
                if (e.data && e.data.type === 'TRIP_COMPLETED') window.location.reload();
            });
        });

        // CSS ping animation
        var st = document.createElement('style');
        st.textContent = '@keyframes ping{0%{transform:scale(1);opacity:1}100%{transform:scale(2);opacity:0}}';
        document.head.appendChild(st);
        </script>
    </div>

@push('scripts')
<script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
@endpush

@endsection

