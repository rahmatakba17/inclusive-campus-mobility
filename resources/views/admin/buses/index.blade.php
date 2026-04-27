@extends('layouts.admin', ['view_name' => 'Fleet Data'])

@section('title', __('Manajemen Armada Bus Kampus Non-Merdeka'))

@push('styles')
<style>
    /* Entrance animation */
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .row-enter { animation: fadeSlideUp 0.3s ease both; }
    .row-enter:nth-child(1)  { animation-delay: .04s; }
    .row-enter:nth-child(2)  { animation-delay: .08s; }
    .row-enter:nth-child(3)  { animation-delay: .12s; }
    .row-enter:nth-child(4)  { animation-delay: .16s; }
    .row-enter:nth-child(5)  { animation-delay: .20s; }
    .row-enter:nth-child(6)  { animation-delay: .24s; }
    .row-enter:nth-child(7)  { animation-delay: .28s; }
    .row-enter:nth-child(8)  { animation-delay: .32s; }
    .row-enter:nth-child(9)  { animation-delay: .36s; }
    .row-enter:nth-child(10) { animation-delay: .40s; }

    .bus-row { transition: background 0.15s, box-shadow 0.15s; }
    .bus-row:hover { background: #f8fafc; box-shadow: 0 2px 12px rgba(30,58,95,0.06); }

    /* Status pill */
    .pill-active   { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
    .pill-maintain { background:#fef9c3; color:#854d0e; border:1px solid #fde68a; }
    .pill-inactive { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }

    /* Trip status */
    .trip-jalan    { background:#dbeafe; color:#1d4ed8; border:1px solid #bfdbfe; }
    .trip-standby  { background:#fef3c7; color:#b45309; border:1px solid #fde68a; }
    .trip-istirahat{ background:#ede9fe; color:#6d28d9; border:1px solid #ddd6fe; }

    /* Action buttons */
    .btn-action { transition: all .2s cubic-bezier(.4,0,.2,1); }
    .btn-action:hover { transform: scale(1.1); }

    /* Search box focus ring */
    #search-input:focus { box-shadow: 0 0 0 3px rgba(30,58,95,0.12); }

    /* Capacity bar */
    .cap-bar { height: 3px; border-radius: 999px; background: #e2e8f0; overflow: hidden; }
    .cap-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg,#3b82f6,#1e3a5f); transition: width .4s; }
</style>
@endpush

@section('admin-content')

{{-- ===== PAGE HEADER ===== --}}
<section aria-label="Header halaman manajemen armada" class="mb-8">

    {{-- Meta info strip --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
        <div>
            <nav class="flex items-center gap-1.5 text-[9px] font-black text-slate-500 uppercase tracking-widest mb-3" aria-label="Breadcrumb">
                <i class="fas fa-home text-slate-300"></i>
                <span>/</span>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition-colors">Admin</a>
                <span>/</span>
                <span class="text-[#1e3a5f]">Fleet Data</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-none">Manajemen Armada</h1>
            <p class="text-xs text-slate-500 font-semibold mt-1.5">
                Kelola seluruh unit bus operasional Bus Kampus Kampus Non-Merdeka
            </p>
        </div>

        <a href="{{ route('admin.buses.create') }}"
           class="inline-flex items-center gap-2.5 bg-[#1e3a5f] hover:bg-[#163050] text-white font-black px-5 py-3.5 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 text-xs uppercase tracking-widest group flex-shrink-0"
           aria-label="Tambah armada bus baru">
            <span class="w-5 h-5 rounded-lg bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition-colors">
                <i class="fas fa-plus text-[9px]"></i>
            </span>
            Tambah Armada
        </a>
    </div>

    {{-- Stats bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
        @php
            $total    = $buses->total();
            $active   = \App\Models\Bus::where('status','active')->count();
            $maint    = \App\Models\Bus::where('status','maintenance')->count();
            $inactive = \App\Models\Bus::where('status','inactive')->count();
        @endphp
        <div class="bg-white border border-slate-100 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-bus text-slate-500 text-xs"></i>
            </div>
            <div>
                <p class="text-xl font-black text-slate-900 leading-none">{{ $total }}</p>
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mt-0.5">Total Unit</p>
            </div>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
            </div>
            <div>
                <p class="text-xl font-black text-emerald-600 leading-none">{{ $active }}</p>
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mt-0.5">Aktif</p>
            </div>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-wrench text-amber-500 text-xs"></i>
            </div>
            <div>
                <p class="text-xl font-black text-amber-600 leading-none">{{ $maint }}</p>
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mt-0.5">Perawatan</p>
            </div>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-times-circle text-red-400 text-xs"></i>
            </div>
            <div>
                <p class="text-xl font-black text-red-500 leading-none">{{ $inactive }}</p>
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mt-0.5">Nonaktif</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== SEARCH + FILTER BAR ===== --}}
<div class="bg-white border border-slate-100 rounded-2xl shadow-sm px-5 py-4 mb-5 flex flex-col sm:flex-row gap-3 items-center">
    <div class="relative flex-1 w-full">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs" aria-hidden="true"></i>
        <input id="search-input"
               type="search"
               placeholder="Cari nama bus, plat nomor, atau rute..."
               oninput="filterTable(this.value)"
               class="w-full pl-10 pr-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-50 border border-slate-100 rounded-xl outline-none transition-all placeholder:text-slate-300"
               aria-label="Cari armada bus">
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        <select onchange="filterByStatus(this.value)"
                class="text-xs font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 outline-none cursor-pointer"
                aria-label="Filter berdasarkan status">
            <option value="">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="maintenance">Perawatan</option>
            <option value="inactive">Nonaktif</option>
        </select>
        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-2 hidden sm:block">
            {{ $buses->total() }} unit
        </span>
    </div>
</div>

{{-- ===== DATA TABLE ===== --}}
<section aria-label="Tabel daftar armada bus" class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">

    @forelse($buses as $bus)
    @php
        $isDriverMissing = is_null($bus->driver_id);
        $statusClass = match($bus->status) {
            'active'      => $isDriverMissing ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'pill-active',
            'maintenance' => 'pill-maintain',
            default       => 'pill-inactive',
        };
        $statusLabel = match($bus->status) {
            'active'      => $isDriverMissing ? 'Tanpa Sopir' : 'Aktif',
            'maintenance' => 'Perawatan',
            default       => 'Nonaktif',
        };
        $tripClass = match($bus->trip_status) {
            'jalan'     => 'trip-jalan',
            'standby'   => 'trip-standby',
            'istirahat' => 'trip-istirahat',
            default     => 'trip-standby',
        };
        $tripLabel = match($bus->trip_status) {
            'jalan'     => 'Sedang Jalan',
            'standby'   => 'Standby',
            'istirahat' => 'Istirahat',
            default     => 'Standby',
        };
        $fillPct = $bus->capacity > 0 ? round(($bus->bookings_count / $bus->capacity) * 100) : 0;
    @endphp

    <article class="bus-row row-enter border-b border-slate-50 last:border-0"
             data-name="{{ strtolower($bus->name) }}"
             data-plate="{{ strtolower($bus->plate_number) }}"
             data-route="{{ strtolower($bus->route) }}"
             data-status="{{ $bus->status }}"
             data-bus-id="{{ $bus->id }}"
             aria-label="Armada {{ $bus->name }}">

        <div class="flex items-center gap-4 px-6 py-4">

            {{-- Bus Number Avatar --}}
            <div class="flex-shrink-0">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-black text-xs border
                    {{ $bus->status === 'active' ? 'bg-[#1e3a5f]/8 border-[#1e3a5f]/15 text-[#1e3a5f]' : 'bg-slate-100 border-slate-200 text-slate-500' }}">
                    {{ str_pad($bus->bus_number ?? $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            {{-- Main Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="text-sm font-black text-slate-900 tracking-tight">{{ $bus->name }}</h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                    <span class="trip-status-badge inline-flex items-center px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $tripClass }}">
                        {{ $tripLabel }}
                    </span>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-[10px] font-black text-slate-500 font-mono tracking-wider">{{ $bus->plate_number }}</span>
                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                    <span class="text-[10px] text-slate-500 font-medium truncate max-w-xs">{{ $bus->route }}</span>
                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                    <span class="text-[10px] text-slate-500 font-medium">
                        <i class="far fa-clock mr-0.5 text-slate-300"></i>
                        {{ substr($bus->departure_time,0,5) }} – {{ substr($bus->arrival_time,0,5) }}
                    </span>
                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                    <span class="text-[10px] {{ $isDriverMissing ? 'text-rose-500 font-bold' : 'text-slate-500 font-medium' }}">
                        <i class="far fa-user mr-0.5 {{ $isDriverMissing ? 'text-rose-400' : 'text-slate-300' }}"></i>
                        {{ $bus->driver->name ?? 'Belum Ditugaskan' }}
                    </span>
                </div>
            </div>

            {{-- Capacity mini stat --}}
            <div class="hidden md:flex flex-col items-end gap-1 flex-shrink-0 w-24">
                <div class="flex items-baseline gap-1">
                    <span class="text-base font-black text-slate-700">{{ $bus->capacity }}</span>
                    <span class="text-[9px] text-slate-500 font-semibold">kursi</span>
                </div>
                <div class="cap-bar w-full">
                    <div class="cap-fill" style="width:{{ min($fillPct, 100) }}%"></div>
                </div>
                <span class="text-[8px] text-slate-500 font-bold">{{ $bus->bookings_count }} pemesanan</span>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 flex-shrink-0">
                {{-- View --}}
                <a href="{{ route('admin.buses.show', $bus) }}"
                   class="btn-action w-9 h-9 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 flex items-center justify-center text-slate-500 hover:text-[#1e3a5f]"
                   aria-label="Lihat detail {{ $bus->name }}"
                   title="Detail">
                    <i class="fas fa-eye text-[10px]"></i>
                </a>

                {{-- Edit --}}
                <a href="{{ route('admin.buses.edit', $bus) }}"
                   class="btn-action w-9 h-9 rounded-xl bg-blue-50 hover:bg-blue-500 border border-blue-100 hover:border-blue-500 flex items-center justify-center text-blue-500 hover:text-white"
                   aria-label="Edit data {{ $bus->name }}"
                   title="Edit">
                    <i class="fas fa-pen text-[10px]"></i>
                </a>

                {{-- Delete --}}
                <form action="{{ route('admin.buses.destroy', $bus) }}"
                      method="POST"
                      onsubmit="return confirmDelete(event, '{{ addslashes($bus->name) }}', '{{ $bus->status }}')"
                      class="inline-flex">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn-action w-9 h-9 rounded-xl bg-red-50 hover:bg-red-500 border border-red-100 hover:border-red-500 flex items-center justify-center text-red-400 hover:text-white"
                            aria-label="Hapus armada {{ $bus->name }}"
                            title="Hapus">
                        <i class="fas fa-trash text-[10px]"></i>
                    </button>
                </form>
            </div>
        </div>
    </article>

    @empty

    {{-- Empty State --}}
    <div class="py-24 text-center" role="status" aria-label="Tidak ada data armada">
        <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-5 border border-slate-100">
            <i class="fas fa-bus-slash text-2xl text-slate-300"></i>
        </div>
        <h3 class="text-base font-black text-slate-700 mb-1">Belum Ada Armada</h3>
        <p class="text-xs text-slate-500 font-medium max-w-xs mx-auto mb-6">
            Mulai tambahkan unit bus kampus pertama untuk sistem transportasi Kampus Non-Merdeka.
        </p>
        <a href="{{ route('admin.buses.create') }}"
           class="inline-flex items-center gap-2 bg-[#1e3a5f] text-white font-black px-5 py-3 rounded-2xl text-xs uppercase tracking-widest hover:bg-slate-900 transition-colors">
            <i class="fas fa-plus"></i> Tambah Armada Pertama
        </a>
    </div>

    @endforelse

</section>

{{-- No-result row (hidden by default, shown when search has no match) --}}
<div id="no-result"
     class="hidden py-16 text-center bg-white border border-slate-100 rounded-3xl mt-2"
     aria-live="polite">
    <i class="fas fa-search text-2xl text-slate-200 mb-3 block"></i>
    <p class="text-sm font-bold text-slate-500">Tidak ada hasil untuk pencarian ini</p>
</div>

{{-- ===== PAGINATION ===== --}}
@if($buses->hasPages())
<nav class="mt-6 flex items-center justify-between" aria-label="Navigasi halaman">
    <p class="text-xs text-slate-500 font-medium">
        Menampilkan {{ $buses->firstItem() }}–{{ $buses->lastItem() }} dari {{ $buses->total() }} armada
    </p>
    <div>
        {{ $buses->links() }}
    </div>
</nav>
@endif

@push('scripts')
<script src="{{ asset('js/bus-simulation.js') }}?v={{ filemtime(public_path('js/bus-simulation.js')) }}"></script>
<script>
    // ── Client-side filter ───────────────────────────────────────
    const rows     = document.querySelectorAll('article.bus-row');
    const noResult = document.getElementById('no-result');
    let currentQuery  = '';
    let currentStatus = '';

    function applyFilter() {
        let visible = 0;
        rows.forEach(row => {
            const matchQ = !currentQuery
                || row.dataset.name.includes(currentQuery)
                || row.dataset.plate.includes(currentQuery)
                || row.dataset.route.includes(currentQuery);
            const matchS = !currentStatus || row.dataset.status === currentStatus;
            const show   = matchQ && matchS;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        noResult.classList.toggle('hidden', visible > 0 || rows.length === 0);
    }

    function filterTable(val)      { currentQuery  = val.toLowerCase(); applyFilter(); }
    function filterByStatus(val)   { currentStatus = val;               applyFilter(); }

    // ── Confirm delete with SweetAlert if available ──────────────
    function confirmDelete(e, name, status) {
        if (status !== 'inactive') {
            e.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Ditolak!',
                    html: `Armada <strong>${name}</strong> tidak dapat dihapus karena berstatus <b>Aktif/Perawatan</b>. Anda harus menonaktifkannya terlebih dahulu.`,
                    icon: 'error',
                    confirmButtonColor: '#1e3a5f'
                });
            } else {
                alert(`Ditolak! Armada ${name} masih aktif/perawatan.`);
            }
            return false;
        }

        if (typeof Swal !== 'undefined') {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Armada?',
                html: `Unit <strong>${name}</strong> akan dihapus secara permanen dari sistem.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) e.target.closest('form').submit();
            });
            return false;
        }
        return confirm(`Hapus armada ${name}?`);
    }

    // ── Realtime Map Sync ────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const res = await fetch('/api/simulation/buses');
            const data = await res.json();
            if(window.BusSimulation) {
                BusSimulation.init(data.buses);
                
                setInterval(() => {
                    const positions = BusSimulation.getAllPositions();
                    rows.forEach(row => {
                        const busId = parseInt(row.dataset.busId);
                        const busState = positions.find(b => b.id === busId);
                        if(busState) {
                            const badge = row.querySelector('.trip-status-badge');
                            if(!badge) return;
                            
                            // Reset classes
                            badge.className = 'trip-status-badge inline-flex items-center px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest';
                            
                            if(busState.trip_status === 'jalan') {
                                badge.classList.add('bg-blue-100', 'text-blue-700', 'border', 'border-blue-200');
                                badge.textContent = 'Sedang Jalan';
                            } else if (busState.trip_status === 'istirahat' && busState.direction !== 'rest_gowa') {
                                badge.classList.add('bg-slate-100', 'text-slate-600', 'border', 'border-slate-200');
                                badge.textContent = 'Istirahat';
                            } else {
                                badge.classList.add('bg-amber-100', 'text-amber-700', 'border', 'border-amber-200');
                                badge.textContent = 'Standby';
                            }
                        }
                    });
                }, 1500); // Sinkron dengan 20x speed map
            }
        } catch(e) {
            console.error('Failed to init realtime sync:', e);
        }
    });
</script>
@endpush

@endsection