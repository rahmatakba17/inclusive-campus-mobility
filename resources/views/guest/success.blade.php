@extends('layouts.app')

@section('title', 'Pemesanan Berhasil')
@section('content')

<div class="min-h-screen flex items-center justify-center pt-32 pb-20 bg-gray-50">
    <div class="max-w-md w-full px-6 text-center">

        <div class="mb-8 relative inline-block">
            <div class="w-24 h-24 bg-green-50 rounded-[2.5rem] flex items-center justify-center text-green-500 transform rotate-12 border-2 border-green-100 shadow-xl shadow-green-200/50">
                <i class="fas fa-check-circle text-5xl transform -rotate-12"></i>
            </div>
            <div class="absolute -top-4 -right-4 w-12 h-12 bg-white rounded-2xl flex items-center justify-center animate-pulse shadow-lg p-2 border border-gray-100">
                <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" alt="Logo">
            </div>
        </div>

        <h1 class="text-3xl font-black text-gray-900 mb-2">Pemesanan Berhasil!</h1>
        <p class="text-gray-500 font-medium mb-10 leading-relaxed">
            Terima kasih <b>{{ $booking->guest_name }}</b>! Kursi Anda telah dipesan.
            Silakan simpan detail di bawah untuk ditunjukkan kepada kondektur.
        </p>

        <div class="bg-white rounded-[2rem] border-4 border-white shadow-2xl p-8 mb-10 text-left relative overflow-hidden">

            {{-- Decorative pattern --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full -translate-y-16 translate-x-16 opacity-50"></div>

            <div class="relative z-10">
                {{-- Ticket Header --}}
                <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-100 border-dashed">
                    <div>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Nomor Kursi</p>
                        <p class="text-4xl font-black text-orange-500 leading-none">#{{ $booking->seat_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Kode Booking</p>
                        <p class="text-sm font-black text-[#1e3a5f] bg-gray-100 px-3 py-1 rounded-lg">{{ $booking->booking_code }}</p>
                    </div>
                </div>

                {{-- Ticket Body --}}
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                            <i class="fas fa-bus"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1">Armada Bus</p>
                            <p class="text-sm font-bold text-gray-800">{{ $booking->bus->name }}</p>
                            <p class="text-[10px] text-gray-400 leading-none mt-0.5">{{ $booking->bus->plate_number }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1">Tanggal & Rute</p>
                            <p class="text-sm font-bold text-gray-800">{{ $booking->booking_date->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400 leading-none mt-0.5">{{ $booking->bus->route }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1">Identitas Tamu</p>
                            <p class="text-sm font-bold text-gray-800">{{ $booking->guest_name }}</p>
                            <p class="text-[10px] text-gray-400 leading-none mt-0.5">{{ $booking->guest_phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Price tag --}}
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center relative z-10">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Harga Tiket Tamu</span>
                <span class="text-xl font-black text-[#1e3a5f]">Rp 6.000</span>
            </div>
        </div>

        <div class="space-y-4">
            <button onclick="window.print()"
                    class="w-full bg-[#1e3a5f] hover:bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-xl shadow-[#1e3a5f]/20 transition-all flex items-center justify-center gap-3">
                <i class="fas fa-print"></i> Cetak E-Ticket
            </button>
            <a href="{{ route('home') }}"
               class="block w-full text-center bg-white hover:bg-gray-50 text-gray-600 font-bold py-4 rounded-2xl border border-gray-200 transition-all">
                Selesai & Kembali ke Home
            </a>
        </div>

        <p class="mt-8 text-xs text-gray-400 flex items-center justify-center gap-2">
            <i class="fas fa-info-circle text-orange-500"></i>
            Simpan screenshot halaman ini sebagai cadangan tiket.
        </p>
    </div>
</div>
@endsection