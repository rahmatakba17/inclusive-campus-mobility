@extends('layouts.admin', ['view_name' => 'Bookings'])

@section('title', __('Kelola Pemesanan'))
@section('admin-content')

<div x-data="bookingManager()" class="relative">
    {{-- Main loader overlay --}}
    <div x-show="loadingGrid" class="absolute inset-0 z-50 bg-white/50 backdrop-blur-sm flex items-center justify-center rounded-[2.5rem]">
        <div class="w-16 h-16 border-4 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

{{-- Stats Strip --}}
<div id="stats-container">
<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="fas fa-list text-blue-600"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['total'] }}</p>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('Total Entry') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="fas fa-clock text-amber-600"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['pending'] }}</p>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('Awaiting') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="fas fa-check-circle text-emerald-600"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['confirmed'] }}</p>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('Authorized') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5 group hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-rose-100 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="fas fa-times-circle text-rose-600"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $stats['cancelled'] }}</p>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('Voided') }}</p>
        </div>
    </div>
</div>
</div>

{{-- Search + Filter Bar --}}
<form action="{{ route('admin.bookings.index') }}"
      method="GET"
      x-ref="filterForm"
      @submit.prevent="fetchData"
      class="mb-6">

    {{-- ── Search Bar (standalone, seperti Fleet) ── --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm px-4 py-3 flex items-center gap-3 mb-3">
        <i class="fas fa-search text-slate-300 text-sm flex-shrink-0" aria-hidden="true"></i>
        <label class="sr-only" for="booking-search">{{ __('Cari pemesanan') }}</label>
        <input id="booking-search"
               type="search"
               name="q"
               value="{{ request('q') }}"
               placeholder="{{ __('Cari nama penumpang, kode booking, atau email...') }}"
               x-ref="qInput"
               @input.debounce.500ms="fetchData"
               @search="fetchData"
               class="flex-1 text-sm font-medium text-slate-700 bg-transparent outline-none placeholder:text-slate-300"
               autocomplete="off"
               aria-label="{{ __('Cari pemesanan') }}">
        {{-- Clear button —tampil hanya saat ada teks --}}
        @if(request('q'))
        <a href="{{ route('admin.bookings.index', array_diff_key(request()->query(), ['q' => ''])) }}"
           class="w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all flex-shrink-0"
           aria-label="{{ __('Hapus pencarian') }}">
            <i class="fas fa-times text-[9px]"></i>
        </a>
        @endif
    </div>

    {{-- ── Filter Dropdowns ── --}}
    <div class="flex items-center gap-3 flex-wrap">
        <div class="flex items-center gap-2 flex-1 min-w-[160px] bg-white border border-slate-100 rounded-2xl px-4 py-2.5 shadow-sm">
            <i class="fas fa-tag text-slate-300 text-xs flex-shrink-0" aria-hidden="true"></i>
            <select name="status"
                    @change="fetchData"
                    class="flex-1 text-xs font-bold text-slate-600 bg-transparent outline-none cursor-pointer appearance-none"
                    aria-label="{{ __('Filter status pemesanan') }}">
                <option value="">{{ __('Semua Status') }}</option>
                <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>⏳ {{ __('Menunggu') }}</option>
                <option value="confirmed"  {{ request('status') === 'confirmed'  ? 'selected' : '' }}>✅ {{ __('Terverifikasi') }}</option>
                <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>❌ {{ __('Dibatalkan') }}</option>
            </select>
        </div>

        <div class="flex items-center gap-2 flex-1 min-w-[160px] bg-white border border-slate-100 rounded-2xl px-4 py-2.5 shadow-sm">
            <i class="fas fa-bus text-slate-300 text-xs flex-shrink-0" aria-hidden="true"></i>
            <select name="bus_id"
                    @change="fetchData"
                    class="flex-1 text-xs font-bold text-slate-600 bg-transparent outline-none cursor-pointer appearance-none"
                    aria-label="{{ __('Filter armada bus') }}">
                <option value="">{{ __('Semua Armada') }}</option>
                @foreach($buses as $bus)
                    <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>{{ $bus->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2 flex-1 min-w-[160px] bg-white border border-slate-100 rounded-2xl px-4 py-2.5 shadow-sm">
            <i class="far fa-calendar text-slate-300 text-xs flex-shrink-0" aria-hidden="true"></i>
            <input type="date"
                   name="date"
                   value="{{ request('date') }}"
                   @change="fetchData"
                   class="flex-1 text-xs font-bold text-slate-600 bg-transparent outline-none cursor-pointer"
                   aria-label="{{ __('Filter tanggal pemesanan') }}">
        </div>

        {{-- Reset — hanya muncul jika ada filter aktif --}}
        @if(request()->hasAny(['q','status','bus_id','date']))
        <a href="{{ route('admin.bookings.index') }}"
           class="flex-shrink-0 inline-flex items-center gap-1.5 text-[9px] font-black text-slate-400 hover:text-red-500 uppercase tracking-widest transition-colors px-3 py-2.5"
           aria-label="{{ __('Reset semua filter') }}">
            <i class="fas fa-rotate-left text-[9px]"></i>
            Reset
        </a>
        @endif

        {{-- Active filter indicators --}}
        @if(request()->hasAny(['q','status','bus_id','date']))
        <div class="w-full flex items-center gap-2 flex-wrap pt-1" aria-live="polite">
            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">{{ __('Aktif') }}:</span>
            @if(request('q'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#1e3a5f]/8 text-[#1e3a5f] border border-[#1e3a5f]/15 rounded-lg text-[8px] font-black uppercase tracking-widest">
                    <i class="fas fa-search text-[7px]"></i> "{{ Str::limit(request('q'), 25) }}"
                </span>
            @endif
            @if(request('status'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[8px] font-black uppercase tracking-widest">
                    <i class="fas fa-tag text-[7px]"></i> {{ request('status') }}
                </span>
            @endif
            @if(request('bus_id'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[8px] font-black uppercase tracking-widest">
                    <i class="fas fa-bus text-[7px]"></i> {{ optional($buses->find(request('bus_id')))->name ?? 'Armada' }}
                </span>
            @endif
            @if(request('date'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[8px] font-black uppercase tracking-widest">
                    <i class="fas fa-calendar text-[7px]"></i> {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}
                </span>
            @endif
        </div>
        @endif
    </div>
</form>


{{-- Table Container --}}
<div id="table-container">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/30 border-b border-slate-50">
                    <th class="px-10 py-6 text-left">{{ __('Internal ID') }}</th>
                    <th class="px-10 py-6 text-left">{{ __('Traveler Identity') }}</th>
                    <th class="px-10 py-6 text-left">{{ __('Service Unit') }}</th>
                    <th class="px-10 py-6 text-left">{{ __('Target Date') }}</th>
                    <th class="px-10 py-6 text-left">{{ __('State Approval') }}</th>
                    <th class="px-10 py-6 text-right">{{ __('Utility') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50/50">
                @forelse($bookings as $booking)
                <tr class="hover:bg-slate-50/30 transition-all duration-300 group">
                    <td class="px-10 py-6 font-mono text-[10px] font-bold text-slate-400">#{{ $booking->booking_code }}</td>
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center font-black text-xs shadow-sm shadow-orange-500/20 transition-transform group-hover:scale-105">
                                {{ $booking->passenger_avatar }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ $booking->passenger_name }}</p>
                                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $booking->passenger_contact }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo_kampus_non_merdeka.png') }}" class="w-4 h-4 grayscale" alt="Logo">
                            <span class="text-xs font-bold text-slate-600">{{ $booking->bus->name }}</span>
                        </div>
                    </td>
                    <td class="px-10 py-6 text-sm text-slate-500 font-bold">{{ $booking->booking_date->format('d M Y') }}</td>
                    <td class="px-10 py-6">
                        <select name="status" @change="updateStatus('{{ route('admin.bookings.updateStatus', $booking) }}', $event.target.value)"
                                class="text-[10px] font-black uppercase tracking-widest border border-slate-200 rounded-xl px-4 py-2 bg-white focus:ring-4 focus:ring-orange-500/10 outline-none cursor-pointer transition-all shadow-sm hover:border-orange-300">
                            <option value="pending"   {{ $booking->status === 'pending'   ? 'selected' : '' }}>⏳ {{ __('Awaiting') }}</option>
                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>✅ {{ __('Verified') }}</option>
                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>❌ {{ __('Voided') }}</option>
                        </select>
                    </td>
                    <td class="px-10 py-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="w-10 h-10 bg-slate-50 hover:bg-slate-900 hover:text-white text-slate-400 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
                                  onsubmit="return confirm('Archive transaction data?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-10 h-10 bg-rose-50/10 hover:bg-rose-500 hover:text-white text-rose-500 rounded-xl flex items-center justify-center transition-all duration-300">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-32 text-center text-slate-400 relative overflow-hidden">
                        <div class="absolute inset-0 bg-slate-50/20 -z-10"></div>
                        <div class="w-24 h-24 bg-white rounded-[2rem] shadow-xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-inbox text-slate-200 text-4xl"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-800">{{ __('No Transaction Records') }}</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">{{ __('The analysis found no matching entries') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div class="px-10 py-8 bg-slate-50/30 border-t border-slate-100" @click.prevent="handlePagination($event)">
            {{ $bookings->withQueryString()->links() }}
        </div>
    @endif
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingManager', () => ({
            loadingGrid: false,
            init() {
                // handle native back/forward buttons
                window.addEventListener('popstate', (e) => {
                    if(e.state && e.state.loadedData) {
                        this.refreshDOM(e.state.loadedData);
                    } else {
                        window.location.reload();
                    }
                });
            },
            async fetchData() {
                this.loadingGrid = true;
                const url = new URL(this.$refs.filterForm.action);
                const formData = new FormData(this.$refs.filterForm);
                // Include ALL fields including empty ones so clearing search/filters works
                for (let pair of formData.entries()) {
                    url.searchParams.set(pair[0], pair[1]);
                }
                // Remove truly empty params for clean URL (except keep q if user cleared it)
                url.searchParams.forEach((val, key) => {
                    if (!val) url.searchParams.delete(key);
                });
                
                try {
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const html = await res.text();
                    this.refreshDOM(html);
                    window.history.pushState({ loadedData: html }, '', url.toString());
                } catch (error) {
                    console.error("Filter failed:", error);
                } finally {
                    this.loadingGrid = false;
                }
            },
            async handlePagination(event) {
                // Check if the click is on an anchor tag
                const link = event.target.closest('a');
                if (!link) return;
                
                this.loadingGrid = true;
                try {
                    const res = await fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const html = await res.text();
                    this.refreshDOM(html);
                    window.history.pushState({ loadedData: html }, '', link.href);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } catch (error) {
                    console.error("Pagination failed:", error);
                } finally {
                    this.loadingGrid = false;
                }
            },
            refreshDOM(html) {
                const parser = new DOMParser();
                const doc    = parser.parseFromString(html, 'text/html');
                // Refresh table
                const newTable = doc.getElementById('table-container');
                if (newTable) {
                    document.getElementById('table-container').innerHTML = newTable.innerHTML;
                }
                // Refresh stats strip so counts stay accurate
                const newStats = doc.getElementById('stats-container');
                if (newStats) {
                    document.getElementById('stats-container').innerHTML = newStats.innerHTML;
                }
            },
            async updateStatus(url, statusValue) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const res = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: statusValue })
                    });
                    
                    if (res.ok) {
                        // Alert automatically handled by response from server if needed,
                        // or we just update quietly without reload!
                        const data = await res.json();
                        if(data.success) {
                             // Optional: show a mini toast notification here
                        }
                    } else {
                        alert("Terjadi kesalahan teknis saat mengubah status.");
                    }
                } catch(error) {
                    console.error("Status update failed:", error);
                    alert("Gagal menghubungi server.");
                }
            }
        }));
    });
</script>
@endsection