@extends('layouts.app')

@section('title', 'Pilih Kursi - Tamu')
@section('content')

<div class="px-4 py-32 sm:px-0 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-12">
            <a href="{{ route('guest.buses') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-orange-500 uppercase tracking-widest transition-colors mb-4">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Bus
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900">Pemesanan Tamu</h1>
            <p class="mt-2 text-sm text-gray-500 font-medium">Lengkapi data diri Anda dan pilih kursi untuk perjalanan dengan <b>{{ $bus->name }}</b>.</p>
        </div>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl text-sm mb-8 flex gap-3 items-center shadow-sm">
            <i class="fas fa-exclamation-circle text-lg"></i>
            {{ session('error') }}
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-10">

            {{-- Kiri: Form & Info --}}
            <div class="lg:col-span-1 space-y-8">

                {{-- Info Bus --}}
                <div class="bg-gradient-to-br from-[#1e3a5f] to-[#162d4a] rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10 w-32 p-4">
                        <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-full h-auto grayscale invert" alt="Logo">
                    </div>
                    <div class="relative z-10">
                        <h3 class="font-bold text-2xl mb-1">{{ $bus->name }}</h3>
                        <p class="text-xs text-orange-200 uppercase font-black tracking-widest mb-6">{{ $bus->plate_number }}</p>
                        <div class="space-y-4 bg-white/5 rounded-2xl p-4 border border-white/10 backdrop-blur-md">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-yellow-400">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <span class="text-sm font-bold">{{ $bus->route }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-green-400">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <span class="text-sm font-bold">{{ $bus->departure_time }} - {{ $bus->arrival_time }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Info Tamu --}}
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-circle text-orange-500"></i> Data Penumpang
                    </h4>
                    <form id="guestForm" action="{{ route('guest.booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bus_id" value="{{ $bus->id }}">
                        <input type="hidden" name="booking_date" value="{{ $date }}">
                        <input type="hidden" name="selected_seats" id="selectedSeatsInput">
                        <input type="hidden" name="rute" id="ruteInput" value="Kampus Perintis Kemerdekaan -> Kampus Gowa">
                        <input type="hidden" name="notes" id="notesInput">

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                <input type="text" name="guest_name" required
                                       placeholder="Masukkan nama Anda..."
                                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nomor WhatsApp</label>
                                <input type="tel" name="guest_phone" required
                                       placeholder="Contoh: 08123456789"
                                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kanan: QRIS & Seat Map --}}
            <div class="lg:col-span-2 relative"
                 x-data="{
                    tahap: 1,
                    showQris: false,
                    rute: new URLSearchParams(window.location.search).get('from') === 'gowa' ? 'Kampus Gowa -> Kampus Perintis Kemerdekaan' : 'Kampus Perintis Kemerdekaan -> Kampus Gowa',
                    selectedSeats: [],
                    bookedSeats: {{ json_encode($bookedSeats) }},
                    
                    busId: {{ $bus->id }},
                    busStatus: 'standby',
                    busStatusLabel: 'Standby - Siap',
                    get isBookingLocked() { return this.busStatus !== 'standby'; },

                    bukaQris() { 
                        if (this.isBookingLocked) return;
                        this.showQris = true; 
                    },
                    konfirmasiBayar() { 
                        if (this.isBookingLocked) return;
                        this.showQris = false; this.tahap = 2; 
                    },
                    toggleSeat(seat) {
                        if (this.isBookingLocked) return;
                        if (this.bookedSeats.includes(seat)) return;
                        if (this.selectedSeats.includes(seat)) {
                            this.selectedSeats = [];
                        } else {
                            this.selectedSeats = [seat]; // Only 1 for guest
                        }
                        document.getElementById('selectedSeatsInput').value = this.selectedSeats.join(',');
                    },

                    async pollBusStatus() {
                        try {
                            const res = await fetch('/api/simulation/buses');
                            const data = await res.json();
                            const dbBus = data.buses.find(b => b.id === this.busId);
                            if (!dbBus) return;
                            
                            let simStatus = dbBus.trip_status ?? 'standby';
                            if (typeof BusSimulation !== 'undefined') {
                                BusSimulation.init(data.buses);
                                const positions = BusSimulation.getAllPositions();
                                const simBus = positions.find(p => p.id === this.busId);
                                if (simBus && simBus.trip_status !== 'standby') simStatus = simBus.trip_status;
                            }
                            
                            const finalStatus = (dbBus.trip_status !== 'standby') ? dbBus.trip_status : simStatus;
                            
                            this.busStatus = finalStatus;
                            const labels = {
                                'standby': 'Standby',
                                'jalan': 'Sedang Beroperasi',
                                'istirahat': 'Sedang Istirahat'
                            };
                            this.busStatusLabel = labels[finalStatus] ?? finalStatus;
                            
                            if (this.isBookingLocked && this.showQris) {
                                this.showQris = false;
                            }
                        } catch(e) {}
                    }
                 }"
                 x-init="pollBusStatus(); setInterval(() => pollBusStatus(), 2000);">
                 
                {{-- OVERLAY REALTIME --}}
                <div x-show="isBookingLocked" x-transition.duration.300ms x-cloak
                     class="absolute inset-0 z-50 flex flex-col items-center justify-center rounded-[3rem] bg-slate-900/95 backdrop-blur-md text-white text-center p-8 border border-white/10"
                     aria-live="assertive" role="alert">
                    <div class="w-20 h-20 bg-red-500/20 border-2 border-red-400/40 rounded-3xl flex items-center justify-center mb-6 animate-pulse">
                        <i class="fas fa-bus-slash text-4xl text-red-400"></i>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-black uppercase tracking-tighter mb-3 leading-none text-red-400">Bus Telah <br>Berangkat!</h2>
                    <p class="text-slate-300 text-sm mb-4 font-bold tracking-widest uppercase" x-text="'STATUS TERKINI: ' + busStatusLabel"></p>
                    <p class="text-slate-400 font-medium leading-relaxed max-w-sm mt-2 text-sm">
                        Transaksi dihentikan otomatis karena armada terpilih sudah melaju dari terminal. Sistem menyarankan Anda untuk mengalihkan reservasi ke bus lain yang berstatus <strong class="text-emerald-400">Standby</strong>.
                    </p>
                    <a href="{{ route('guest.buses') }}"
                       class="mt-10 inline-flex items-center gap-3 bg-white text-slate-900 font-black text-xs uppercase tracking-[0.2em] py-4 px-8 rounded-2xl hover:bg-slate-100 hover:scale-105 transition-all shadow-xl shadow-white/10">
                        <i class="fas fa-arrow-right"></i> Alihkan ke Bus Lain
                    </a>
                </div>

                {{-- TAHAP 1: INFO HARGA & QRIS --}}
                <div x-show="tahap === 1" x-transition.duration.500ms>
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-12 text-center relative overflow-hidden">
                        <div class="absolute -top-10 -left-10 w-32 h-32 bg-orange-50 rounded-full opacity-50 blur-3xl"></div>

                        <div class="w-24 h-24 bg-orange-50 rounded-3xl mx-auto flex items-center justify-center mb-6 transform rotate-6 border border-orange-100">
                            <i class="fas fa-receipt text-4xl text-orange-500 transform -rotate-6"></i>
                        </div>

                        <h2 class="text-3xl font-black text-gray-900 mb-3 leading-tight">Ringkasan Pembayaran</h2>
                        <p class="text-gray-500 mb-10 max-w-sm mx-auto font-medium">Segera selesaikan transaksi untuk membuka akses pemilihan kursi privat Anda.</p>

                        <div class="bg-gray-50 p-8 rounded-3xl border border-gray-200 mb-10 max-w-sm mx-auto flex justify-between items-center text-left">
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Status Tamu</p>
                                <p class="text-3xl font-black text-gray-900">Rp 6.000</p>
                                <p class="text-xs text-gray-400 mt-1 font-bold">Terhitung per 1 Tiket</p>
                            </div>
                            <div class="bg-[#1e3a5f] text-white p-5 rounded-2xl shadow-lg">
                                <i class="fas fa-qrcode text-3xl"></i>
                            </div>
                        </div>

                        <button @click="bukaQris()" type="button"
                                class="w-full max-w-sm mx-auto bg-gray-900 hover:bg-black text-white font-bold py-5 px-8 rounded-2xl shadow-xl hover:shadow-gray-400/30 transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1">
                            <i class="fas fa-shield-alt"></i>
                            Proses Pembayaran Digital
                        </button>

                        <p class="mt-6 text-xs text-gray-400 font-bold flex items-center justify-center gap-2">
                            <i class="fas fa-lock text-green-500"></i> Pembayaran Terproteksi & Instan
                        </p>
                    </div>
                </div>

                {{-- MODAL QRIS SIMULASI --}}
                <div x-show="showQris"
                     class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1e3a5f]/60 backdrop-blur-md"
                     x-cloak x-transition.opacity>
                    <div class="bg-white rounded-[3rem] p-10 max-w-sm w-full mx-4 shadow-2xl relative" @click.away="showQris = false">
                        <button @click="showQris = false"
                                class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-2xl bg-gray-50 text-gray-400 hover:text-gray-900 transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>

                        <div class="text-center mb-8">
                            <div class="inline-block bg-blue-50 text-[#1e3a5f] font-black px-4 py-1.5 rounded-full text-[10px] uppercase tracking-widest mb-4 border border-blue-100">
                                QRIS Payment Gateway
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 leading-tight">Simulasi Bayar</h3>
                            <p class="text-sm text-gray-500 mt-2 font-medium">Scan QR code untuk melanjutkan ke pemilihan kursi.</p>
                        </div>

                        <div class="bg-white p-8 rounded-[2rem] border-2 border-gray-100 flex items-center justify-center mb-8 shadow-inner">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg"
                                 alt="QRIS" class="w-48 h-48 opacity-90 mix-blend-multiply">
                        </div>

                        <div class="flex justify-between items-center bg-blue-50 p-5 rounded-2xl border border-blue-100 mb-8">
                            <span class="text-sm font-bold text-[#1e3a5f]">Total:</span>
                            <span class="text-xl font-black text-[#1e3a5f]">Rp 6.000</span>
                        </div>

                        <button @click="konfirmasiBayar()"
                                class="w-full bg-[#1e3a5f] hover:bg-slate-900 text-white font-bold py-4 rounded-2xl transition-all shadow-xl flex justify-center items-center gap-3 transform hover:-translate-y-1">
                            Konfirmasi Sudah Bayar
                        </button>
                    </div>
                </div>

                {{-- TAHAP 2: DENAH KURSI --}}
                <div x-show="tahap === 2" x-transition.duration.500ms x-cloak>
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 mb-8">
                        <div class="flex justify-between items-center mb-10 border-b border-gray-50 pb-6">
                            <div>
                                <h2 class="text-xl font-black text-gray-900">Denah Kursi Digital</h2>
                                <p class="text-sm text-gray-500 font-medium">Silakan tentukan 1 tempat duduk pilihan Anda.</p>
                            </div>
                            <div class="bg-orange-50 text-orange-500 px-5 py-2.5 rounded-2xl text-xs font-black border border-orange-100 flex items-center gap-2">
                                <i class="fas fa-ticket-alt"></i>
                                Pilihan: <span x-text="selectedSeats.length ? '#' + selectedSeats[0] : '-'"></span>
                            </div>
                        </div>

                        {{-- Bus Chassis Visualization --}}
                        <div class="max-w-md mx-auto bg-white border-[12px] border-slate-100 shadow-2xl rounded-[5rem] p-12 relative overflow-hidden">
                            <div class="absolute inset-0 bg-slate-50/50 -z-10"></div>

                            {{-- Cockpit/Front Area --}}
                            <div class="flex items-center justify-between mb-12 border-b-4 border-slate-100 pb-8 px-4">
                                <div class="w-16 h-16 bg-slate-900 rounded-3xl flex items-center justify-center border-4 border-white shadow-lg transform -rotate-12">
                                    <i class="fas fa-steering-wheel text-white text-2xl animate-pulse-slow"></i>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Entrance Area</span>
                                    <div class="w-24 h-4 bg-[#ffd700] rounded-full shadow-inner opacity-50"></div>
                                </div>
                            </div>

                            {{-- Seat Matrix --}}
                            <div class="relative">
                                {{-- Aisle visualization --}}
                                <div class="absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-10 sm:w-12 bg-slate-200/40 rounded-full -z-10 shadow-inner"></div>

                                <div class="grid grid-cols-4 gap-3 sm:gap-4 gap-y-6 sm:gap-y-8 relative">
                                    @for($i = 1; $i <= 16; $i++)
                                        <div class="{{ $i % 4 == 2 ? 'mr-6 sm:mr-8' : '' }} flex justify-center">
                                            <button type="button"
                                                    @click="toggleSeat({{ $i }})"
                                                    :disabled="bookedSeats.includes({{ $i }})"
                                                    :class="{
                                                        'bg-slate-100 text-slate-300 cursor-not-allowed border-transparent opacity-60': bookedSeats.includes({{ $i }}),
                                                        'bg-white text-slate-500 hover:border-[#c41e3a] hover:text-[#c41e3a] border-2 border-slate-200 shadow-sm group': !bookedSeats.includes({{ $i }}) && !selectedSeats.includes({{ $i }}),
                                                        'bg-[#c41e3a] text-white border-4 border-white shadow-xl scale-110 z-10 ring-4 ring-[#c41e3a]/20': selectedSeats.includes({{ $i }})
                                                    }"
                                                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 relative overflow-hidden group">
                                                <i class="fas fa-couch text-xs sm:text-sm mb-1 group-hover:scale-110 transition-transform"></i>
                                                <span class="font-black text-[9px] sm:text-[10px] tracking-tighter">{{ $i }}</span>
                                                
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
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-[0.5em] whitespace-nowrap">
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
                    </div>

                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 relative overflow-hidden">
                        {{-- Overlay jika kursi belum dipilih --}}
                        <div x-show="selectedSeats.length === 0"
                             class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 rounded-3xl flex items-center justify-center">
                            <div class="bg-[#1e3a5f] text-white text-xs font-black px-6 py-3.5 rounded-2xl flex items-center gap-3 shadow-2xl animate-bounce">
                                <i class="fas fa-mouse-pointer"></i>Klik Kursi yang Ingin Anda Pesan
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Arah Perjalanan</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <button type="button" @click="rute = 'Kampus Perintis Kemerdekaan -> Kampus Gowa'; document.getElementById('ruteInput').value = rute;"
                                        :class="rute === 'Kampus Perintis Kemerdekaan -> Kampus Gowa' ? 'border-orange-500 bg-orange-50 text-orange-600 shadow-sm' : 'border-gray-200 text-gray-400 hover:border-gray-300'"
                                        class="border-2 rounded-2xl py-4 px-2 text-xs font-bold transition-all text-center">
                                    Kampus Non-Merdeka Perintis Kemerdekaan <br><i class="fas fa-arrow-down my-2 text-[#1e3a5f]"></i><br> Kampus Non-Merdeka Gowa
                                </button>
                                <button type="button" @click="rute = 'Kampus Gowa -> Kampus Perintis Kemerdekaan'; document.getElementById('ruteInput').value = rute;"
                                        :class="rute === 'Kampus Gowa -> Kampus Perintis Kemerdekaan' ? 'border-orange-500 bg-orange-50 text-orange-600 shadow-sm' : 'border-gray-200 text-gray-400 hover:border-gray-300'"
                                        class="border-2 rounded-2xl py-4 px-2 text-xs font-bold transition-all text-center">
                                    Kampus Non-Merdeka Gowa <br><i class="fas fa-arrow-down my-2 text-[#1e3a5f]"></i><br> Kampus Non-Merdeka Perintis Kemerdekaan
                                </button>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Catatan Perjalanan (Opsional)</label>
                            <textarea onkeyup="document.getElementById('notesInput').value = this.value"
                                      rows="3"
                                      class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none resize-none transition-all placeholder:text-gray-300"
                                      placeholder="Contoh: Titik jemput Halte Teknik Gowa..."></textarea>
                        </div>

                        <button @click="document.getElementById('guestForm').submit()"
                                class="w-full bg-[#1e3a5f] hover:bg-slate-900 text-white font-black py-5 px-8 rounded-2xl shadow-2xl transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1">
                            <i class="fas fa-check-circle text-xl"></i>
                            Konfirmasi & Selesaikan Pesanan
                        </button>
                    </div>

                    <div class="mt-12 text-center">
                        <a href="{{ route('guest.buses') }}" class="text-sm font-bold text-gray-400 hover:text-[#1e3a5f] transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Batal & Pilih Armada Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
<script>
document.getElementById('guestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formEl = this;
    try {
        const res  = await fetch('/api/simulation/buses');
        const data = await res.json();
        const busId = {{ $bus->id }};
        const dbBus = data.buses.find(b => b.id === busId);
        
        if (dbBus && dbBus.trip_status !== 'standby') {
            alert('⚠️ Transaksi Batal\n\nBus ini sudah tidak Standby.\nAnda akan dialihkan ke bus lain.');
            window.location.href = "{{ route('guest.buses') }}";
            return;
        }

        if (typeof BusSimulation !== 'undefined' && dbBus) {
            BusSimulation.init(data.buses);
            const simBus = BusSimulation.getAllPositions().find(p => p.id === busId);
            if (simBus && simBus.trip_status !== 'standby') {
                alert('⚠️ Transaksi Batal\n\nPantauan simulasi menunjukkan bus telah berangkat.\nAnda akan dialihkan ke bus lain.');
                window.location.href = "{{ route('guest.buses') }}";
                return;
            }
        }
        formEl.submit();
    } catch(err) {
        formEl.submit();
    }
});
</script>
@endsection