@extends('layouts.sopir')

@section('title', 'Dashboard Sopir')

@push('styles')
<style>
    .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-2px); }

    .manifest-row { transition: background 0.15s; }
    .manifest-row:hover { background: rgba(255,255,255,0.04); }

    /* Status button animations */
    .status-btn { position: relative; overflow: hidden; transition: all 0.25s cubic-bezier(.4,0,.2,1); }
    .status-btn::after {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at center, rgba(255,255,255,0.15) 0%, transparent 70%);
        opacity: 0; transition: opacity 0.2s;
    }
    .status-btn:active::after { opacity: 1; }

    /* Seat badge */
    .seat-booked { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); }
    .seat-priority { background: rgba(59,130,246,0.12); color: #93c5fd; border: 1px solid rgba(59,130,246,0.25); }
    .seat-standing { background: rgba(20,184,166,0.1); color: #5eead4; border: 1px dashed rgba(20,184,166,0.3); }
    .seat-empty { background: rgba(100,116,139,0.1); color: #64748b; border: 1px solid rgba(100,116,139,0.15); }
    .seat-standing-booked { background: rgba(20,184,166,0.2); color: #2dd4bf; border: 1px solid rgba(20,184,166,0.4); }

    /* Scrollable manifest */
    .manifest-list { max-height: 70vh; overflow-y: auto; }

    /* Pulse dot */
    .live-dot { width: 8px; height: 8px; border-radius: 50%; background: #10b981; }
    .live-dot::after {
        content: ''; position: absolute; inset: -4px;
        border-radius: 50%; border: 2px solid #10b981;
        animation: ping 1.5s ease-out infinite;
    }
    @keyframes ping { 0%{transform:scale(1);opacity:1} 100%{transform:scale(2.5);opacity:0} }
</style>
@endpush

@section('sopir-content')

@if(!$hasBus)
{{-- ===== BELUM DITUGASKAN ===== --}}
<section class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4"
         aria-label="Informasi penugasan sopir">
    <div class="w-20 h-20 rounded-3xl bg-red-500/10 border border-red-500/20 flex items-center justify-center mb-6">
        <i class="fas fa-exclamation-triangle text-3xl text-red-400"></i>
    </div>
    <h1 class="text-2xl font-black text-white mb-2 tracking-tight">Belum Ditugaskan</h1>
    <p class="text-slate-500 text-sm max-w-md leading-relaxed">
        Anda belum ditugaskan untuk mengendarai armada bus manapun. Silakan hubungi Administrator sistem transportasi Bus Kampus Non-Merdeka.
    </p>
    <div class="mt-8 glass px-5 py-3 rounded-2xl">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kontak Admin</p>
        <p class="text-sm font-bold text-slate-300 mt-1">Sistem Transportasi Kampus Non-Merdeka</p>
    </div>
</section>

@else
{{-- ===== DASHBOARD UTAMA ===== --}}

<div x-data="statusManager('{{ $bus->trip_status ?? 'standby' }}', {{ $bus->id }})" x-init="init()">

    {{-- ===== HERO: Info Bus ===== --}}
    <section class="mb-6" aria-label="Informasi armada yang dikendarai">
        <div class="relative rounded-3xl overflow-hidden p-6 md:p-8"
             style="background: linear-gradient(135deg, #1e3a5f 0%, #0f2137 50%, #1a1a2e 100%);">

            {{-- Background accents --}}
            <div class="absolute -right-16 -top-16 w-48 h-48 rounded-full opacity-20"
                 style="background: radial-gradient(circle, #f59e0b, transparent);"></div>
            <div class="absolute -left-8 -bottom-8 w-32 h-32 rounded-full opacity-10"
                 style="background: radial-gradient(circle, #3b82f6, transparent);"></div>
            <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}"
                 class="absolute right-6 bottom-4 w-16 opacity-10 grayscale invert pointer-events-none"
                 alt="" aria-hidden="true">

            <div class="relative z-10">
                {{-- Route label --}}
                <div class="flex items-center gap-2 mb-3">
                    <div class="relative flex">
                        <div class="live-dot relative flex-shrink-0"></div>
                    </div>
                    <span class="text-[9px] font-black text-amber-400 uppercase tracking-[0.25em]">Rute Aktif Hari Ini</span>
                </div>

                {{-- Bus name --}}
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-1">{{ $bus->name }}</h1>
                <p class="text-base text-slate-300 font-medium mb-5">{{ $bus->route }}</p>

                {{-- Schedule chips + Status --}}
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2 glass px-4 py-2.5 rounded-2xl">
                        <i class="fas fa-play text-amber-400 text-[10px]"></i>
                        <div>
                            <p class="text-[8px] text-slate-500 uppercase tracking-widest font-bold">Berangkat</p>
                            <p class="text-sm font-black text-white font-mono">{{ substr($bus->departure_time, 0, 5) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 glass px-4 py-2.5 rounded-2xl">
                        <i class="fas fa-flag-checkered text-amber-400 text-[10px]"></i>
                        <div>
                            <p class="text-[8px] text-slate-500 uppercase tracking-widest font-bold">Tiba</p>
                            <p class="text-sm font-black text-white font-mono">{{ substr($bus->arrival_time, 0, 5) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 glass px-4 py-2.5 rounded-2xl">
                        <i class="fas fa-id-card text-slate-500 text-[10px]"></i>
                        <div>
                            <p class="text-[8px] text-slate-500 uppercase tracking-widest font-bold">Plat</p>
                            <p class="text-sm font-black text-white font-mono">{{ $bus->plate_number }}</p>
                        </div>
                    </div>
                    {{-- Dynamic status badge --}}
                    <div class="ml-auto">
                        <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest"
                              :class="{
                                  'status-jalan': status === 'jalan',
                                  'status-standby': status === 'standby',
                                  'status-istirahat': status === 'istirahat'
                              }">
                            <i class="fas"
                               :class="{
                                   'fa-road': status === 'jalan',
                                   'fa-clock': status === 'standby',
                                   'fa-moon': status === 'istirahat'
                               }"></i>
                            <span x-text="{'jalan':'Sedang Jalan','standby':'Ngetem','istirahat':'Istirahat'}[status]"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== STATS ROW ===== --}}
    <section class="grid grid-cols-3 gap-4 mb-6" aria-label="Statistik penumpang dan pendapatan hari ini">
        {{-- Total Penumpang --}}
        <article class="stat-card glass rounded-2xl p-4 md:p-5 border border-white/[0.08] flex flex-col gap-2">
            <div class="w-10 h-10 rounded-2xl bg-blue-500/15 border border-blue-500/20 flex items-center justify-center">
                <i class="fas fa-users text-blue-400 text-base"></i>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-black text-white leading-none">
                    {{ $totalPassengers }}<span class="text-sm font-semibold text-slate-500">/{{ $bus->capacity }}</span>
                </p>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">Penumpang</p>
            </div>
        </article>

        {{-- Sisa Kuota --}}
        @php $remaining = $bus->capacity - $totalPassengers; @endphp
        <article class="stat-card glass rounded-2xl p-4 md:p-5 border border-white/[0.08] flex flex-col gap-2">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center">
                <i class="fas fa-chair text-emerald-400 text-base"></i>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-black {{ $remaining > 0 ? 'text-emerald-400' : 'text-red-400' }} leading-none">
                    {{ $remaining }}
                </p>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">Sisa Kursi</p>
            </div>
        </article>

        {{-- Tip Hari Ini --}}
        <article class="stat-card rounded-2xl p-4 md:p-5 flex flex-col gap-2 border border-amber-500/20"
                 style="background: linear-gradient(135deg, rgba(245,158,11,0.1) 0%, rgba(251,191,36,0.05) 100%);">
            <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center">
                <i class="fas fa-star text-amber-400 text-base"></i>
            </div>
            <div>
                <p class="text-base md:text-lg font-black text-amber-400 leading-none">
                    Rp {{ number_format($tipToday, 0, ',', '.') }}
                </p>
                <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mt-1">{{ $tipCount }} Tip Hari Ini</p>
            </div>
        </article>
    </section>

    {{-- ===== LIVE STATUS CONTROL ===== --}}
    <section class="glass rounded-3xl p-5 md:p-6 border border-white/[0.08] mb-6"
             aria-label="Kontrol status perjalanan real-time">

        <header class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-2xl bg-blue-500/15 border border-blue-500/20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-satellite-dish text-blue-400 text-sm"></i>
            </div>
            <div>
                <h2 class="text-sm font-black text-white uppercase tracking-widest">Live Status Perjalanan</h2>
                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Terlihat real-time oleh penumpang</p>
            </div>
        </header>

        {{-- Status Buttons --}}
        <div class="grid grid-cols-2 gap-3 mb-3">
            {{-- Ngetem / Standby --}}
            <button type="button"
                    @click="updateStatus('standby')"
                    :disabled="isLoading"
                    :class="status === 'standby'
                        ? 'status-standby text-white glow scale-[1.02]'
                        : 'bg-white/[0.06] text-slate-500 border border-white/10 hover:border-amber-500/40 hover:text-amber-300 hover:bg-amber-500/10'"
                    class="status-btn py-4 px-3 rounded-2xl text-xs font-black transition-all flex flex-col items-center justify-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed"
                    aria-label="Set status ngetem atau standby">
                <i class="fas fa-clock text-xl"></i>
                <span>Ngetem</span>
                <span class="text-[8px] opacity-70 font-semibold normal-case">Di Terminal</span>
            </button>

            {{-- Mulai Jalan --}}
            <button type="button"
                    @click="updateStatus('jalan')"
                    :disabled="isLoading"
                    :class="status === 'jalan'
                        ? 'status-jalan text-white glow scale-[1.02]'
                        : 'bg-white/[0.06] text-slate-500 border border-white/10 hover:border-emerald-500/40 hover:text-emerald-300 hover:bg-emerald-500/10'"
                    class="status-btn py-4 px-3 rounded-2xl text-xs font-black transition-all flex flex-col items-center justify-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed"
                    aria-label="Set status mulai perjalanan">
                <i class="fas fa-road text-xl"></i>
                <span>Jalan</span>
                <span class="text-[8px] opacity-70 font-semibold normal-case">Mulai Perjalanan</span>
            </button>
        </div>

        {{-- Selesai Perjalanan --}}
        <button type="button"
                @click="finishTrip()"
                :disabled="isLoading"
                class="status-btn w-full py-4 rounded-2xl text-sm font-black transition-all flex items-center justify-center gap-2.5 disabled:opacity-40 disabled:cursor-not-allowed border border-[#ffd700]/20 hover:border-[#ffd700]/50"
                style="background: linear-gradient(135deg, rgba(30,58,95,0.8), rgba(15,33,70,0.9));"
                aria-label="Tandai perjalanan selesai, reset manifest">
            <i class="fas fa-flag-checkered text-[#ffd700] text-base"></i>
            <span class="text-white">Selesai Perjalanan</span>
            <span class="text-[9px] text-slate-500 font-semibold">(Tiba di Tujuan)</span>
        </button>

        {{-- Loading indicator --}}
        <div x-show="isLoading" x-cloak class="flex items-center justify-center gap-2 mt-3">
            <svg class="animate-spin w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Memperbarui...</span>
        </div>
    </section>

    {{-- ===== MINI MAP ===== --}}
    <section class="mb-6 rounded-3xl overflow-hidden border border-white/[0.08] relative h-72 md:h-96"
             aria-label="Peta posisi armada bus secara real-time">
        <div class="absolute top-4 left-4 z-10 glass-dark px-4 py-2 rounded-2xl shadow-lg">
            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest flex items-center gap-1.5">
                <i class="fas fa-satellite-dish text-blue-400 animate-pulse"></i>
                Live Radar · Armada Bus Kampus
            </p>
        </div>
        <iframe src="{{ route('map', ['embed' => true]) }}"
                class="w-full h-full rounded-3xl"
                frameborder="0"
                title="Peta posisi armada bus kampus Kampus Non-Merdeka secara real-time"
                loading="lazy"></iframe>
    </section>

    {{-- ===== LAPORAN HARIAN ======== --}}
    <section class="glass rounded-3xl p-5 md:p-6 border border-white/[0.08] mb-6" aria-label="Buat laporan harian armada">
        <header class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-2xl bg-amber-500/15 border border-amber-500/20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clipboard-check text-amber-400 text-sm"></i>
            </div>
            <div>
                <h2 class="text-sm font-black text-white uppercase tracking-widest">Inspeksi Laporan Harian</h2>
                <p class="text-[9px] text-slate-500 font-bold tracking-widest">Wajib diisi setiap mengakhiri shift per hari</p>
            </div>
        </header>

        <form action="{{ route('sopir.dashboard.report') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Kondisi Fisik / Mesin Bus</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="condition" value="good" class="peer sr-only" checked required>
                        <div class="text-center py-2.5 px-2 bg-white/[0.05] border border-white/[0.08] rounded-xl text-slate-300 font-bold text-[10px] uppercase peer-checked:bg-emerald-500/20 peer-checked:border-emerald-500/50 peer-checked:text-emerald-400 transition-all">
                            <i class="fas fa-check-circle block text-lg mb-1"></i> Normal
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="condition" value="needs_maintenance" class="peer sr-only">
                        <div class="text-center py-2.5 px-2 bg-white/[0.05] border border-white/[0.08] rounded-xl text-slate-300 font-bold text-[10px] uppercase peer-checked:bg-amber-500/20 peer-checked:border-amber-500/50 peer-checked:text-amber-400 transition-all">
                            <i class="fas fa-tools block text-lg mb-1"></i> Perlu Servis
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="condition" value="damaged" class="peer sr-only">
                        <div class="text-center py-2.5 px-2 bg-white/[0.05] border border-white/[0.08] rounded-xl text-slate-300 font-bold text-[10px] uppercase peer-checked:bg-red-500/20 peer-checked:border-red-500/50 peer-checked:text-red-400 transition-all">
                            <i class="fas fa-car-crash block text-lg mb-1"></i> Kandang
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Catatan Detail (Wajib isi keluhan jika ada)</label>
                <textarea name="notes" rows="2" placeholder="Sebutkan kondisi komponen, bahan bakar, atau keluhan teknis jika ada..." 
                          class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm text-slate-300 outline-none focus:border-amber-500/50 resize-none" required></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-amber-500 hover:bg-amber-400 text-amber-950 font-black text-xs uppercase tracking-widest rounded-xl transition-colors">
                <i class="fas fa-paper-plane mr-1.5"></i> Kirim Laporan Harian
            </button>
        </form>
    </section>

    {{-- ===== MANIFEST PENUMPANG ===== --}}
    <section aria-label="Manifest daftar penumpang hari ini">

        <header class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-orange-500/15 border border-orange-500/20 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-orange-400 text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-white uppercase tracking-widest">Manifest Penumpang</h2>
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">{{ $totalPassengers }} terisi · {{ $bus->capacity - $totalPassengers }} kosong</p>
                </div>
            </div>
            {{-- Legend --}}
            <div class="hidden md:flex items-center gap-3 text-[8px] font-bold uppercase tracking-widest">
                <span class="flex items-center gap-1 text-blue-400"><span class="w-2 h-2 rounded bg-blue-500/30 inline-block"></span> Prioritas</span>
                <span class="flex items-center gap-1 text-slate-500"><span class="w-2 h-2 rounded bg-slate-500/30 inline-block"></span> Reguler</span>
                <span class="flex items-center gap-1 text-teal-400"><span class="w-2 h-2 rounded bg-teal-500/30 inline-block border border-dashed border-teal-500/40"></span> Berdiri</span>
            </div>
        </header>

        <div class="glass rounded-3xl border border-white/[0.08] overflow-hidden">
            <ul class="manifest-list divide-y divide-white/[0.04]" role="list" aria-label="Daftar kursi dan penumpang">
                @for($i = 1; $i <= $bus->capacity; $i++)
                    @php
                        $booking    = $manifest[$i] ?? null;
                        $isPriority = $i <= 4;
                        $isStanding = $i > 16;
                        $seatLabel  = $isStanding ? 'B' . ($i - 16) : str_pad($i, 2, '0', STR_PAD_LEFT);
                        $seatType   = $isStanding ? 'Berdiri' : ($isPriority ? 'Prioritas' : 'Reguler');
                    @endphp

                    <li class="manifest-row px-4 py-3.5 flex items-center gap-3
                               {{ $isStanding && !$booking ? 'opacity-60' : '' }}"
                        role="listitem">

                        {{-- Seat Badge --}}
                        <div class="flex-shrink-0">
                            <span class="inline-flex w-9 h-9 items-center justify-center rounded-xl text-[11px] font-black
                                @if($booking && $isStanding) seat-standing-booked
                                @elseif($booking) seat-booked
                                @elseif($isPriority) seat-priority
                                @elseif($isStanding) seat-standing
                                @else seat-empty
                                @endif">
                                {{ $seatLabel }}
                            </span>
                        </div>

                        {{-- Passenger Info --}}
                        <div class="flex-1 min-w-0">
                            @if($booking)
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-bold text-white truncate">
                                        {{ $booking->user ? $booking->user->name : ($booking->guest_name ?? 'Tamu') }}
                                    </p>
                                    @if($booking->user)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[7px] font-black uppercase tracking-widest
                                            {{ $booking->user->isCivitas() ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25' : 'bg-slate-500/15 text-slate-500 border border-slate-500/25' }}">
                                            {{ $booking->user->isCivitas() ? 'CIVITAS' : 'UMUM' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[7px] font-black uppercase tracking-widest bg-orange-500/15 text-orange-400 border border-orange-500/25">TAMU</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 mt-0.5">
                                    <p class="text-[9px] font-mono text-slate-500">
                                        <i class="fas fa-ticket-alt mr-1 text-slate-600"></i>{{ $booking->booking_code }}
                                    </p>
                                    @if($booking->payment_method)
                                        <span class="text-[8px] font-black uppercase tracking-widest
                                            {{ $booking->payment_method === 'qris' ? 'text-pink-400' : 'text-blue-400' }}">
                                            <i class="fas {{ $booking->payment_method === 'qris' ? 'fa-qrcode' : 'fa-id-card' }} mr-0.5"></i>
                                            {{ strtoupper($booking->payment_method) }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <p class="text-[10px] text-slate-600 font-medium italic">
                                    [{{ $seatType }}] — Kosong
                                </p>
                            @endif
                        </div>

                        {{-- Status Icon --}}
                        <div class="flex-shrink-0 flex items-center justify-end">
                            @if($booking)
                                @if(!$booking->is_boarded && !$booking->is_completed)
                                    <button type="button" 
                                            @click="boardPassenger({{ $booking->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 border border-blue-500/30 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                                        <i class="fas fa-check"></i> Hadir
                                    </button>
                                @else
                                    <i class="fas fa-user-check text-sm {{ $isStanding ? 'text-teal-400' : 'text-emerald-400' }}" aria-label="Tervalidasi" title="Sudah divalidasi"></i>
                                @endif
                            @else
                                <i class="fas {{ $isStanding ? 'fa-minus text-teal-900' : 'fa-circle text-slate-800' }} text-xs mr-2" aria-hidden="true"></i>
                            @endif
                        </div>
                    </li>
                @endfor
            </ul>

            {{-- Footer manifest --}}
            <div class="px-5 py-3 border-t border-white/[0.06] flex items-center justify-between">
                <p class="text-[9px] font-bold text-slate-600 uppercase tracking-widest">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ $totalPassengers }}/{{ $bus->capacity }} kursi terisi
                </p>
                <div class="w-24 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         style="width: {{ $bus->capacity > 0 ? round(($totalPassengers / $bus->capacity) * 100) : 0 }}%;
                                background: {{ $totalPassengers >= $bus->capacity ? '#ef4444' : ($totalPassengers >= $bus->capacity * 0.8 ? '#f59e0b' : '#10b981') }};"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Hidden CSRF token for JS --}}
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

</div>{{-- end x-data --}}

{{-- ===== SCRIPTS ===== --}}
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('statusManager', (initialStatus, busId) => ({
            status: initialStatus,
            isLoading: false,
            busId: busId,
            lastTipCount: null,

            init() {
                setInterval(() => this.checkTips(), 30000);

                // Listen only for trip completion from simulation
                window.addEventListener('message', (e) => {
                    if (e.data && e.data.type === 'TRIP_COMPLETED') {
                        if (e.data.busId == this.busId) {
                            this.manifestFinished();
                        }
                    }
                    // NOTE: BUS_UPDATE auto-sync disabled — caused infinite MEMPERBAHARUI loop
                });
            },

            manifestFinished() {
                Swal.fire({
                    icon: 'info',
                    title: '🏁 Tiba di Tujuan',
                    text: 'Sistem mendeteksi bus telah sampai. Manifest di-reset.',
                    background: '#0f172a',
                    color: '#f1f5f9',
                    iconColor: '#3b82f6',
                    timer: 3000,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => window.location.reload());
            },

            async checkTips() {
                const ctrl = new AbortController();
                const tid  = setTimeout(() => ctrl.abort(), 8000);
                try {
                    const res  = await fetch("{{ route('sopir.dashboard.tips') }}", {
                        signal: ctrl.signal,
                        headers: { 'Accept': 'application/json' }
                    });
                    clearTimeout(tid);
                    const data = await res.json();
                    const newTips = data.tips || [];

                    if (this.lastTipCount === null) {
                        this.lastTipCount = 0;
                        return;
                    }

                    if (newTips.length > 0) {
                        for (const tip of newTips) {
                            const fmt = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(tip.amount);
                            Swal.fire({
                                icon: 'success',
                                title: '🎁 Tip Baru Masuk!',
                                html: `Seseorang memberikan apresiasi anonim sebesar <strong>${fmt}</strong>!`,
                                background: '#0f172a', color: '#f1f5f9',
                                toast: true, position: 'top-end',
                                showConfirmButton: false, timer: 8000, timerProgressBar: true
                            });
                        }
                    }
                } catch(e) { clearTimeout(tid); }
            },

            async finishTrip() {
                if (this.isLoading) return;
                const confirmResult = await Swal.fire({
                    title: 'Selesai Perjalanan?',
                    text: 'Seluruh manifest akan ditandai selesai dan di-reset untuk rute selanjutnya.',
                    icon: 'warning',
                    background: '#0f172a',
                    color: '#f1f5f9',
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonColor: '#1e3a5f',
                    cancelButtonColor: '#c41e3a',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal'
                });
                if (!confirmResult.isConfirmed) return;

                this.isLoading = true;
                try {
                    const response = await fetch("{{ route('sopir.dashboard.finish') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if (response.ok && data.success) {
                        await Swal.fire({
                            icon: 'success', title: '✅ Trip Selesai', text: data.message,
                            background: '#0f172a', color: '#f1f5f9',
                            timer: 2000, showConfirmButton: false, timerProgressBar: true
                        });
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Gagal memproses manifest.');
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Oops', text: error.message, background: '#0f172a', color: '#f1f5f9' });
                } finally {
                    this.isLoading = false;
                }
            },

            async updateStatus(newStatus, silent = false) {
                if (this.status === newStatus || this.isLoading) return;
                this.isLoading = true;
                const oldStatus = this.status;
                this.status = newStatus;

                // Safety: force-reset isLoading after 10s no matter what
                const safetyTimer = setTimeout(() => { this.isLoading = false; }, 10000);

                const ctrl = new AbortController();
                const tid  = setTimeout(() => ctrl.abort(), 8000);

                try {
                    const response = await fetch("{{ route('sopir.dashboard.status') }}", {
                        method: 'POST',
                        signal: ctrl.signal,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ _method: 'PATCH', trip_status: newStatus })
                    });
                    clearTimeout(tid);
                    const data = await response.json();
                    if (response.ok && data.success) {
                        if (!silent) {
                            Swal.fire({
                                icon: 'success', title: 'Status Diperbarui', text: data.message,
                                background: '#0f172a', color: '#f1f5f9',
                                toast: true, position: 'top-end',
                                showConfirmButton: false, timer: 3000, timerProgressBar: true
                            });
                        }
                    } else {
                        throw new Error(data.message || 'Gagal memperbarui status');
                    }
                } catch (error) {
                    clearTimeout(tid);
                    this.status = oldStatus;
                    if (!silent) Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, background: '#0f172a', color: '#f1f5f9' });
                } finally {
                    clearTimeout(safetyTimer);
                    this.isLoading = false;
                }
            },

            async boardPassenger(bookingId) {
                if (this.isLoading) return;
                this.isLoading = true;
                
                try {
                    const response = await fetch(`/sopir/dashboard/board/${bookingId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        Swal.fire({
                            icon: 'success', title: 'Tervalidasi', text: data.message,
                            background: '#0f172a', color: '#f1f5f9',
                            toast: true, position: 'top-end',
                            showConfirmButton: false, timer: 3000, timerProgressBar: true
                        }).then(() => window.location.reload());
                    } else {
                        throw new Error(data.message || 'Gagal memvalidasi penumpang');
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, background: '#0f172a', color: '#f1f5f9' });
                } finally {
                    this.isLoading = false;
                }
            }
        }));
    });
</script>
@endpush

@endif
@endsection