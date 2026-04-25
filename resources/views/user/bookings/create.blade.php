@extends('layouts.user')

@section('title', 'Pesan Tiket — ' . $bus->name)
@section('user-content')

@php
    $isCivitas = auth()->user()->isCivitas();
    $isUmum    = auth()->user()->isUmum();
    $harga     = $isCivitas ? 3000 : 5000; // Rp3.000 Sivitas | Rp5.000 Umum (subsidi silang)
    // Timer otomatis terkunci 10 detik murni pada awal halaman dirender khusus prioritas
    $lockRemaining = 10;
@endphp

<div class="max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('user.buses') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#c41e3a] uppercase tracking-widest transition-colors mb-4">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Bus
        </a>
        <h1 class="text-2xl font-black text-[#1e3a5f] tracking-tighter uppercase leading-none">Pesan Tiket Bus</h1>
        <p class="mt-1 text-sm text-slate-500 font-medium">{{ $bus->name }} — {{ $bus->route }}</p>
    </div>

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-100 text-rose-700 px-5 py-4 rounded-2xl text-sm mb-6 flex gap-3 items-start shadow-sm">
        <i class="fas fa-exclamation-circle text-rose-400 mt-0.5 text-lg flex-shrink-0"></i>
        <span class="font-semibold">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid lg:grid-cols-5 gap-8 items-start"
         x-data="{
            tahap: 1,
            paymentMethod: null,
            etollNumber: '',
            etollScanning: false,
            etollScanned: false,
            qrisScanning: false,
            qrCountdown: 0,
            qrTimer: null,
            rute: new URLSearchParams(window.location.search).get('from') === 'gowa' ? 'Kampus Gowa -> Kampus Perintis Kemerdekaan' : 'Kampus Perintis Kemerdekaan -> Kampus Gowa',
            selectedSeats: [],
            bookedSeats: {{ json_encode($bookedSeats) }},
            capacity: {{ $bus->capacity }},
            isCivitas: {{ $isCivitas ? 'true' : 'false' }},
            isUmum: {{ $isUmum ? 'true' : 'false' }},
            basePriceByRole: {{ $harga }},       // Rp3.000 Sivitas | Rp5.000 Umum
            pricePerSeat: {{ $harga }},           // reaktif — berubah saat priorityNeed 'high'
            maxSeats: {{ $isCivitas ? 4 : 1 }},  // default; dioverride jika high priority
            isPriority: false,
            priorityNeed: '',  // '' | 'medium' | 'high' | 'other'
            busId: {{ $bus->id }},
            busStatus: 'standby',
            busStatusLabel: 'Standby — Siap Menerima Penumpang',

            priorityLockSeconds: {{ $lockRemaining }},
            isPriorityLocked: {{ $lockRemaining > 0 ? 'true' : 'false' }},
            justUnlocked: false,
            priorityLockTimer: null,
            get formatLockTime() {
                if (!this.isPriorityLocked) return '00:00';
                const m = Math.floor(this.priorityLockSeconds / 60);
                const s = this.priorityLockSeconds % 60;
                return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            },

            get isBookingLocked() { return this.busStatus !== 'standby'; },

            // ─── Pricing Engine (reactive) ───────────────────────────────────
            // Prioritas Tinggi (Kursi Roda) → Rp 0 (GRATIS, subsidi Kampus Non-Merdeka)
            // Sivitas                        → Rp 3.000 / kursi
            // Umum/Tamu                      → Rp 5.000 / kursi (subsidi silang)
            get totalHarga() { return this.selectedSeats.length * this.pricePerSeat; },

            get canProceedToSeat() {
                return this.paymentMethod !== null &&
                    (this.paymentMethod === 'qris'
                        ? this.qrCountdown === 0 && !this.qrisScanning && this.tahap > 1
                        : this.etollScanned);
            },

            // Panggil ini setiap kali isPriority atau priorityNeed berubah
            recalcPricing() {
                if (this.isPriority && this.priorityNeed === 'high') {
                    this.pricePerSeat = 0;           // Fully subsidized
                    this.maxSeats     = 1;            // Fairness: hanya 1 kursi prioritas
                } else {
                    this.pricePerSeat = this.basePriceByRole;
                    this.maxSeats     = this.isUmum ? 1 : 4;
                }
                // Clear seat jika jumlah melebihi batas baru
                if (this.selectedSeats.length > this.maxSeats) {
                    this.selectedSeats = this.selectedSeats.slice(0, this.maxSeats);
                }
            },

            // Pilih metode pembayaran
            selectMethod(method) {
                if (this.isBookingLocked) return;
                // Blokir E-Tol untuk pengguna Umum (hanya kartu mahasiswa/staf)
                if (method === 'etoll' && this.isUmum) {
                    alert('⚠️ Metode E-Tol hanya tersedia untuk Sivitas Akademika Kampus Non-Merdeka (mahasiswa, dosen, staf). Silakan gunakan QRIS.');
                    return;
                }
                this.paymentMethod = method;
            },

            // Simulasi scan e-tol: generate 16 digit otomatis
            scanEtoll() {
                this.etollScanning = true;
                setTimeout(() => {
                    // Generate 16 digit nomor kartu acak
                    this.etollNumber = Array.from({length:16}, () => Math.floor(Math.random()*10)).join('');
                    this.etollScanning = false;
                    this.etollScanned = true;
                    setTimeout(() => { this.tahap = 2; }, 600);
                }, 2000);
            },

            // Simulasi scan QRIS: countdown 5 detik lalu lanjut
            scanQris() {
                this.qrisScanning = true;
                this.qrCountdown = 5;
                this.qrTimer = setInterval(() => {
                    this.qrCountdown--;
                    if (this.qrCountdown <= 0) {
                        clearInterval(this.qrTimer);
                        this.qrisScanning = false;
                        this.tahap = 2;
                    }
                }, 1000);
            },

            toggleSeat(seat) {
                if (this.isBookingLocked) return;
                if (this.bookedSeats.includes(seat)) return;

                const prioritySeats = [1, 2, 3, 4];
                const isPrioritySeat = prioritySeats.includes(seat);

                if (isPrioritySeat && !this.isPriority) {
                    if (this.isPriorityLocked) {
                        alert(`Kursi 1-4 dialokasikan khusus untuk penumpang berkebutuhan prioritas (lansia, ibu hamil, penyandang disabilitas). Kursi akan terbuka untuk umum dalam waktu ${this.formatLockTime} mendatang jika masih kosong.`);
                        return;
                    }
                }

                const idx = this.selectedSeats.indexOf(seat);
                if (idx > -1) {
                    this.selectedSeats.splice(idx, 1);
                } else {
                    if (this.priorityNeed === 'high' && isPrioritySeat && this.selectedSeats.length >= 1) {
                        alert('Pengguna Prioritas Tinggi (Kursi Roda) hanya dapat memilih 1 kursi prioritas per transaksi demi keadilan bagi penumpang lain.');
                        return;
                    }
                    if (this.selectedSeats.length < this.maxSeats) {
                        this.selectedSeats.push(seat);
                    } else {
                        alert('Maksimal ' + this.maxSeats + ' kursi untuk tipe pengguna Anda dalam satu transaksi.');
                    }
                }
            },

            // Fetch kursi terbaru sebelum submit
            async refreshSeats() {
                try {
                    const res = await fetch('/api/simulation/bus/' + this.busId + '/seats');
                    const data = await res.json();
                    this.bookedSeats = data.booked_seats;
                } catch(e) {}
            },

            // Polling realtime trip_status — dua sumber: DB + Simulasi Engine (sinkron dengan peta)
            async pollBusStatus() {
                try {
                    const res = await fetch('/api/simulation/buses');
                    const data = await res.json();
                    const thisBus = data.buses.find(b => b.id === this.busId);
                    if (!thisBus) return;

                    // Sumber 1: status dari DB (SATU-SATUNYA penentu booking lock)
                    const dbStatus = thisBus.trip_status ?? 'standby';

                    // Sumber 2: status simulasi — hanya untuk DISPLAY badge, bukan penentu lock
                    let displayStatus = dbStatus;
                    try {
                        if (typeof BusSimulation !== 'undefined') {
                            BusSimulation.init(data.buses);
                            const positions = BusSimulation.getAllPositions();
                            const simBus = positions.find(p => p.id === this.busId);
                            if (simBus && dbStatus !== 'standby') {
                                // Hanya pakai sim status jika DB memang bukan standby
                                displayStatus = simBus.trip_status ?? dbStatus;
                            }
                        }
                    } catch(simErr) {}

                    // KRITIS: Hanya DB status yang menentukan apakah booking LOCKED
                    // Simulasi waktu bisa salah kalkulasi di production → jangan pakai untuk lock
                    this.busStatus = dbStatus;
                    const labels = {
                        'standby'   : 'Standby — Siap Menerima Penumpang',
                        'jalan'     : 'Sedang Berjalan — Pemesanan Ditutup',
                        'istirahat' : 'Istirahat — Pemesanan Ditutup',
                    };
                    this.busStatusLabel = labels[dbStatus] ?? dbStatus;

                    // Jika sedang di Tahap 2 dan bus tiba-tiba locked, reset ke Tahap 1
                    if (this.isBookingLocked && this.tahap === 1) {
                        this.paymentMethod = null;
                        this.etollScanned  = false;
                    }
                } catch(e) {}
            },

            init() {
                this.pollBusStatus();
                
                // Start countdown untuk kursi prioritas
                if (this.priorityLockSeconds > 0) {
                    this.priorityLockTimer = setInterval(() => {
                        this.priorityLockSeconds--;
                        if (this.priorityLockSeconds <= 0) {
                            this.isPriorityLocked = false;
                            this.justUnlocked = true;
                            // Munculkan animasi transisi menjadi kursi UMUM selama 15 detik
                            setTimeout(() => { this.justUnlocked = false; }, 15000);
                            clearInterval(this.priorityLockTimer);
                        }
                    }, 1000);
                } else {
                    this.isPriorityLocked = false;
                }

                // Polling agresif 2 detik — sinkron dengan peta realtime
                setInterval(() => { this.refreshSeats(); this.pollBusStatus(); }, 2000);
            }
         }"
         >

        {{-- ============ SIDEBAR KIRI (col-2) ============ --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Info Bus --}}
            <div class="bg-gradient-to-br from-[#1e3a5f] to-[#0f2137] rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="absolute right-0 bottom-0 opacity-10 w-24 p-2">
                    <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto grayscale invert" alt="Logo">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black text-[#ffd700] uppercase tracking-widest">{{ $bus->bus_code ?? 'BUS-' . str_pad($bus->bus_number, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-[10px] font-mono text-white/40">{{ $bus->plate_number }}</span>
                    </div>
                    <h3 class="text-xl font-black tracking-tight leading-none mb-3">{{ $bus->name }}</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-3 bg-white/5 rounded-xl px-3 py-2 border border-white/5">
                            <i class="fas fa-map-marker-alt text-rose-400 w-4 text-center"></i>
                            <span class="font-semibold text-white/80 text-xs">{{ $bus->route }}</span>
                        </div>
                        <div class="flex items-center gap-3 bg-white/5 rounded-xl px-3 py-2 border border-white/5">
                            <i class="fas fa-user text-blue-400 w-4 text-center"></i>
                            <span class="font-semibold text-white/80 text-xs">{{ $bus->driver?->name ?? 'Tidak Ditugaskan' }}</span>
                        </div>
                        <div class="flex items-center gap-3 bg-white/5 rounded-xl px-3 py-2 border border-white/5">
                            <i class="fas fa-chair text-emerald-400 w-4 text-center"></i>
                            <span class="font-semibold text-white/80 text-xs">
                                Kapasitas: {{ $bus->capacity }} kursi
                                ({{ count($bookedSeats) }} terisi)
                            </span>
                        </div>
                    </div>

                    {{-- Status bus — REALTIME dari polling --}}
                    <div class="mt-4 flex items-center gap-2 rounded-xl px-3 py-2 border transition-all"
                         :class="{
                             'bg-yellow-500/20 border-yellow-400/30': busStatus === 'standby',
                             'bg-red-600/30 border-red-400/40': busStatus === 'jalan',
                             'bg-orange-500/20 border-orange-400/30': busStatus === 'istirahat',
                         }">
                        <span class="w-2 h-2 rounded-full animate-pulse"
                              :class="{
                                  'bg-yellow-400': busStatus === 'standby',
                                  'bg-red-400': busStatus === 'jalan',
                                  'bg-orange-400': busStatus === 'istirahat',
                              }"></span>
                        <span class="text-xs font-bold"
                              :class="{
                                  'text-yellow-300': busStatus === 'standby',
                                  'text-red-300': busStatus === 'jalan',
                                  'text-orange-300': busStatus === 'istirahat',
                              }"
                              x-text="busStatusLabel"></span>
                    </div>
                </div>
            </div>

            {{-- Tarif --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Tarif Perjalanan</h4>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <span class="text-sm font-semibold text-slate-600">{{ $isCivitas ? 'Sivitas Akademika' : 'Umum/Tamu' }}</span>
                    <span class="font-black text-[#1e3a5f]" x-text="pricePerSeat === 0 ? 'GRATIS' : 'Rp ' + pricePerSeat.toLocaleString('id-ID') + '/kursi'">Rp {{ number_format($harga, 0, ',', '.') }}/kursi</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-bold text-slate-700">Total (pilih kursi)</span>
                    <span class="font-black text-[#c41e3a] text-lg" x-text="totalHarga === 0 ? 'GRATIS' : 'Rp ' + totalHarga.toLocaleString('id-ID')">Rp 0</span>
                </div>
                <div class="mt-3 space-y-1">
                    <div class="text-[10px] text-slate-500 font-medium">
                        {{ $isUmum ? '⚠️ Maks. 1 kursi per transaksi' : '✅ Multi-kursi tersedia (maks. 4)' }}
                    </div>
                    <div x-show="isPriority && priorityNeed === 'high'" x-cloak
                         class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                        ♿ Kursi Roda: Biaya disubsidi penuh (Rp 0)
                    </div>
                    <div x-show="isUmum" class="text-[10px] text-orange-500 font-medium">
                        💳 Pembayaran: QRIS saja (E-Tol khusus Sivitas)
                    </div>
                </div>
            </div>

            {{-- Link ke peta --}}
            <a href="{{ route('map') }}" target="_blank"
               class="flex items-center gap-3 bg-[#0f2137] hover:bg-[#1e3a5f] border border-white/10 p-4 rounded-2xl text-white transition-all group">
                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-map-marked-alt text-blue-400"></i>
                </div>
                <div>
                    <div class="text-xs font-black">Lihat Posisi Bus di Peta</div>
                    <div class="text-[10px] text-white/40">Pantau real-time pergerakan armada</div>
                </div>
                <i class="fas fa-external-link-alt text-white/20 ml-auto"></i>
            </a>
        </div>

        {{-- ============ KONTEN UTAMA (col-3) ============ --}}
        <div class="lg:col-span-3 relative">

            {{-- OVERLAY REALTIME: Muncul ketika bus sudah bukan Standby --}}
            <div x-show="isBookingLocked" x-transition.duration.300ms
                 style="display:none"
                 class="absolute inset-0 z-50 flex flex-col items-center justify-center rounded-3xl bg-slate-900/90 backdrop-blur-sm text-white text-center p-8"
                 aria-live="assertive" role="alert">
                <div class="w-20 h-20 bg-red-500/20 border-2 border-red-400/40 rounded-3xl flex items-center justify-center mb-5 animate-pulse">
                    <i class="fas fa-bus-slash text-3xl text-red-400"></i>
                </div>
                <h2 class="text-xl font-black uppercase tracking-tighter mb-2">Pemesanan Ditutup</h2>
                <p class="text-slate-300 text-sm mb-1 font-medium" x-text="'Status Bus: ' + busStatusLabel"></p>
                <p class="text-slate-500 text-xs leading-relaxed max-w-xs">
                    Bus ini tidak lagi menerima pemesanan karena sudah meninggalkan terminal. Pantau peta realtime dan pesan saat bus kembali ke status <strong class="text-yellow-300">Standby</strong>.
                </p>
                <a href="{{ route('user.buses') }}"
                   class="mt-6 inline-flex items-center gap-2 bg-white text-slate-900 font-black text-xs uppercase tracking-widest py-3 px-6 rounded-xl hover:bg-slate-100 transition-all">
                    <i class="fas fa-arrow-left"></i> Pilih Bus Lain
                </a>
            </div>

            {{-- ===== TAHAP 1: PILIH METODE PEMBAYARAN ===== --}}
            <div x-show="tahap === 1" x-transition.duration.300ms>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xl p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl mx-auto flex items-center justify-center mb-4 border border-slate-100">
                            <i class="fas fa-credit-card text-2xl text-[#1e3a5f]"></i>
                        </div>
                        <h2 class="text-xl font-black text-[#1e3a5f] uppercase tracking-tighter">Pilih Metode Pembayaran</h2>
                        <p class="text-slate-500 text-sm mt-2 font-medium">Transaksi hanya saat bus dalam status <strong class="text-yellow-600">Standby</strong></p>
                    </div>

                    {{-- Pilihan Metode — diblokir ketika bus sudah tidak standby --}}
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        {{-- E-TOL --}}
                        <button type="button" @click="selectMethod('etoll')"
                                :disabled="isBookingLocked"
                                :class="[
                                    isBookingLocked ? 'opacity-40 cursor-not-allowed border-slate-100' : '',
                                    paymentMethod === 'etoll' ? 'border-[#1e3a5f] bg-[#1e3a5f]/5 shadow-lg' : 'border-slate-100 hover:border-slate-300'
                                ]"
                                class="p-5 border-2 rounded-2xl flex flex-col items-center gap-3 transition-all">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center"
                                 :class="paymentMethod === 'etoll' ? 'bg-[#1e3a5f] text-white' : 'bg-slate-50 text-slate-500'">
                                <i class="fas fa-id-card text-2xl"></i>
                            </div>
                            <div class="text-center">
                                <div class="font-black text-sm text-slate-800">Kartu E-Tol</div>
                                <div class="text-[10px] text-slate-500 mt-1">Tap kartu ke reader</div>
                            </div>
                            <div x-show="paymentMethod === 'etoll'" class="text-[10px] font-bold text-[#1e3a5f] bg-[#1e3a5f]/10 px-3 py-1 rounded-full">
                                ✓ Dipilih
                            </div>
                        </button>

                        {{-- QRIS --}}
                        <button type="button" @click="selectMethod('qris')"
                                :disabled="isBookingLocked"
                                :class="[
                                    isBookingLocked ? 'opacity-40 cursor-not-allowed border-slate-100' : '',
                                    paymentMethod === 'qris' ? 'border-[#c41e3a] bg-[#c41e3a]/5 shadow-lg' : 'border-slate-100 hover:border-slate-300'
                                ]"
                                class="p-5 border-2 rounded-2xl flex flex-col items-center gap-3 transition-all">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center"
                                 :class="paymentMethod === 'qris' ? 'bg-[#c41e3a] text-white' : 'bg-slate-50 text-slate-500'">
                                <i class="fas fa-qrcode text-2xl"></i>
                            </div>
                            <div class="text-center">
                                <div class="font-black text-sm text-slate-800">Scan QRIS</div>
                                <div class="text-[10px] text-slate-500 mt-1">Scan QR dengan dompet digital</div>
                            </div>
                            <div x-show="paymentMethod === 'qris'" class="text-[10px] font-bold text-[#c41e3a] bg-[#c41e3a]/10 px-3 py-1 rounded-full">
                                ✓ Dipilih
                            </div>
                        </button>
                    </div>

                    {{-- Panel E-TOL --}}
                    <div x-show="paymentMethod === 'etoll'" x-transition.duration.200ms>
                        <div class="bg-[#1e3a5f]/5 border border-[#1e3a5f]/20 rounded-2xl p-6 text-center">
                            <div x-show="!etollScanning && !etollScanned">
                                <div class="w-20 h-20 bg-[#1e3a5f]/10 rounded-2xl mx-auto flex items-center justify-center mb-4 border-2 border-dashed border-[#1e3a5f]/30">
                                    <i class="fas fa-id-card text-4xl text-[#1e3a5f]/50"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600 mb-4">Klik tombol di bawah untuk mensimulasikan tap kartu E-Tol ke reader bus</p>
                                <button type="button" @click="scanEtoll()"
                                        class="w-full bg-[#1e3a5f] text-white font-black py-4 rounded-xl text-sm uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#0f2137] transition-all">
                                    <i class="fas fa-wifi"></i> Tap / Scan Kartu E-Tol
                                </button>
                            </div>

                            {{-- Scanning Animation --}}
                            <div x-show="etollScanning" x-cloak>
                                <div class="relative w-24 h-24 mx-auto mb-4">
                                    <div class="w-24 h-24 border-4 border-[#1e3a5f]/20 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-id-card text-4xl text-[#1e3a5f]/30"></i>
                                    </div>
                                    <div class="absolute inset-0 border-4 border-[#1e3a5f] rounded-2xl animate-ping opacity-40"></div>
                                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[#1e3a5f] to-transparent animate-pulse"
                                         style="animation: scanline 1.5s infinite; top:50%;"></div>
                                </div>
                                <p class="text-sm font-bold text-[#1e3a5f] animate-pulse">Membaca kartu E-Tol...</p>
                            </div>

                            {{-- Scanned Success --}}
                            <div x-show="etollScanned" x-cloak>
                                <div class="w-16 h-16 bg-emerald-500 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/30">
                                    <i class="fas fa-check text-white text-2xl"></i>
                                </div>
                                <p class="text-sm font-black text-emerald-600 mb-2">Kartu Terdeteksi!</p>
                                <div class="bg-white border border-slate-100 rounded-xl px-4 py-2 inline-block">
                                    <span class="text-xs text-slate-500 mr-2">No. Kartu:</span>
                                    <span class="font-mono font-black text-[#1e3a5f]" x-text="etollNumber.replace(/(.{4})/g, '$1 ').trim()"></span>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">Menuju pemilihan kursi...</p>
                            </div>
                        </div>
                    </div>

                    {{-- Panel QRIS --}}
                    <div x-show="paymentMethod === 'qris'" x-transition.duration.200ms>
                        <div class="bg-[#c41e3a]/5 border border-[#c41e3a]/20 rounded-2xl p-6 text-center">
                            <div x-show="!qrisScanning && tahap === 1 && qrCountdown === 0">
                                <div class="bg-white p-4 rounded-2xl border-2 border-dashed border-[#c41e3a]/30 inline-block mb-4">
                                    <img src="{{ asset('images/external/qr-placeholder.svg') }}"
                                         alt="QRIS" class="w-36 h-36 mx-auto opacity-70">
                                </div>
                                <p class="text-sm text-slate-500 font-medium mb-1">Scan QR di atas menggunakan aplikasi dompet digital Anda</p>
                                <p class="text-xs text-slate-500 mb-4">Total: <strong class="text-[#c41e3a] font-black" x-text="'Rp ' + (harga).toLocaleString('id-ID')"></strong></p>
                                <button type="button" @click="scanQris()"
                                        class="bg-[#c41e3a] text-white font-black py-3 px-8 rounded-xl text-sm uppercase tracking-widest flex items-center gap-2 mx-auto hover:bg-[#a01830] transition-all">
                                    <i class="fas fa-qrcode"></i> Simulasi Scan QR
                                </button>
                            </div>

                            {{-- QR Scanning --}}
                            <div x-show="qrisScanning" x-cloak>
                                <div class="relative w-36 h-36 mx-auto mb-4">
                                    <img src="{{ asset('images/external/qr-placeholder.svg') }}"
                                         alt="QRIS" class="w-36 h-36 opacity-40">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-4xl font-black text-[#c41e3a]" x-text="qrCountdown"></div>
                                    </div>
                                    <div class="absolute inset-0 border-4 border-[#c41e3a] rounded-lg animate-pulse"></div>
                                </div>
                                <p class="text-sm font-bold text-[#c41e3a]">Memverifikasi pembayaran... (<span x-text="qrCountdown"></span>s)</p>
                                <p class="text-xs text-slate-500 mt-1">Jangan tutup halaman ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== TAHAP 2: PILIH KURSI ===== --}}
            <form action="{{ route('user.bookings.store') }}" method="POST" id="bookingForm">
                @csrf
                <input type="hidden" name="bus_id" value="{{ $bus->id }}">
                <input type="hidden" name="booking_date" value="{{ $date }}">
                <input type="hidden" name="payment_method" :value="paymentMethod">
                <input type="hidden" name="etoll_number" :value="etollNumber">
                <input type="hidden" name="rute" :value="rute">
                <input type="hidden" name="selected_seats" :value="selectedSeats.join(',')">
                <input type="hidden" name="notes" id="notesHidden">
                <input type="hidden" name="is_priority" :value="isPriority ? 1 : 0">
                <input type="hidden" name="priority_need" :value="priorityNeed">

                <div x-show="tahap === 2" x-transition.duration.300ms x-cloak class="space-y-5">

                    {{-- BANNER PERINGATAN REALTIME — muncul jika status berubah saat di Tahap 2 --}}
                    <div x-show="isBookingLocked" x-transition
                         class="bg-red-50 border-2 border-red-200 rounded-2xl p-5 flex items-start gap-4"
                         role="alert" aria-live="assertive">
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                        </div>
                        <div>
                            <p class="font-black text-red-700 text-sm uppercase tracking-wide">Bus Sudah Berangkat!</p>
                            <p class="text-red-600 text-xs mt-1" x-text="'Status saat ini: ' + busStatusLabel"></p>
                            <p class="text-red-500 text-xs mt-1">Pemesanan tidak dapat dilanjutkan. Silakan kembali dan pilih bus yang masih Standby.</p>
                        </div>
                    </div>
                    {{-- Header tahap 2 --}}
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-black text-[#1e3a5f] uppercase tracking-tighter">Pilih Kursi</h2>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500">Metode:</span>
                                <span class="text-xs font-black px-3 py-1 rounded-full"
                                      :class="paymentMethod === 'etoll' ? 'bg-[#1e3a5f]/10 text-[#1e3a5f]' : 'bg-[#c41e3a]/10 text-[#c41e3a]'"
                                      x-text="paymentMethod === 'etoll' ? '💳 E-Tol' : '📱 QRIS'"></span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-slate-500 font-medium">
                                Pilih <strong class="text-[#c41e3a]" x-text="maxSeats"></strong> kursi untuk perjalanan Anda
                                <span class="ml-2 text-emerald-600 font-bold" id="seat-live-badge">● Kursi live</span>
                            </p>
                            <span class="text-xs bg-[#c41e3a] text-white font-black px-3 py-1 rounded-full" x-text="'Sisa pilih: ' + (maxSeats - selectedSeats.length)"></span>
                        </div>
                    </div>

                    {{-- Priority Lock Timer Badge --}}
                    <div x-show="isPriorityLocked" x-cloak x-transition class="mb-5 bg-orange-50 border border-orange-200 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between shadow-sm gap-3">
                        <div class="flex items-start sm:items-center gap-3">
                            <i class="fas fa-lock text-orange-500 mt-0.5 sm:mt-0 text-lg"></i>
                            <div>
                                <span class="text-xs font-black text-orange-800 uppercase tracking-wide block">Kursi 1-4 Terkunci untuk Prioritas</span>
                                <span class="text-[10px] text-orange-600/80 font-medium leading-tight">Terbuka untuk umum dalam waktu yang tertera.</span>
                            </div>
                        </div>
                        <div class="bg-orange-100 text-orange-800 font-mono font-black text-sm px-3 py-1.5 rounded-lg flex items-center justify-center gap-2 border border-orange-200 shadow-inner">
                            <i class="fas fa-clock text-[11px]"></i> <span x-text="formatLockTime"></span>
                        </div>
                    </div>

                    {{-- WCAG Priority Toggle --}}
                    <div class="mb-5 p-4 border border-blue-100 bg-blue-50/50 rounded-xl">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_priority_cb" x-model="isPriority"
                                   @change="
                                       if (!isPriority) { priorityNeed = ''; }
                                       recalcPricing();
                                   "
                                   class="w-5 h-5 mt-0.5 accent-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 shrink-0"
                                   aria-controls="priority_options" :aria-expanded="isPriority.toString()">
                            <div>
                                <span class="text-sm font-bold text-slate-700 block mb-0.5">Ajukan Fasilitas Kebutuhan Prioritas / Inklusif</span>
                                <span class="text-[11px] text-slate-500">Untuk lansia, ibu hamil, dan penyandang disabilitas</span>
                            </div>
                        </label>

                        <div id="priority_options" x-show="isPriority" x-transition x-cloak class="mt-4 pt-4 border-t border-blue-100 space-y-3">
                            <label for="priority_need_sel" class="block text-xs font-bold text-slate-700 mb-1.5">
                                <i class="fas fa-hand-holding-heart text-blue-400 mr-1" aria-hidden="true"></i>
                                Tingkat Kebutuhan Prioritas:
                            </label>
                            <select x-model="priorityNeed"
                                    id="priority_need_sel"
                                    @change="recalcPricing(); selectedSeats = [];"
                                    class="w-full p-3 rounded-xl border-2 border-blue-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 text-sm font-semibold outline-none transition"
                                    :aria-required="isPriority">
                                <option value="">-- Pilih Kategori Kebutuhan --</option>
                                <option value="medium">🤰  Ringan/Sedang — Lansia, Ibu Hamil (Kursi depan, tarif normal)</option>
                                <option value="high">♿  Tinggi — Pengguna Kursi Roda (Gratis, subsidi Kampus Non-Merdeka)</option>
                                <option value="other">🏥  Kondisi Medis Khusus Lainnya (Kursi depan, tarif normal)</option>
                            </select>

                            <!-- Info subsidi gratis untuk high -->
                            <div x-show="priorityNeed === 'high'" x-cloak x-transition.opacity
                                 class="flex items-start gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl"
                                 aria-live="polite">
                                <i class="fas fa-tag text-emerald-500 text-sm mt-0.5 shrink-0" aria-hidden="true"></i>
                                <p class="text-xs text-emerald-700 font-semibold leading-snug">
                                    <strong>Biaya disubsidi penuh oleh Kampus Non-Merdeka.</strong>
                                    Pengguna kursi roda tidak dikenakan tarif (Rp&nbsp;0). Hanya 1 kursi prioritas per transaksi.
                                </p>
                            </div>

                            <!-- Info medium/other -->
                            <div x-show="priorityNeed === 'medium' || priorityNeed === 'other'" x-cloak x-transition.opacity
                                 class="flex items-start gap-2 p-3 bg-blue-50 border border-blue-200 rounded-xl"
                                 aria-live="polite">
                                <i class="fas fa-info-circle text-blue-500 text-sm mt-0.5 shrink-0" aria-hidden="true"></i>
                                <p class="text-xs text-blue-700 font-medium leading-snug">
                                    Tarif perjalanan normal berlaku. Kursi prioritas zona depan (1–4) akan terbuka untuk Anda.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Denah Bus --}}
                        {{-- Bus Chassis Visualization --}}
                        <div class="max-w-md mx-auto bg-white border-[12px] border-slate-100 shadow-2xl rounded-[5rem] p-10 sm:p-12 relative overflow-hidden">
                            <div class="absolute inset-0 bg-slate-50/50 -z-10"></div>

                            {{-- Cockpit/Front Area --}}
                            <div class="flex items-center justify-between mb-10 border-b-4 border-slate-100 pb-8 px-2 sm:px-4">
                                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-slate-900 rounded-3xl flex items-center justify-center border-4 border-white shadow-lg transform -rotate-12">
                                    <i class="fas fa-dharmachakra text-white text-2xl" style="animation: spin 8s linear infinite;"></i>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1">Entrance Area</span>
                                    <div class="w-20 sm:w-24 h-4 bg-[#ffd700] rounded-full shadow-inner opacity-50"></div>
                                </div>
                            </div>

                            {{-- Seat Matrix --}}
                            <div class="relative">
                                {{-- Aisle visualization --}}
                                <div class="absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-10 sm:w-12 bg-slate-200/40 rounded-full -z-10 shadow-inner"></div>

                                <div class="grid grid-cols-4 gap-3 sm:gap-4 gap-y-6 sm:gap-y-8 relative">
                                    @for($i = 1; $i <= 16; $i++)
                                        @php $isBooked = in_array($i, $bookedSeats); $isPriority = $i <= 4; @endphp
                                        <div class="{{ $i % 4 == 2 ? 'mr-6 sm:mr-8' : '' }} flex justify-center">
                                            <button type="button"
                                                    @click="toggleSeat({{ $i }})"
                                                    :disabled="bookedSeats.includes({{ $i }})"
                                                    :aria-label="'Kursi {{ $i }}' + (bookedSeats.includes({{ $i }}) ? ' terisi' : ' kosong') + ('{{ $isPriority ? ' inklusif prioritas' : '' }}')"
                                                    :class="{
                                                        'bg-slate-100 text-slate-300 cursor-not-allowed border-transparent opacity-60': bookedSeats.includes({{ $i }}),
                                                        'bg-emerald-50 text-emerald-500 hover:border-emerald-400 hover:bg-emerald-100 border-2 border-emerald-200 shadow-sm group': !bookedSeats.includes({{ $i }}) && (!{{ $isPriority ? 'true' : 'false' }} || (!isPriorityLocked && !isPriority)) && !selectedSeats.includes({{ $i }}),
                                                        'bg-[#1e3a5f]/5 text-[#1e3a5f] hover:border-[#1e3a5f] hover:bg-[#1e3a5f]/10 border-2 border-[#1e3a5f]/30 shadow-sm group': !bookedSeats.includes({{ $i }}) && {{ $isPriority ? 'true' : 'false' }} && (isPriorityLocked || isPriority) && !selectedSeats.includes({{ $i }}),
                                                        'bg-[#c41e3a] text-white border-4 border-white shadow-2xl scale-110 z-10 ring-4 ring-[#c41e3a]/20': selectedSeats.includes({{ $i }})
                                                    }"
                                                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 relative overflow-hidden group">
                                                <i class="fas fa-couch text-xs sm:text-sm mb-1 group-hover:scale-110 transition-transform"></i>
                                                <span class="font-black text-[9px] sm:text-[10px] tracking-tighter">{{ $i }}</span>
                                                
                                                @if($isPriority)
                                                    {{-- Ikon Kursi Roda Reguler (Muncul selama masih terkunci ATAU jika user sudah mengaktifkan toggle inklusif) --}}
                                                    <i x-show="(isPriorityLocked || isPriority) && !justUnlocked" class="fas fa-wheelchair text-[6px] absolute top-1.5 right-1.5 opacity-60 transition-opacity" aria-hidden="true"></i>

                                                    {{-- Ikon Umum (Muncul 15 detik setelah unlock) --}}
                                                    <i x-show="!isPriorityLocked && !isPriority && justUnlocked && !bookedSeats.includes({{ $i }})" x-transition.opacity.duration.500ms class="fas fa-users text-[8px] absolute top-1.5 right-1.5 text-emerald-600 animate-bounce" aria-hidden="true"></i>

                                                    {{-- Overlay Kunci Animatif yang berisi Timer Detik --}}
                                                    <div x-show="!bookedSeats.includes({{ $i }}) && isPriorityLocked && !isPriority" x-transition.opacity.duration.300ms 
                                                         class="absolute inset-0 bg-slate-900/90 backdrop-blur-[2px] flex flex-col items-center justify-center rounded-2xl z-20 cursor-not-allowed border border-slate-700">
                                                        <i class="fas fa-wheelchair text-[#ffd700] text-[10px] sm:text-xs mb-1 animate-pulse" aria-hidden="true"></i>
                                                        <div class="flex items-center gap-1 font-mono text-[8px] sm:text-[9px] font-black text-rose-200 bg-rose-500/20 px-1 py-0.5 rounded border border-rose-500/30">
                                                            <i class="fas fa-lock text-[7px] text-rose-400"></i>
                                                            <span x-text="priorityLockSeconds + 's'"></span>
                                                        </div>
                                                    </div>

                                                    {{-- Overlay Status Berubah Untuk Umum (Animasi 15 detik) --}}
                                                    <div x-show="!bookedSeats.includes({{ $i }}) && justUnlocked && !isPriority" x-transition.opacity.duration.500ms
                                                         class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl z-20 bg-emerald-500/10 border-2 border-emerald-400 animate-pulse pointer-events-none">
                                                         <div class="bg-emerald-50/90 backdrop-blur-sm rounded-full p-1 shadow-sm mb-0.5">
                                                            <i class="fas fa-users text-emerald-600 text-[10px]"></i>
                                                         </div>
                                                         <span class="text-[5px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-100/80 px-1 rounded-sm">Umum</span>
                                                    </div>
                                                @endif

                                                {{-- Indicator for selected --}}
                                                <div x-show="selectedSeats.includes({{ $i }})"
                                                     class="absolute top-0 right-0 w-4 h-4 bg-[#ffd700] rounded-bl-xl shadow-sm"></div>
                                            </button>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            {{-- Standing Area / Rear Section --}}
                            <div class="mt-12 sm:mt-16 border-t-4 border-dashed border-slate-200 pt-8 relative">
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-slate-50 text-[9px] font-black text-slate-500 uppercase tracking-[0.5em] whitespace-nowrap">
                                    {{ __('Area Berdiri') }}
                                </div>
                                <div class="flex justify-around items-center px-4">
                                    @for($i = 17; $i <= 20; $i++)
                                        <button type="button"
                                                @click="toggleSeat({{ $i }})"
                                                :disabled="bookedSeats.includes({{ $i }})"
                                                :aria-label="'Area Berdiri {{ $i }}' + (bookedSeats.includes({{ $i }}) ? ' terisi' : ' kosong')"
                                                :class="{
                                                    'bg-slate-100 text-slate-300 border-transparent opacity-60': bookedSeats.includes({{ $i }}),
                                                    'bg-teal-50 text-teal-500 border-2 border-dashed border-teal-300 hover:bg-teal-100': !bookedSeats.includes({{ $i }}) && !selectedSeats.includes({{ $i }}),
                                                    'bg-[#c41e3a] text-white border-2 border-white scale-110 shadow-lg ring-2 ring-[#c41e3a]/20': selectedSeats.includes({{ $i }})
                                                }"
                                                class="w-10 h-10 sm:w-11 sm:h-11 rounded-full flex flex-col items-center justify-center transition-all relative overflow-hidden group">
                                            <i class="fas fa-walking text-xs mb-0.5 group-hover:animate-bounce"></i>
                                            <span class="font-black text-[8px]">{{ $i }}</span>
                                        </button>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- Legend Kursi --}}
                        <div class="mt-5 flex flex-wrap gap-3 justify-center">
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <div class="w-5 h-5 bg-emerald-500 rounded-md"></div> Reguler
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <div class="w-5 h-5 bg-[#1e3a5f] rounded-md"></div> Inklusif / Prioritas
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <div class="w-5 h-5 bg-slate-200 rounded-md"></div> Terisi
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <div class="w-5 h-5 bg-[#ffd700] border-2 border-[#c41e3a] rounded-md"></div> Pilihan
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan & Submit --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl p-6 relative">
                        {{-- Overlay jika belum pilih kursi sesuai --}}
                        <div x-show="selectedSeats.length === 0"
                             class="absolute inset-0 bg-white/80 backdrop-blur-[2px] z-20 flex items-center justify-center rounded-2xl" aria-hidden="true">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mb-3 mx-auto">
                                    <i class="fas fa-hand-pointer text-xl text-[#c41e3a]"></i>
                                </div>
                                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Pilih kursi terlebih dahulu</p>
                            </div>
                        </div>

                        <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest mb-4">Ringkasan Pemesanan</h3>
                        <div class="space-y-2 mb-5">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Bus:</span>
                                <span class="font-bold text-slate-800">{{ $bus->name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Tanggal:</span>
                                <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm" aria-live="polite">
                                <span class="text-slate-500">Kursi:</span>
                                <span class="font-bold text-[#c41e3a]" x-text="selectedSeats.length ? 'No. ' + selectedSeats.join(', ') : '—'"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Pembayaran:</span>
                                <span class="font-bold text-slate-800" x-text="paymentMethod === 'etoll' ? '💳 Kartu E-Tol' : '📱 QRIS'"></span>
                            </div>
                            <div class="flex justify-between text-sm pt-2 border-t border-slate-50">
                                <span class="font-black text-slate-700">Total:</span>
                                <span class="font-black text-[#1e3a5f] text-lg" x-text="'Rp ' + totalHarga.toLocaleString('id-ID')"></span>
                            </div>
                        </div>

                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-widest mb-3">Arah Perjalanan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-5">
                            <button type="button" @click="rute = 'Kampus Perintis Kemerdekaan -> Kampus Gowa'"
                                    :aria-pressed="rute === 'Kampus Perintis Kemerdekaan -> Kampus Gowa'"
                                    :class="rute === 'Kampus Perintis Kemerdekaan -> Kampus Gowa' ? 'border-[#1e3a5f] bg-[#1e3a5f]/5 text-[#1e3a5f] shadow-sm' : 'border-slate-100 text-slate-500 hover:border-slate-300'"
                                    class="border-2 rounded-xl py-3 px-2 text-[10px] font-bold transition-all text-center">
                                Kampus Non-Merdeka Perintis Kemerdekaan <br><i class="fas fa-arrow-down my-1 text-[8px]" aria-hidden="true"></i><br> Kampus Non-Merdeka Gowa
                            </button>
                            <button type="button" @click="rute = 'Kampus Gowa -> Kampus Perintis Kemerdekaan'"
                                    :aria-pressed="rute === 'Kampus Gowa -> Kampus Perintis Kemerdekaan'"
                                    :class="rute === 'Kampus Gowa -> Kampus Perintis Kemerdekaan' ? 'border-[#c41e3a] bg-[#c41e3a]/5 text-[#c41e3a] shadow-sm' : 'border-slate-100 text-slate-500 hover:border-slate-300'"
                                    class="border-2 rounded-xl py-3 px-2 text-[10px] font-bold transition-all text-center">
                                Kampus Non-Merdeka Gowa <br><i class="fas fa-arrow-down my-1 text-[8px]" aria-hidden="true"></i><br> Kampus Non-Merdeka Perintis Kemerdekaan
                            </button>
                        </div>


                        <textarea onkeyup="document.getElementById('notesHidden').value = this.value"
                                  rows="2"
                                  aria-label="Catatan tambahan"
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm text-[#1e3a5f] outline-none focus:ring-2 focus:ring-[#c41e3a]/20 focus:border-[#c41e3a] transition-all resize-none mb-4"
                                  placeholder="Catatan (opsional, misal: titik jemput...)"></textarea>

                        <button type="submit"
                                :disabled="selectedSeats.length === 0 || (isPriority && priorityNeed === '') || isBookingLocked"
                                class="w-full bg-gradient-to-r from-[#c41e3a] to-[#a1182e] text-white font-black py-4 rounded-xl transition-all flex items-center justify-center gap-3 uppercase tracking-widest text-xs disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-xl hover:shadow-[#c41e3a]/30 transform hover:-translate-y-0.5">
                            <i class="fas fa-ticket-alt text-base"></i>
                            <span x-show="!isBookingLocked">Konfirmasi &amp; Selesaikan Pemesanan</span>
                            <span x-show="isBookingLocked" x-cloak><i class="fas fa-ban mr-1"></i>Bus Sudah Berangkat</span>
                        </button>

                        <button type="button" @click="tahap = 1; selectedSeats = [];"
                                class="w-full mt-2 text-xs text-slate-500 hover:text-[#c41e3a] font-semibold py-2 transition-colors">
                            ← Kembali (Ganti Metode Pembayaran)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes scanline {
    0%   { top: 0; opacity: 1; }
    50%  { opacity: 0.5; }
    100% { top: 100%; opacity: 1; }
}
#seat-live-badge { animation: liveblink 2s infinite; }
@keyframes liveblink { 0%,100%{ opacity:1; } 50%{ opacity:0.4; } }
</style>

{{-- Load mesin simulasi yang sama dengan peta realtime --}}
<script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>

<script>
/**
 * Guard submit form — cek sekali lagi status bus via simulasi engine
 * TIDAK dapat di-bypass karena berjalan sinkron sebelum submit
 */
document.getElementById('bookingForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formEl = this;

    try {
        const res  = await fetch('/api/simulation/buses');
        const data = await res.json();
        const busId = {{ $bus->id }};
        const dbBus = data.buses.find(b => b.id === busId);

        // Cek DB status
        if (dbBus && dbBus.trip_status !== 'standby') {
            alert('⚠️ Pemesanan Dibatalkan\n\nBus ini sudah dalam status "' + (dbBus.trip_status_label ?? dbBus.trip_status) + '".\nSilakan kembali dan pilih bus yang masih Standby.');
            return;
        }

        // Cek status via mesin simulasi (sinkron dengan peta)
        if (typeof BusSimulation !== 'undefined' && dbBus) {
            BusSimulation.init(data.buses);
            const positions = BusSimulation.getAllPositions();
            const simBus = positions.find(p => p.id === busId);
            if (simBus && simBus.trip_status !== 'standby') {
                alert('⚠️ Pemesanan Dibatalkan\n\nStatus real-time bus di peta menunjukkan bus sudah "' + simBus.trip_status + '".\nPemesanan hanya bisa dilakukan saat bus berstatus Standby di terminal.');
                return;
            }
        }

        // Lolos semua pengecekan — lanjutkan submit
        formEl.submit();

    } catch(err) {
        // Jika gagal fetch API, tetap izinkan submit (server-side guard akan menangkap)
        console.warn('Bus status check failed, proceeding with server-side validation.', err);
        formEl.submit();
    }
});
</script>

@endsection