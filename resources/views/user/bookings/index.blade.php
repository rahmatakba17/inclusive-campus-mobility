@extends('layouts.user')

@section('title', __('My Tickets'))
@section('user-content')

{{-- Page Header --}}
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

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl lg:text-3xl font-black text-[#1e3a5f] tracking-tighter uppercase">{{ __('Riwayat Perjalanan') }}</h2>
        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">
            {{ $bookings->total() }} {{ __('tiket ditemukan') }}
        </p>
    </div>
    <a href="{{ route('user.buses') }}"
       class="inline-flex items-center justify-center gap-2.5 bg-gradient-to-r from-[#1e3a5f] to-[#0f2137] text-white font-black px-7 py-3.5 rounded-2xl text-[11px] uppercase tracking-widest transition-all shadow-lg shadow-[#1e3a5f]/20 hover:shadow-xl hover:shadow-[#1e3a5f]/30 hover:-translate-y-0.5 transform">
        <i class="fas fa-plus text-[10px]" aria-hidden="true"></i>
        {{ __('Pesan Tiket Baru') }}
    </a>
</div>

{{-- Stat Summary Bar --}}
@php
    // Hitung stat dari seluruh tiket user (bukan hanya yang ada di halaman ini)
    $all = auth()->user()->bookings;
    $cntSelesai   = $all->where('is_completed', true)->count();
    $cntAktif     = $all->where('is_completed', false)->whereIn('status', ['confirmed','pending'])->count();
    $cntBatal     = $all->where('status', 'cancelled')->count();
@endphp
<div class="grid grid-cols-3 gap-3 md:gap-4 mb-8">
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
        <p class="text-2xl md:text-3xl font-black text-[#1e3a5f] leading-none mb-1">{{ $cntAktif }}</p>
        <p class="text-[8px] md:text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Aktif') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
        <p class="text-2xl md:text-3xl font-black text-emerald-500 leading-none mb-1">{{ $cntSelesai }}</p>
        <p class="text-[8px] md:text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Selesai') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
        <p class="text-2xl md:text-3xl font-black text-rose-500 leading-none mb-1">{{ $cntBatal }}</p>
        <p class="text-[8px] md:text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Batal') }}</p>
    </div>
</div>

{{-- Ticket List Wrapper --}}
<div>

    {{-- Filtering Area (Server-side Form) --}}
    <form action="{{ route('user.bookings.index') }}" method="GET" x-data="{}" class="flex flex-col lg:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-500"></i>
            </div>
            <input type="text" name="query" value="{{ request('query') }}" 
                   @input.debounce.500ms="$event.target.form.submit()"
                   placeholder="{{ __('Cari armada bus, kode tiket, atau rute...') }}" 
                   class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f]/30 transition-all">
        </div>
        
        <div class="flex gap-3">
            <div class="relative">
                <input type="date" name="date" value="{{ request('date') }}" 
                       @change="$event.target.form.submit()"
                       aria-label="{{ __('Filter berdasarkan tanggal') }}"
                       class="w-full md:w-36 lg:w-40 px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f]/30 transition-all cursor-text text-center">
            </div>
            
            <div class="relative">
                <select name="status" aria-label="{{ __('Filter berdasarkan status') }}"
                        @change="$event.target.form.submit()"
                        class="w-full md:w-32 lg:w-36 pl-4 pr-8 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f]/30 transition-all appearance-none cursor-pointer">
                    <option value="" @selected(request('status') == '')>{{ __('Semua Status') }}</option>
                    <option value="aktif" @selected(request('status') == 'aktif')>{{ __('Aktif') }}</option>
                    <option value="selesai" @selected(request('status') == 'selesai')>{{ __('Selesai') }}</option>
                    <option value="batal" @selected(request('status') == 'batal')>{{ __('Batal') }}</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>
        </div>
    </form>

    {{-- Tickets --}}
    <div class="space-y-4">
        @forelse($bookings as $booking)
        @php
            $isCompleted = $booking->is_completed;
            $isCancelled = $booking->status === 'cancelled';
            $isPending   = !$isCompleted && $booking->status === 'pending';
            $isActive    = !$isCompleted && $booking->status === 'confirmed';

            // Determine route direction from notes
            $notes = strtolower($booking->notes ?? '');
            $isGowa = str_contains($notes, 'gowa -> kampus perintis')
                   || str_contains($notes, 'gowa->kampus perintis')
                   || str_contains($notes, 'gowa -> perintis');

            $routeLabel = $isGowa ? 'Kampus Non-Merdeka Kampus Gowa → Perintis' : 'Perintis → Kampus Non-Merdeka Kampus Gowa';
            $seatLabel = $booking->seat_number > 16 ? 'Berdiri ' . ($booking->seat_number - 16) : 'Kursi ' . $booking->seat_number;
            $dateLabel = $booking->booking_date->translatedFormat('d M Y');
        @endphp

        <article class="group bg-white rounded-[1.5rem] border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-300 overflow-hidden"
                 aria-label="{{ __('Tiket') }} {{ $booking->booking_code }}">
            
            <div class="p-5 md:p-6 lg:p-7 flex flex-col md:flex-row gap-5 md:gap-8 justify-between md:items-center">
                
                {{-- Left Info Area --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between md:justify-start gap-4 mb-3">
                        <span class="text-[9px] font-black uppercase tracking-widest text-[#c41e3a]">{{ $routeLabel }}</span>
                        
                        {{-- Status Badge (Mobile top-right, Desktop aligned near title/route) --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[8px] font-black uppercase tracking-widest border
                            {{ $isCompleted ? 'bg-violet-50 text-violet-600 border-violet-100' : '' }}
                            {{ $isActive    ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                            {{ $isPending   ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                            {{ $isCancelled ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                {{ $isCompleted ? 'bg-violet-500' : '' }}
                                {{ $isActive    ? 'bg-emerald-500 animate-pulse' : '' }}
                                {{ $isPending   ? 'bg-amber-500' : '' }}
                                {{ $isCancelled ? 'bg-rose-500' : '' }}"></span>
                            {{ __($booking->status_badge) }}
                        </span>
                    </div>

                    <h3 class="font-black text-slate-800 text-lg tracking-tight mb-2 truncate">{{ $booking->bus->name }}</h3>
                    
                    {{-- Minimalist text info instead of heavy pills/icons --}}
                    <div class="flex flex-wrap items-center gap-2 md:gap-3 text-[11px] font-medium text-slate-500">
                        <span class="text-slate-700 font-bold">{{ $dateLabel }}</span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full" aria-hidden="true"></span>
                        <span class="text-slate-700">{{ $seatLabel }}</span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full" aria-hidden="true"></span>
                        <span class="font-mono tracking-widest">{{ $booking->booking_code }}</span>
                    </div>
                </div>

                {{-- Right Actions Area --}}
                <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-3 md:border-l border-t md:border-t-0 border-slate-100 pt-4 md:pt-0 md:pl-8">
                    
                    <a href="{{ route('user.bookings.show', $booking) }}"
                       class="w-full md:w-auto inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 transition-colors">
                        {{ __('Lihat Tiket') }}
                    </a>

                    @if($isPending)
                    <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST"
                          onsubmit="return confirm('{{ __('Batalkan pemesanan ini?') }}')" class="w-full md:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full md:w-auto inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-white hover:bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-rose-200 transition-colors">
                            {{ __('Batal') }}
                        </button>
                    </form>
                    @endif
                </div>
                
            </div>
        </article>
        @empty
        {{-- Empty state --}}
        <div class="bg-white rounded-[2rem] p-16 text-center border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-ticket-alt text-3xl text-slate-300"></i>
                </div>
                <h3 class="font-black text-slate-800 text-lg mb-2 tracking-tighter">{{ __('Belum Ada Tiket') }}</h3>
                <p class="text-slate-500 text-xs mb-8 max-w-xs mx-auto font-medium leading-relaxed">{{ request('query') || request('date') || request('status') ? __('Tidak ada tiket yang cocok dengan filter pencarian Anda.') : __('Anda belum memesan tiket perjalanan apapun. Silakan pesan tiket untuk memulai perjalanan Anda.') }}</p>
                
                @if(request('query') || request('date') || request('status'))
                <a href="{{ route('user.bookings.index') }}"
                   class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-black px-6 py-3 rounded-xl text-[10px] transition-all uppercase tracking-widest mb-4">
                    <i class="fas fa-undo"></i>
                    {{ __('Reset Filter') }}
                </a>
                @endif
                
                <a href="{{ route('user.buses') }}"
                   class="inline-flex items-center gap-3 bg-[#1e3a5f] hover:bg-[#0f2137] text-white font-black px-6 py-3 rounded-xl text-[10px] transition-all uppercase tracking-widest">
                    <i class="fas fa-search"></i>
                    {{ __('Pesan Tiket Sekarang') }}
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- Pagination --}}
@if($bookings->hasPages())
    <div class="mt-10 flex justify-center">
        {{ $bookings->links() }}
    </div>
@endif

@endsection