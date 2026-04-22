@extends('layouts.admin', ['view_name' => 'Passengers'])

@section('title', __('Pengguna Bus'))
@section('admin-content')

<div x-data="userManager()" class="relative">
    {{-- Main loader overlay --}}
    <div x-show="loadingGrid" class="absolute inset-0 z-50 bg-white/50 backdrop-blur-sm flex items-center justify-center rounded-[2.5rem]">
        <div class="w-16 h-16 border-4 border-[#c41e3a] border-t-transparent rounded-full animate-spin"></div>
    </div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-white p-5 border-l-4 border-slate-900 flex items-center gap-5 shadow-sm">
        <div class="w-12 h-12 bg-slate-900 text-white rounded flex items-center justify-center flex-shrink-0">
            <i class="fas fa-users text-lg"></i>
        </div>
        <div>
            <p class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ $stats['total_users'] }}</p>
            <h2 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1.5">{{ __('Total Passengers') }}</h2>
        </div>
    </div>

    <div class="bg-white p-5 border-l-4 border-slate-300 flex items-center gap-5 shadow-sm">
        <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-check text-lg"></i>
        </div>
        <div>
            <p class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ $stats['active_users'] }}</p>
            <h2 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1.5">{{ __('Active Transactors') }}</h2>
        </div>
    </div>

    <div class="bg-white p-5 border-l-4 border-slate-300 flex items-center gap-5 shadow-sm">
        <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded flex items-center justify-center flex-shrink-0">
            <i class="fas fa-calendar-check text-lg"></i>
        </div>
        <div>
            <p class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ $stats['today_users'] }}</p>
            <h2 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1.5">{{ __('Today') }}</h2>
        </div>
    </div>
</div>

{{-- User Table --}}
<section class="bg-white shadow-sm border border-slate-200">
    <div class="px-8 py-6 border-b-2 border-slate-900 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50">
        <div>
            <h1 class="font-black text-slate-900 text-2xl tracking-tighter">{{ __('Passenger Identity') }}</h1>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Management of all registered travelers') }}</p>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.users.index') }}" method="GET" x-ref="searchForm" @submit.prevent>
                <div class="relative group">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search passenger...') }}"
                           @input.debounce.500ms="fetchData"
                           class="pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-none text-sm focus:ring-0 focus:border-slate-900 transition-colors w-full md:w-72 font-semibold">
                </div>
            </form>
            <div class="bg-slate-900 text-white px-4 py-2.5 flex items-center gap-3">
                <span class="text-xs font-black tracking-widest text-slate-500 border-r border-slate-700 pr-3">{{ __('TOTAL') }}</span>
                <span class="text-base font-black tracking-tighter">{{ $users->total() }}</span>
            </div>
        </div>
    </div>

<div id="table-container">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] border-b border-slate-200 bg-white">
                    <th class="px-8 py-5">{{ __('Passenger') }}</th>
                    <th class="px-8 py-5">{{ __('Category') }}</th>
                    <th class="px-8 py-5">{{ __('Status') }}</th>
                    <th class="px-8 py-5 text-center">{{ __('Bookings') }}</th>
                    <th class="px-8 py-5">{{ __('Registration') }}</th>
                    <th class="px-8 py-5 text-right">{{ __('Options') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded bg-slate-900 text-white flex items-center justify-center font-black flex-shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-sm tracking-tight">{{ $user->name }}</p>
                                <p class="text-[10px] text-slate-500 font-bold mt-0.5">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-sm text-[9px] font-black uppercase tracking-widest border border-slate-200 bg-slate-100 text-slate-700">
                            {{ $user->roleNameDisplay() }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-sm text-[9px] font-black uppercase tracking-widest border border-emerald-200 bg-emerald-50 text-emerald-700" title="Akun Aktif">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_rgba(16,185,129,0.5)]"></span>
                                AKTIF
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-sm text-[9px] font-black uppercase tracking-widest border border-amber-200 bg-amber-50 text-amber-700" title="Akun Menunggu Perizinan">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_5px_rgba(245,158,11,0.5)]"></span>
                                TERTUNDA
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-sm font-black text-slate-900">{{ $user->bookings_count }}</span>
                    </td>
                    <td class="px-8 py-5 text-xs text-slate-500 font-bold tracking-tight">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-2 text-right">
                            <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                   class="inline-flex items-center justify-center bg-white border {{ $user->is_active ? 'border-amber-300 hover:border-amber-500 text-amber-700 hover:text-amber-900 hover:bg-amber-50' : 'border-emerald-300 hover:border-emerald-500 text-emerald-700 hover:text-emerald-900 hover:bg-emerald-50' }} font-black py-2 px-3 min-w-[90px] text-[9px] tracking-widest uppercase transition-colors"
                                   title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                    @if($user->is_active)
                                        <i class="fas fa-ban mr-1.5"></i> SUSPEND
                                    @else
                                        <i class="fas fa-check-circle mr-1.5"></i> ACTIVATE
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="inline-flex items-center justify-center gap-2 bg-white border border-slate-300 hover:border-slate-900 text-slate-700 hover:text-slate-900 font-black py-2 px-4 text-[9px] tracking-widest uppercase transition-colors">
                                {{ __('Detail') }}
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center">
                        <i class="fas fa-user-slash text-slate-300 text-3xl mb-4"></i>
                        <h2 class="text-lg font-black text-slate-900 tracking-tight">Empty Database</h2>
                        <p class="text-slate-500 font-bold text-xs mt-1">No passengers found in the system</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-200" @click.prevent="handlePagination($event)">
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>
</section>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('userManager', () => ({
            loadingGrid: false,
            init() {
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