@extends('layouts.admin', ['view_name' => 'Dashboard'])

@section('title', __('Dashboard Admin — Bus Kampus Non-Merdeka'))
@section('admin-content')

{{-- ===== STAT CARDS ===== --}}
<section aria-label="Ringkasan statistik sistem" class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    @php
    $cards = [
        ['label' => 'Total Armada',   'value' => $stats['total_buses'],               'sub' => $stats['active_buses'].' aktif',        'icon' => 'fa-bus',             'color' => 'text-[#1e3a5f]',   'bg' => 'bg-[#1e3a5f]/6'],
        ['label' => 'Total Pemesanan','value' => $stats['total_bookings'],             'sub' => '+'.$stats['today_bookings'].' hari ini','icon' => 'fa-ticket-alt',      'color' => 'text-blue-600',    'bg' => 'bg-blue-50'],
        ['label' => 'Terverifikasi',  'value' => $stats['confirmed_bookings'],         'sub' => $stats['pending_bookings'].' menunggu',  'icon' => 'fa-check-circle',    'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
        ['label' => 'Tip Hari Ini',   'value' => 'Rp '.number_format($stats['tip_today_amount'],0,',','.'), 'sub' => $stats['tip_today_count'].' transaksi', 'icon' => 'fa-star', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
    ];
    @endphp

    @foreach($cards as $card)
    <article class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $card['label'] }}</p>
            <div class="w-8 h-8 {{ $card['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas {{ $card['icon'] }} {{ $card['color'] }} text-xs"></i>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900 leading-none tracking-tight">{{ $card['value'] }}</p>
        <p class="text-[10px] text-slate-400 font-semibold mt-1.5">{{ $card['sub'] }}</p>
    </article>
    @endforeach

</section>

{{-- ===== LIVE FLEET STATUS ===== --}}
<section x-data="liveFleet()" x-init="startPolling()"
         class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm mb-6"
         aria-label="Status armada bus secara real-time">

    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
            <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest">Live Status Armada</h2>
        </div>
        <span class="text-[9px] text-slate-400 font-mono" x-text="lastUpdate" aria-live="polite"></span>
    </div>

    <div class="grid grid-cols-4 gap-3">
        @foreach([
            ['key'=>'total',    'label'=>'Total',     'color'=>'text-slate-700', 'bg'=>'bg-slate-50'],
            ['key'=>'jalan',    'label'=>'Jalan',     'color'=>'text-emerald-600','bg'=>'bg-emerald-50'],
            ['key'=>'standby',  'label'=>'Standby',   'color'=>'text-amber-600', 'bg'=>'bg-amber-50'],
            ['key'=>'istirahat','label'=>'Istirahat', 'color'=>'text-slate-500', 'bg'=>'bg-slate-50'],
        ] as $s)
        <div class="{{ $s['bg'] }} rounded-xl px-3 py-2.5 text-center border border-transparent">
            <p class="text-xl font-black {{ $s['color'] }}" x-text="fleet.{{ $s['key'] }}">—</p>
            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

</section>

{{-- ===== MAIN GRID ===== --}}
<div class="grid lg:grid-cols-3 gap-6">

    {{-- Recent Bookings --}}
    <section class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden"
             aria-label="Pemesanan terbaru">

        <header class="px-5 py-4 border-b border-slate-50 flex items-center justify-between">
            <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest">Pemesanan Terbaru</h2>
            <a href="{{ route('admin.bookings.index') }}"
               class="text-[9px] font-black text-[#1e3a5f] hover:text-blue-700 uppercase tracking-widest transition-colors flex items-center gap-1"
               aria-label="Lihat semua pemesanan">
                Lihat Semua <i class="fas fa-arrow-right text-[8px]"></i>
            </a>
        </header>

        <div class="divide-y divide-slate-50">
            @forelse($recent_bookings as $booking)
            <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-slate-50/60 transition-colors">

                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 font-black text-xs flex items-center justify-center flex-shrink-0">
                    {{ substr($booking->passenger_name, 0, 1) }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ $booking->passenger_name }}</p>
                    <p class="text-[10px] text-slate-400 font-medium truncate">
                        {{ $booking->bus->name }}
                        @if($booking->payment_method)
                            · <span class="{{ $booking->payment_method === 'qris' ? 'text-rose-400' : 'text-blue-400' }} font-bold uppercase">{{ $booking->payment_method }}</span>
                        @endif
                    </p>
                </div>

                {{-- Status + Date --}}
                <div class="text-right flex-shrink-0">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest
                        {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($booking->status === 'cancelled' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-700') }}">
                        {{ $booking->status }}
                    </span>
                    <p class="text-[9px] text-slate-400 font-mono mt-1">{{ $booking->booking_date->format('d M') }} · {{ $booking->created_at->format('H:i') }}</p>
                </div>
            </div>
            @empty
            <div class="py-16 text-center">
                <i class="fas fa-inbox text-2xl text-slate-200 mb-2 block"></i>
                <p class="text-xs text-slate-400 font-medium">Belum ada pemesanan</p>
            </div>
            @endforelse
        </div>

    </section>

    {{-- Right Sidebar --}}
    <div class="space-y-4">

        {{-- Top Fleet --}}
        <section class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5"
                 aria-label="Armada dengan pemesanan terbanyak">
            <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest mb-4">Armada Terpopuler</h2>
            <ol class="space-y-3" role="list">
                @foreach($popular_buses as $i => $bus)
                <li class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-lg text-[9px] font-black flex items-center justify-center flex-shrink-0
                        {{ $i === 0 ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500' }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $bus->name }}</p>
                        <div class="w-full h-1 bg-slate-100 rounded-full mt-1 overflow-hidden">
                            @php $maxCount = $popular_buses->max('bookings_count') ?: 1; @endphp
                            <div class="h-full rounded-full bg-[#1e3a5f]/40"
                                 style="width:{{ round(($bus->bookings_count / $maxCount) * 100) }}%"></div>
                        </div>
                    </div>
                    <span class="text-[9px] font-black text-slate-400 flex-shrink-0">{{ $bus->bookings_count }}</span>
                </li>
                @endforeach
            </ol>
        </section>

        {{-- Active Users --}}
        <section class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5"
                 aria-label="Pengguna aktif dengan pemesanan terbanyak">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest">Pengguna Aktif</h2>
                <a href="{{ route('admin.users.index') }}"
                   class="text-[9px] font-black text-slate-400 hover:text-[#1e3a5f] uppercase tracking-widest transition-colors"
                   aria-label="Kelola pengguna">
                    Kelola <i class="fas fa-arrow-right text-[8px]"></i>
                </a>
            </div>
            <ul class="space-y-3" role="list">
                @foreach($active_users as $user)
                <li class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#1e3a5f]/8 text-[#1e3a5f] rounded-xl font-black text-xs flex items-center justify-center flex-shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $user->name }}</p>
                        <p class="text-[9px] text-slate-400 font-medium">{{ $user->bookings_count }} tiket</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0" title="Aktif"></span>
                </li>
                @endforeach
            </ul>
        </section>

        {{-- Tip Feed --}}
        <section x-data="tipFeed()" x-init="startPolling()"
                 class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5"
                 aria-label="Aktivitas tip sopir hari ini">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-black text-slate-700 uppercase tracking-widest">Tip Terkini</h2>
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse flex-shrink-0"></span>
            </div>

            {{-- Daily total --}}
            <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-2.5 mb-4 flex items-center justify-between">
                <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest">Total Hari Ini</p>
                <p class="text-sm font-black text-amber-700" x-text="'Rp ' + totalToday.toLocaleString('id-ID')">—</p>
            </div>

            <ul class="space-y-2 max-h-44 overflow-y-auto" role="list">
                <template x-for="tip in tips" :key="tip.id">
                    <li class="flex items-center gap-3 py-1.5">
                        <i class="fas fa-gift text-amber-400 text-xs flex-shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-700 truncate" x-text="tip.bus_name"></p>
                            <p class="text-[9px] text-slate-400" x-text="tip.time"></p>
                        </div>
                        <span class="text-xs font-black text-emerald-600 flex-shrink-0" x-text="'Rp ' + tip.amount.toLocaleString('id-ID')"></span>
                    </li>
                </template>
                <div x-show="tips.length === 0" class="py-6 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Belum ada tip hari ini</p>
                </div>
            </ul>
        </section>

    </div>

</div>

<script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('liveFleet', () => ({
            fleet: { total: '—', jalan: '—', standby: '—', istirahat: '—' },
            lastUpdate: '',
            async startPolling() {
                try {
                    // Initial fetch parameter bus dari API
                    const res  = await fetch('/api/simulation/buses');
                    const data = await res.json();
                    
                    if(window.BusSimulation) {
                        BusSimulation.init(data.buses);
                        
                        // Update UI sesuai kecepatan simulasi map (tiap 1.5 detik)
                        const updateUI = () => {
                            const positions = BusSimulation.getAllPositions();
                            const jalan     = positions.filter(b => b.trip_status === 'jalan').length;
                            const standby   = positions.filter(b => b.trip_status === 'standby' || b.direction === 'rest_gowa').length;
                            const istirahat = positions.filter(b => b.trip_status === 'istirahat' && b.direction !== 'rest_gowa').length;
                            
                            this.fleet = {
                                total: positions.length,
                                jalan: jalan,
                                standby: standby,
                                istirahat: istirahat
                            };
                            this.lastUpdate = new Date().toLocaleTimeString('id-ID');
                        };
                        
                        updateUI();
                        setInterval(updateUI, 1500); // Sinkron presisi dengan peta real-time
                        
                        // Refresh data dasar dari API setiap 15 detik untuk update status driver dll
                        setInterval(async () => {
                            try {
                                const refreshRes = await fetch('/api/simulation/buses');
                                const refreshData = await refreshRes.json();
                                BusSimulation.refreshUserBookingState(refreshData.buses);
                            } catch(e) {}
                        }, 15000);
                    }
                } catch(e) {
                    console.error('Gagal memuat status live armada', e);
                }
            }
        }));

        Alpine.data('tipFeed', () => ({
            tips: [], totalToday: 0, countToday: 0,
            startPolling() {
                this.fetchTips();
                setInterval(() => this.fetchTips(), 15000);
            },
            async fetchTips() {
                try {
                    const res  = await fetch('/api/admin/tips');
                    const data = await res.json();
                    this.tips       = data.tips;
                    this.totalToday = data.total_today;
                    this.countToday = data.count_today;
                } catch(e) {}
            }
        }));
    });
</script>

@endsection