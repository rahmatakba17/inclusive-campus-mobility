@extends('layouts.admin', ['view_name' => 'Drivers'])

@section('title', __('Kelola Sopir'))
@section('admin-content')

<div x-data="driverManager()" class="relative">
    {{-- Main loader overlay --}}
    <div x-show="loadingGrid" class="absolute inset-0 z-50 bg-white/50 backdrop-blur-sm flex items-center justify-center rounded-[2.5rem]" style="display: none;">
        <div class="w-16 h-16 border-4 border-[#c41e3a] border-t-transparent rounded-full animate-spin"></div>
    </div>

{{-- Driver Table --}}
<section class="bg-white shadow-sm border border-slate-200">
    <div class="px-8 py-6 border-b-2 border-slate-900 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50">
        <div>
            <h1 class="font-black text-slate-900 text-2xl tracking-tighter">{{ __('Driver Identity') }}</h1>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Management of all operational drivers') }}</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.drivers.create') }}" class="bg-slate-900 hover:bg-slate-700 text-white px-5 py-2.5 shadow-sm transition-colors font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-plus"></i> {{ __('Add Driver') }}
            </a>
            
            <form action="{{ route('admin.drivers.index') }}" method="GET" x-ref="searchForm" @submit.prevent>
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search driver...') }}"
                           @input.debounce.500ms="fetchData"
                           class="pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-none text-sm focus:ring-0 focus:border-slate-900 transition-colors w-full md:w-60 font-semibold">
                </div>
            </form>
            <div class="bg-slate-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="text-[10px] font-black tracking-widest text-slate-400 border-r border-slate-700 pr-3">{{ __('TOTAL') }}</span>
                <span class="text-base font-black tracking-tighter">{{ $drivers->total() }}</span>
            </div>
        </div>
    </div>

<div id="table-container">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] border-b border-slate-200 bg-white">
                    <th class="px-8 py-5">{{ __('Driver Detail') }}</th>
                    <th class="px-8 py-5">{{ __('Assigned Bus') }}</th>
                    <th class="px-8 py-5">{{ __('Registration') }}</th>
                    <th class="px-8 py-5 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($drivers as $driver)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded bg-slate-900 text-white flex items-center justify-center font-black flex-shrink-0">
                                {{ substr($driver->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-sm tracking-tight">{{ $driver->name }}</p>
                                <p class="text-[10px] text-slate-500 font-bold mt-0.5">{{ $driver->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        @if($driver->bus)
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-900">{{ $driver->bus->name }}</span>
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">{{ $driver->bus->plate_number }}</span>
                            </div>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-700 rounded-sm border border-slate-200 text-[9px] font-black uppercase tracking-widest">{{ __('Not Assigned') }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-xs text-slate-500 font-bold tracking-tight">
                        {{ $driver->created_at->format('d M Y') }}
                    </td>
                    <td class="px-8 py-5 text-right space-x-2">
                        <a href="{{ route('admin.drivers.edit', $driver) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-none border border-slate-300 text-slate-500 hover:border-slate-900 hover:text-slate-900 transition-colors">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form action="{{ route('admin.drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('Hapus sopir ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-none border border-slate-300 text-rose-500 hover:border-rose-600 hover:text-rose-600 transition-colors">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-20 text-center">
                        <i class="fas fa-id-card-clip text-slate-300 text-3xl mb-4"></i>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Empty Database</h2>
                        <p class="text-slate-500 font-bold text-xs mt-1">No drivers found in the system</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($drivers->hasPages())
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-200" @click.prevent="handlePagination($event)">
            {{ $drivers->withQueryString()->links() }}
        </div>
    @endif
</div>
</section>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('driverManager', () => ({
            loadingGrid: false,
            async fetchData() {
                this.loadingGrid = true;
                const url = new URL(this.$refs.searchForm.action);
                const formData = new FormData(this.$refs.searchForm);
                for (let pair of formData.entries()) {
                    if (pair[1]) url.searchParams.append(pair[0], pair[1]);
                }
                
                try {
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const html = await res.text();
                    this.refreshDOM(html);
                    window.history.pushState({ loadedData: html }, '', url.toString());
                } catch (error) {
                    console.error("Search failed:", error);
                } finally {
                    this.loadingGrid = false;
                }
            },
            async handlePagination(event) {
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
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('table-container');
                if (newContent) {
                    document.getElementById('table-container').innerHTML = newContent.innerHTML;
                }
            }
        }));
    });
</script>
@endsection
