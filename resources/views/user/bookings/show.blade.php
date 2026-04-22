@extends('layouts.user')

@section('title', __('Detail Tiket') . ' ' . $booking->booking_code)
@section('user-content')

<main class="max-w-xl mx-auto py-4" aria-label="{{ __('Detail Pemesanan Tiket') }}">

    {{-- Flash alerts --}}
    @if(session('success'))
    <div class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl text-sm shadow-sm" role="alert" aria-live="polite">
        <i class="fas fa-check-circle text-emerald-500 mt-0.5 text-lg flex-shrink-0" aria-hidden="true"></i>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 flex items-start gap-3 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-2xl text-sm shadow-sm" role="alert" aria-live="assertive">
        <i class="fas fa-exclamation-circle text-rose-400 mt-0.5 text-lg flex-shrink-0" aria-hidden="true"></i>
        <span class="font-semibold">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Top navigation --}}
    <nav class="mb-6" aria-label="{{ __('Navigasi Halaman') }}">
        <a href="{{ route('user.bookings.index') }}"
           class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#c41e3a] uppercase tracking-widest transition-colors mb-4 focus:outline-none focus:ring-2 focus:ring-[#c41e3a]/50 rounded-lg px-2 py-1 -ml-2">
            <i class="fas fa-arrow-left" aria-hidden="true"></i> {{ __('Kembali ke Riwayat') }}
        </a>
    </nav>

    {{-- Ticket card (Boarding Pass Style) --}}
    @php
        $isCompleted = $booking->is_completed;
        $isActive = !$isCompleted && $booking->status === 'confirmed';
        $isPending = !$isCompleted && $booking->status === 'pending';
        $isCancelled = $booking->status === 'cancelled';

        // Cek status realtime bus — pembatalan diblokir jika bus sudah berjalan/istirahat
        $busIsMoving = $booking->bus && in_array($booking->bus->trip_status, ['jalan', 'istirahat']);
        $canCancel   = ($isPending || $isActive) && !$busIsMoving;

        $notes = strtolower($booking->notes ?? '');
        $isGowa = str_contains($notes, 'gowa -> kampus perintis')
               || str_contains($notes, 'gowa->kampus perintis')
               || str_contains($notes, 'gowa -> perintis');
               
        $routeColor = $isGowa ? '#c41e3a' : '#1e3a5f';
        $originCode = $isGowa ? 'GWA' : 'PRT';
        $destCode = $isGowa ? 'PRT' : 'GWA';
        $originName = $isGowa ? 'Kampus Non-Merdeka Gowa' : 'Kampus Non-Merdeka Perintis';
        $destName = $isGowa ? 'Kampus Non-Merdeka Perintis' : 'Kampus Non-Merdeka Gowa';
    @endphp

    <article class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.07)] overflow-hidden relative group transform transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)]">
        
        {{-- Status Strip Top --}}
        <div class="h-2 w-full
            {{ $isCompleted ? 'bg-gradient-to-r from-violet-400 to-violet-600' : '' }}
            {{ $isActive    ? 'bg-gradient-to-r from-emerald-400 to-emerald-600' : '' }}
            {{ $isPending   ? 'bg-gradient-to-r from-amber-400 to-amber-500' : '' }}
            {{ $isCancelled ? 'bg-gradient-to-r from-rose-400 to-rose-600' : '' }}">
        </div>

        {{-- Ticket Header: Departure & Destination --}}
        <header class="p-8 relative overflow-hidden flex flex-col justify-between" style="background: linear-gradient(135deg, {{ $routeColor }}, {{ $routeColor }}ee);">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none" aria-hidden="true"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-black/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none" aria-hidden="true"></div>

            <div class="flex items-center justify-between mb-8 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md border border-white/10 shadow-inner" aria-hidden="true">
                        <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" alt="" class="w-6 h-auto opacity-90 drop-shadow-md">
                    </div>
                    <div>
                        <h1 class="text-white font-black tracking-tighter uppercase text-sm leading-none">BUS KAMPUS</h1>
                        <p class="text-white/60 text-[8px] uppercase tracking-[0.3em] font-black mt-1">E-TICKET</p>
                    </div>
                </div>
                
                {{-- Dynamic Status Badge --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border shadow-sm
                    {{ $isCompleted ? 'bg-white/20 text-white border-white/30 backdrop-blur-md' : '' }}
                    {{ $isActive    ? 'bg-white text-emerald-600 border-white/20 shadow-emerald-500/20' : '' }}
                    {{ $isPending   ? 'bg-amber-400 text-amber-900 border-amber-300 shadow-amber-500/20' : '' }}
                    {{ $isCancelled ? 'bg-rose-500 text-white border-rose-400 shadow-rose-500/20' : '' }}">
                    @if($isActive) <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span> @endif
                    {{ __($booking->status_badge) }}
                </span>
            </div>

            {{-- Big Route Display --}}
            <section class="flex items-center justify-between relative z-10" aria-label="{{ __('Rute Perjalanan') }}">
                <div class="text-left w-1/3">
                    <p class="text-white/70 text-[9px] font-black uppercase tracking-[0.2em] mb-1">Origin</p>
                    <p class="text-white text-4xl font-black tracking-tighter">{{ $originCode }}</p>
                    <p class="text-white/90 text-[10px] font-medium leading-tight mt-1 truncate" title="{{ $originName }}">{{ $originName }}</p>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center px-4 relative">
                    <i class="fas fa-bus text-white/50 text-xl absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10" aria-hidden="true"></i>
                    <div class="w-full flex items-center">
                        <div class="w-2 h-2 rounded-full border-2 border-white/50"></div>
                        <div class="flex-1 border-t-2 border-dashed border-white/30"></div>
                        <div class="w-2 h-2 rounded-full bg-white/80 shadow-[0_0_10px_rgba(255,255,255,0.8)]"></div>
                    </div>
                </div>

                <div class="text-right w-1/3">
                    <p class="text-white/70 text-[9px] font-black uppercase tracking-[0.2em] mb-1">Dest</p>
                    <p class="text-white text-4xl font-black tracking-tighter">{{ $destCode }}</p>
                    <p class="text-white/90 text-[10px] font-medium leading-tight mt-1 truncate" title="{{ $destName }}">{{ $destName }}</p>
                </div>
            </section>
        </header>

        {{-- Divider with circles for authentic ticket look --}}
        <div class="relative flex items-center bg-white" aria-hidden="true">
            <div class="absolute -left-4 w-8 h-8 rounded-full bg-[#fafbfc] border-r border-[#eaecf0] shadow-inner top-1/2 transform -translate-y-1/2 z-10"></div>
            <div class="flex-1 border-t-2 border-dashed border-slate-200 mx-6"></div>
            <div class="absolute -right-4 w-8 h-8 rounded-full bg-[#fafbfc] border-l border-[#eaecf0] shadow-inner top-1/2 transform -translate-y-1/2 z-10"></div>
        </div>

        {{-- Ticket Body Info --}}
        <section class="p-8 space-y-8 bg-white" aria-label="{{ __('Informasi Penumpang dan Bus') }}">
            <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                <div>
                    <h2 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">{{ __('Penumpang') }}</h2>
                    <p class="font-black text-slate-800 text-sm tracking-tight capitalize truncate" title="{{ $booking->user->name }}">{{ $booking->user->name }}</p>
                </div>
                <div>
                    <h2 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">{{ __('Tanggal') }}</h2>
                    <p class="font-black text-[#1e3a5f] text-sm tracking-tight">{{ $booking->booking_date->translatedFormat('d M Y') }}</p>
                </div>
                <div>
                    <h2 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">{{ __('Kode Bus / Armada') }}</h2>
                    <p class="font-black text-slate-800 text-sm tracking-tight flex items-center gap-2">
                        {{ $booking->bus->name }}
                    </p>
                    <p class="font-mono text-[10px] text-slate-500 mt-0.5">{{ $booking->bus->plate_number }}</p>
                </div>
                <div>
                    <h2 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">{{ __('Waktu & Rute') }}</h2>
                    <p class="font-black text-slate-800 text-sm tracking-tight">{{ $booking->bus->departure_time }} – {{ $booking->bus->arrival_time }}</p>
                    <p class="text-[9px] text-slate-500 uppercase font-bold mt-0.5">{{ $originCode }} → {{ $destCode }}</p>
                </div>
            </div>

            {{-- Highlight Area: Seat & Barcode --}}
            <div class="flex items-center justify-between p-5 rounded-2xl border-2 {{ $booking->seat_number > 16 ? 'bg-teal-50/30 border-teal-100' : 'bg-slate-50 border-slate-100' }} shadow-inner">
                <div class="flex-1">
                    <h2 class="text-[9px] {{ $booking->seat_number > 16 ? 'text-teal-600' : 'text-slate-500' }} font-black uppercase tracking-[0.2em] mb-1.5">{{ __('Posisi') }}</h2>
                    <div class="flex items-end gap-3">
                        <span class="{{ $booking->seat_number > 16 ? 'text-teal-700' : 'text-[#1e3a5f]' }} font-black text-4xl leading-none tracking-tighter">
                            {{ $booking->seat_number > 16 ? 'B' . ($booking->seat_number - 16) : str_pad($booking->seat_number, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="text-[10px] font-bold uppercase pb-1 {{ $booking->seat_number > 16 ? 'text-teal-600' : 'text-slate-500' }}">
                            @if($booking->seat_number > 16) {{ __('Penum. Berdiri') }} @elseif($booking->seat_number <= 4) {{ __('Prioritas') }} @else {{ __('Reguler') }} @endif
                        </span>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1.5">{{ __('Booking Ref') }}</p>
                    <div class="bg-white p-2 rounded-lg border border-slate-200">
                        {{-- Simulated Barcode effect --}}
                        <div class="flex h-8 w-24 opacity-80" aria-hidden="true" title="{{ $booking->booking_code }}">
                            <div class="w-1 bg-black h-full mr-1"></div><div class="w-0.5 bg-black h-full mr-0.5"></div><div class="w-2 bg-black h-full mr-1"></div><div class="w-1 bg-black h-full mr-0.5"></div><div class="w-0.5 bg-black h-full mr-1"></div><div class="w-1.5 bg-black h-full mr-0.5"></div><div class="w-3 bg-black h-full mr-1"></div><div class="w-0.5 bg-black h-full mr-0.5"></div><div class="w-1 bg-black h-full mr-1"></div><div class="w-2 bg-black h-full mr-0.5"></div><div class="w-1 bg-black h-full mr-1"></div><div class="w-1 bg-black h-full mr-0.5"></div><div class="w-0.5 bg-black h-full"></div>
                        </div>
                    </div>
                    <p class="font-mono text-[10px] font-black tracking-widest text-[#1e3a5f] mt-1">{{ $booking->booking_code }}</p>
                </div>
            </div>

            {{-- Payment & Notes Area --}}
            <div class="text-sm bg-[#fafbfc] rounded-2xl p-5 border border-slate-100">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Pembayaran') }}</span>
                    <div class="flex items-center gap-1.5 font-black text-[#1e3a5f] text-xs">
                        @if($booking->payment_method === 'qris')
                            <i class="fas fa-qrcode text-[#c41e3a]"></i> QRIS
                        @elseif($booking->payment_method === 'etoll')
                            <i class="fas fa-id-card text-[#1e3a5f]"></i> E-TOL
                        @else
                            —
                        @endif
                    </div>
                </div>
                @if($booking->payment_method === 'etoll' && $booking->etoll_number)
                <div class="flex justify-between items-center mb-0">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('No. Kartu') }}</span>
                    <span class="font-mono font-black text-slate-700 text-[11px]">{{ preg_replace('/(.{4})/', '$1 ', $booking->etoll_number) }}</span>
                </div>
                @endif
                
                @if($booking->notes)
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 block">{{ __('Catatan Penumpang') }}</span>
                    <p class="text-[11px] font-medium text-slate-600 italic bg-white p-3 rounded-xl border border-slate-100">{{ $booking->notes }}</p>
                </div>
                @endif
            </div>

            <footer class="flex justify-between items-center pt-2">
                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ __('Diterbitkan') }}</span>
                <span class="text-[10px] text-slate-500 font-bold uppercase">{{ $booking->created_at->translatedFormat('d M Y H:i') }}</span>
            </footer>
        </section>
        
        {{-- Interactive Section: Tip & Actions --}}
        <section class="bg-slate-50 p-8 border-t border-slate-100" aria-label="{{ __('Aksi dan Apresiasi') }}"
                 x-data="checkInManager({{ $booking->bus_id }})">
            <div class="flex flex-col gap-3 mb-6">
                @if(!$isCompleted && !$isCancelled && !$booking->is_boarded)
                <button type="button" @click="doCheckIn()" :disabled="checkingIn"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-3 group disabled:opacity-70 disabled:cursor-wait">
                    <span x-show="!checkingIn"><i class="fas fa-location-crosshairs group-hover:scale-125 transition-transform"></i> Validasi Check-In di Halte</span>
                    <span x-show="checkingIn" x-cloak><i class="fas fa-spinner fa-spin"></i> Mendapatkan Lokasi GPS...</span>
                </button>
                @endif
                
                @if($booking->is_boarded && !$isCompleted && !$isCancelled)
                <div class="w-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-black py-4 rounded-xl text-xs uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500 text-lg"></i> Check-in Terverifikasi
                </div>
                @endif

                @if($isPending || $isActive)
                <div class="w-full mt-2">

                    {{-- BUS STANDBY: Tombol batalkan aktif (reaktif Alpine — sinkron dengan simulasi realtime) --}}
                    <div x-show="!busIsMoving" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST"
                              onsubmit="return confirm('Batalkan pemesanan tiket ini secara permanen? Tindakan ini tidak dapat diurungkan.')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" aria-label="Batalkan Pemesanan"
                                    class="w-full bg-white hover:bg-rose-50 text-rose-600 font-black py-4 rounded-xl text-[10px] uppercase tracking-widest transition-all border border-rose-100 shadow-sm hover:shadow-md hover:border-rose-300 flex items-center justify-center gap-2 group">
                                <i class="fas fa-times group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                                Batalkan Pemesanan
                            </button>
                        </form>
                    </div>

                    {{-- BUS BERJALAN: Tombol diblokir + pesan reaktif (muncul otomatis ketika bus mulai jalan) --}}
                    <div x-show="busIsMoving" x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak
                         class="w-full rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden"
                         role="alert" aria-live="polite">
                        <button type="button" disabled aria-disabled="true"
                                aria-label="Pembatalan tidak tersedia — bus sedang berjalan"
                                class="w-full py-4 text-slate-500 font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 cursor-not-allowed">
                            <i class="fas fa-ban" aria-hidden="true"></i>
                            Pembatalan Tidak Tersedia
                        </button>
                        <div class="flex items-start gap-2.5 px-4 pb-4 pt-1">
                            <i class="fas fa-circle-info text-amber-500 text-xs mt-0.5 shrink-0" aria-hidden="true"></i>
                            <p class="text-[10px] text-slate-500 font-medium leading-snug">
                                Bus sedang dalam perjalanan
                                <strong class="text-amber-600" x-text="busStatusLabel"></strong>.
                                Pembatalan hanya dapat dilakukan ketika bus masih berstatus
                                <strong class="text-amber-600">Standby</strong> di terminal.
                            </p>
                        </div>
                    </div>

                </div>
                @endif
            </div>

            {{-- Smart Tipping System --}}
            @if($isCompleted)
            <div class="p-5 bg-gradient-to-br from-violet-50 to-white border border-violet-100 rounded-2xl flex items-center gap-4 shadow-sm" role="status">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm border border-violet-100" aria-hidden="true">
                    <i class="fas fa-flag-checkered text-violet-500 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-[11px] font-black text-violet-900 uppercase tracking-widest">{{ __('Perjalanan Selesai') }}</h3>
                    <p class="text-[10px] text-violet-600 font-medium mt-1 leading-snug">Perjalanan telah diselesaikan. Terima kasih telah mempercayakan perjalanan Anda pada Bus Kampus Non-Merdeka!</p>
                </div>
            </div>
            @elseif(in_array($booking->status, ['confirmed', 'pending']))
            <div x-data="tipManager({{ $booking->bus_id }})">
                <template x-if="busIsMoving">
                    <div x-transition class="space-y-4">
                        <template x-if="canTip">
                            <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-sm relative overflow-hidden">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 to-[#1e3a5f]" aria-hidden="true"></div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0 animate-pulse border border-emerald-100">
                                        <i class="fas fa-route text-emerald-500 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Apresiasi Sopir</h3>
                                        <p class="text-[10px] text-slate-500 font-medium mt-1 mb-4">Bus sedang dalam perjalanan. Anda dapat memberikan tip anonim secara digital untuk mengapresiasi kinerja sopir.</p>
                                        
                                        <button @click="openTip = !openTip" type="button" aria-expanded="openTip"
                                                class="w-full flex items-center justify-center gap-2 text-white font-black py-3 rounded-xl transition-all uppercase tracking-widest text-[9px] bg-gradient-to-r from-[#ffd700] to-[#f59e0b] shadow-md shadow-yellow-500/20 hover:scale-[1.02]">
                                            <i class="fas fa-gift text-sm"></i>
                                            <span>Beri Tip Sekarang</span>
                                        </button>
                                    </div>
                                </div>

                                <div x-show="openTip" x-collapse class="mt-4 pt-4 border-t border-slate-50">
                                    <p class="text-[9px] text-slate-500 mb-3 font-medium tracking-tight"><i class="fas fa-info-circle mr-1"></i> Tip anonim, diproses langsung, maksimal Rp. 5.000 per transaksi 1x seminggu.</p>
                                    <div class="flex gap-2">
                                        <div class="relative flex-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 text-xs font-black">Rp</span>
                                            <input type="number" x-model.number="amount" min="1000" max="5000" step="500" aria-label="Nominal Tip"
                                                   class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-black text-slate-800 focus:ring-2 focus:ring-[#ffd700] focus:border-[#ffd700] focus:bg-white outline-none transition-all">
                                        </div>
                                        <button type="button" @click="sendTip()" :disabled="sending" aria-label="Kirim Tip"
                                                class="bg-[#1e3a5f] hover:bg-slate-900 text-white font-black px-5 rounded-lg text-[9px] transition-all uppercase tracking-widest shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span x-show="!sending">{{ __('Kirim') }}</span>
                                            <span x-show="sending" x-cloak><i class="fas fa-spinner fa-spin"></i></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="!canTip">
                            <div class="flex items-center gap-4 p-4 bg-emerald-50 border border-emerald-100 rounded-xl" role="alert">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check-circle text-emerald-600 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Tip Telah Terkirim</h3>
                                    <p class="text-[9px] text-emerald-600 font-medium mt-0.5">Tip Anda sukses dikirimkan. Limit direset pada <strong x-text="resetDate"></strong>.</p>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!busIsMoving">
                    <div x-transition class="flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl opacity-60 cursor-not-allowed" aria-hidden="true">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lock text-slate-500 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Fitur Tip Terkunci</h3>
                            <p class="text-[9px] text-slate-500 font-medium mt-0.5">Dapat digunakan ketika bus dalam keadaan <strong class="text-slate-500">berjalan</strong> dengan Anda di dalamnya.</p>
                        </div>
                    </div>
                </template>
            </div>
            @endif
        </section>
    </article>

    {{-- Script remains same, just scoped inside --}}
    @if(in_array($booking->status, ['confirmed', 'pending']) && !$isCompleted)
    <script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            const TIP_ROUTE = '{{ route("user.tip.store", $booking->bus) }}';
            const TIP_STATUS_ROUTE = '{{ route("user.tip.status", $booking->bus) }}';
            const CSRF_TOKEN = '{{ csrf_token() }}';

            Alpine.data('tipManager', (busId) => ({
                openTip: false, busIsMoving: false, busId: busId, autoFinished: false,
                canTip: true, resetDate: '', amount: 5000, sending: false,
                prevBusDir: null, // rekam arah sebelumnya untuk deteksi edge transisi

                init() {
                    this.fetchTipStatus(); this.pollBusStatus();
                    setInterval(() => this.pollBusStatus(), 5000);
                },

                async fetchTipStatus() {
                    try {
                        const res = await fetch(TIP_STATUS_ROUTE);
                        const data = await res.json();
                        this.canTip = data.can_tip; this.resetDate = data.reset_date;
                    } catch(e) {}
                },

                async sendTip() {
                    if (this.sending || !this.canTip) return;
                    this.sending = true;
                    try {
                        const res = await fetch(TIP_ROUTE, {
                            method: 'POST',
                            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF_TOKEN, 'Accept':'application/json' },
                            body: JSON.stringify({ amount: this.amount })
                        });
                        const data = await res.json();
                        if (res.ok) {
                            this.canTip = false; this.openTip = false; this.fetchTipStatus();
                            Swal.fire({
                                icon: 'success', title: '🎁 Tip Terkirim!',
                                html: `Terima kasih! <strong>Rp ${Number(this.amount).toLocaleString('id-ID')}</strong> telah dikirimkan secara anonim ke sopir.`,
                                toast: true, position: 'top-end', showConfirmButton: false, timer: 5000, timerProgressBar: true
                            });
                        } else throw new Error(data.message || 'Gagal mengirim tip.');
                    } catch(e) {
                        Swal.fire({ icon: 'error', title: 'Oops', text: e.message });
                    } finally { this.sending = false; }
                },

                async pollBusStatus() {
                    if (this.autoFinished) return;
                    try {
                        const res = await fetch('/api/simulation/buses', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'same-origin'
                        });
                        const data = await res.json();
                        const myDbBus = data.buses.find(b => b.id === this.busId);
                        if (!myDbBus) return;
                        
                        BusSimulation.init(data.buses);
                        const positions = BusSimulation.getAllPositions();
                        const mySimBus = positions.find(b => b.id === this.busId);

                        const busJalan = mySimBus && mySimBus.trip_status === 'jalan';
                        const userOnBoard = true; 
                        this.busIsMoving = busJalan && userOnBoard;

                        if (userOnBoard && mySimBus) {
                            const curDir  = mySimBus.direction;
                            const prevDir = this.prevBusDir;

                            // Deteksi edge transisi nyata (bukan kondisi awal saat halaman dibuka)
                            const arrivedGowa  = prevDir === 'go'     && curDir === 'rest_gowa';
                            const arrivedTamal = prevDir === 'return' && curDir === 'rest_tamal';

                            if (arrivedGowa || arrivedTamal) {
                                this.triggerAutoFinish(curDir);
                            }

                            this.prevBusDir = curDir; // simpan untuk tick berikut
                        }
                    } catch(e) {}
                },

                async triggerAutoFinish(dir) {
                    if (this.autoFinished) return;
                    this.autoFinished = true;
                    try {
                        // Kirim ?dir= agar server tahu booking rute mana yang perlu diselesaikan
                        const response = await fetch(`/api/simulation/bus/${this.busId}/auto-finish?dir=${dir}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                        });
                        if (response.ok) window.location.reload();
                    } catch (e) { this.autoFinished = false; }
                }
            }));

            Alpine.data('checkInManager', (busId) => ({
                checkingIn: false,
                busIsMoving: false,     // reaktif: true saat simulasi deteksi bus berjalan
                busStatusLabel: '',     // label status untuk pesan di UI

                init() {
                    this.pollBusCancelStatus();
                    setInterval(() => this.pollBusCancelStatus(), 3000);
                },

                async pollBusCancelStatus() {
                    try {
                        const res  = await fetch('/api/simulation/buses', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'same-origin'
                        });
                        const data = await res.json();
                        const myDbBus = data.buses.find(b => b.id === busId);
                        if (!myDbBus) return;

                        BusSimulation.init(data.buses);
                        const positions = BusSimulation.getAllPositions();
                        const mySimBus  = positions.find(b => b.id === busId);

                        const simStatus = mySimBus ? mySimBus.trip_status : myDbBus.trip_status;
                        const dbStatus  = myDbBus.trip_status;

                        // Bus dianggap "tidak dapat dibatalkan" jika SALAH SATU dari simulasi
                        // atau DB menyatakan bukan standby
                        const isMoving = simStatus !== 'standby' || dbStatus !== 'standby';

                        this.busIsMoving = isMoving;
                        if (isMoving) {
                            const labels = { jalan: '(Sedang Berjalan)', istirahat: '(Sedang Istirahat)' };
                            this.busStatusLabel = labels[simStatus] || labels[dbStatus] || '(Tidak Standby)';
                        } else {
                            this.busStatusLabel = '';
                        }
                    } catch(e) {}
                },

                doCheckIn() {
                    if (!navigator.geolocation) {
                        Swal.fire('Oops!', 'Geolokasi tidak didukung oleh perangkat ini.', 'error');
                        return;
                    }

                    this.checkingIn = true;
                    navigator.geolocation.getCurrentPosition(
                        (position) => this.validatePosition(position.coords.latitude, position.coords.longitude),
                        (error) => {
                            this.checkingIn = false;
                            Swal.fire('Oops...', 'Akses lokasi ditolak atau gagal didapatkan.', 'error');
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                },

                async validatePosition(lat, lng) {
                    try {
                        const res = await fetch('{{ route('user.bookings.validate', $booking) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ lat, lng })
                        });

                        const data = await res.json();
                        if (res.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Disetujui',
                                text: data.message,
                                confirmButtonText: 'Lanjutkan',
                            }).then(() => window.location.reload());
                        } else {
                            throw new Error(data.message || 'Gagal memvalidasi jangkauan radius.');
                        }
                    } catch (error) {
                        Swal.fire('Validasi Gagal', error.message, 'warning');
                    } finally {
                        this.checkingIn = false;
                    }
                }
            }));
        });
    </script>
    @endif
</main>
@endsection