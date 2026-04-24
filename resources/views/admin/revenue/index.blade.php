@extends('layouts.admin', ['view_name' => 'Laporan Pemasukan'])

@section('title', 'Laporan Pemasukan')

@section('admin-content')

<div x-data="revenueManager()" x-init="startPolling()">

<div class="mb-8 flex flex-col xl:flex-row xl:justify-between items-start xl:items-end gap-6 xl:gap-0">
    <div>
        <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3 flex-wrap">
            Financial Overview
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-[9px] font-black uppercase tracking-widest flex-shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Real-Time
            </span>
        </h2>
        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Laporan Pemasukan Tiket Bus Kampus</p>
    </div>
    
    <div class="flex flex-col sm:flex-row flex-wrap gap-4 w-full xl:w-auto">
        <!-- Filter Form -->
        <form id="revenueFilterForm" x-data="{ selectedPeriod: '{{ $period }}' }" method="GET" action="{{ route('admin.revenue.index') }}" class="flex flex-wrap sm:flex-nowrap gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100 items-center w-full sm:w-auto">
            
            <select x-model="selectedPeriod" name="period" aria-label="Pilih Periode Laporan" class="px-4 py-2 bg-slate-50 text-slate-700 font-bold text-sm rounded-xl border-none ring-0 outline-none cursor-pointer flex-1 sm:flex-none">
                <option value="yearly">Tahunan</option>
                <option value="monthly">Bulanan</option>
                <option value="weekly">Mingguan</option>
                <option value="daily">Harian</option>
            </select>
            
            <select x-show="selectedPeriod === 'yearly'" x-cloak :disabled="selectedPeriod !== 'yearly'" name="year" aria-label="Pilih Tahun" class="px-4 py-2 bg-slate-50 text-slate-700 font-bold text-sm rounded-xl border-none ring-0 outline-none cursor-pointer flex-1 sm:flex-none sm:min-w-[120px]">
                @for($y = date('Y') + 1; $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            
            <input x-show="selectedPeriod === 'monthly'" x-cloak :disabled="selectedPeriod !== 'monthly'" type="month" name="month" aria-label="Pilih Bulan" value="{{ $month }}" class="px-4 py-2 bg-slate-50 text-slate-700 font-bold text-sm rounded-xl border-none ring-0 outline-none flex-1 sm:flex-none sm:min-w-[140px]">
            
            <input x-show="selectedPeriod === 'weekly'" x-cloak :disabled="selectedPeriod !== 'weekly'" type="week" name="week" aria-label="Pilih Minggu" value="{{ $week }}" class="px-4 py-2 bg-slate-50 text-slate-700 font-bold text-sm rounded-xl border-none ring-0 outline-none flex-1 sm:flex-none sm:min-w-[160px]">
            
            <input x-show="selectedPeriod === 'daily'" x-cloak :disabled="selectedPeriod !== 'daily'" type="date" name="date" aria-label="Pilih Tanggal" value="{{ $date }}" class="px-4 py-2 bg-slate-50 text-slate-700 font-bold text-sm rounded-xl border-none ring-0 outline-none flex-1 sm:flex-none sm:min-w-[140px]">
            
            <button type="submit" aria-label="Terapkan Filter" class="w-10 h-10 bg-[#1e3a5f] hover:bg-slate-800 text-white rounded-xl flex items-center justify-center transition-colors flex-shrink-0">
                <i class="fas fa-filter text-sm"></i>
            </button>
        </form>

        <button x-data x-on:click="
            const form = document.getElementById('revenueFilterForm');
            const url = new URL('{{ route('admin.revenue.print') }}', window.location.origin);
            new FormData(form).forEach((val, key) => url.searchParams.append(key, val));
            window.open(url.toString(), '_blank');
        " class="px-6 py-2 h-14 sm:h-auto bg-emerald-500 hover:bg-emerald-600 text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2 transition-all cursor-pointer flex-shrink-0 w-full sm:w-auto">
            <i class="fas fa-print"></i> Cetak PDF
        </button>
    </div>
</div>

<!-- Stats ROW -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-[#1a2332] rounded-2xl p-6 text-white border border-[#2a364a] flex flex-col justify-center relative overflow-hidden">
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-1">Total Pemasukan</p>
        <h3 class="text-3xl font-black tracking-tight text-white">Rp <span x-text="stats.total_revenue.toLocaleString('id-ID')">{{ number_format($stats['total_revenue'], 0, ',', '.') }}</span></h3>
        <p class="text-slate-500 text-xs mt-3 font-medium"><i class="fas fa-ticket-alt mr-1 text-slate-500"></i> <span x-text="stats.total_paid_tickets" class="text-white font-bold">{{ $stats['total_paid_tickets'] }}</span> Tiket Berbayar</p>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-2">
            <div class="text-slate-500 text-sm"><i class="fas fa-qrcode"></i></div>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Via QRIS</p>
        </div>
        <h3 class="text-2xl font-black text-slate-800 tracking-tight">Rp <span x-text="stats.total_qris.toLocaleString('id-ID')">{{ number_format($stats['total_qris'], 0, ',', '.') }}</span></h3>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-2">
            <div class="text-slate-500 text-sm"><i class="fas fa-id-card"></i></div>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Via E-Toll</p>
        </div>
        <h3 class="text-2xl font-black text-slate-800 tracking-tight">Rp <span x-text="stats.total_etoll.toLocaleString('id-ID')">{{ number_format($stats['total_etoll'], 0, ',', '.') }}</span></h3>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 flex flex-col justify-center">
        <div class="flex items-center gap-3 mb-2">
            <div class="text-slate-500 text-sm"><i class="fas fa-users"></i></div>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Tiket Gratis (Civitas)</p>
        </div>
        <h3 class="text-2xl font-black text-slate-800 tracking-tight"><span x-text="stats.total_free">{{ $stats['total_free'] }}</span> <span class="text-sm text-slate-500 font-bold">Tiket</span></h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- CHART -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-8">
        <h3 class="font-black text-slate-800 tracking-tight mb-6" x-text="chartTitle">{{ $chartTitle }}</h3>
        <div class="w-full h-72">
            <canvas id="revenueChart" role="img" aria-label="Grafik visualisasi tren pemasukan tiket bus"></canvas>
        </div>
    </div>

    <div class="space-y-6">
        <!-- REVENUE FEED -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col h-[400px] relative overflow-hidden">
            <!-- Polling Indicator -->
            <div x-show="isFetching" class="absolute top-0 left-0 w-full h-1 bg-slate-50 overflow-hidden">
                <div class="h-full bg-[#1a2332] w-1/3 animate-[slide_1s_ease-in-out_infinite]"></div>
            </div>

            <div class="flex justify-between items-start mb-4 gap-4">
                <div>
                    <h3 class="font-black text-slate-800 tracking-tight mb-1">Riwayat Pembayaran</h3>
                    <p class="text-[10px] text-slate-500 font-bold tracking-widest uppercase">Pemasukan Berbayar</p>
                </div>
                <div class="relative w-36 flex-shrink-0">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500 text-xs" aria-hidden="true"></i>
                    <input type="text" x-model="searchQuery" @input="applySearch" placeholder="Cari..." aria-label="Cari riwayat pembayaran berdasarkan nama bus"
                           class="w-full text-xs font-bold text-slate-700 pl-8 pr-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-[#1a2332] bg-slate-50 transition-colors shadow-sm">
                </div>
            </div>
            
            <div x-ref="feedContainer" class="flex-1 overflow-y-auto space-y-3 custom-scrollbar pr-2" x-html="revenuesHtml">
                <!-- Rendered by Alpine / Blade initial -->
            </div>
        </div>

        <!-- TIP FEED -->
        <div x-data="tipFeed()" x-init="startPolling()" class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col h-[320px] relative overflow-hidden">
            <div x-show="isFetching" class="absolute top-0 left-0 w-full h-1 bg-slate-50 overflow-hidden">
                <div class="h-full bg-slate-800 w-1/3 animate-[slide_1s_ease-in-out_infinite]"></div>
            </div>

            <h3 class="font-black text-slate-800 tracking-tight mb-1">Notifikasi Tip</h3>
            <p class="text-[10px] text-slate-500 font-bold tracking-widest uppercase mb-6 flex items-center gap-2">
                Log Rahasia
            </p>
            
            <div class="flex-1 overflow-y-auto space-y-3 custom-scrollbar pr-2">
                <template x-for="tip in tips" :key="tip.id">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-500 font-black text-xs">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate" x-text="tip.bus_name"></p>
                            <p class="text-[10px] text-slate-500 font-semibold" x-text="tip.time"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-amber-600 truncate" x-text="'+ Rp' + tip.amount.toLocaleString('id-ID')"></p>
                        </div>
                    </div>
                </template>
                <div x-show="tips.length === 0" class="text-center py-6">
                    <i class="fas fa-inbox text-3xl text-slate-200 mb-3"></i>
                    <p class="text-xs text-slate-500 font-bold">Belum ada tip hari ini</p>
                </div>
            </div>
        </div>
    </div>
</div>

</div> <!-- end x-data -->

<script src="{{ asset('vendor/js/chart.min.js') }}"></script>

<style>
@keyframes slide {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(300%); }
}
</style>

<script>
let revenueChartInstance = null; // Global reference for Chart

document.addEventListener('alpine:init', () => {
    Alpine.data('revenueManager', () => ({
        stats: @json($stats),
        chartTitle: {!! json_encode($chartTitle) !!},
        revenuesHtml: `{!! addslashes(view('admin.revenue.partials.revenue-feed', compact('revenues'))->render()) !!}`,
        isFetching: false,
        searchQuery: '',
        applySearch() {
            const term = this.searchQuery.toLowerCase();
            const container = this.$refs.feedContainer;
            if(!container) return;
            const items = container.querySelectorAll('.revenue-item-row');
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(term) ? '' : 'none';
            });
        },
        startPolling() {
            // Poll for updates every 10 seconds
            setInterval(() => this.fetchUpdate(), 10000);
        },
        async fetchUpdate() {
            this.isFetching = true;
            try {
                const url = new URL(window.location.href);
                const res = await fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if(res.ok) {
                    const data = await res.json();
                    
                    // Update reactivity
                    this.stats = data.stats;
                    this.chartTitle = data.chartTitle;
                    
                    // Update raw HTML feed safely
                    this.revenuesHtml = data.revenues;
                    
                    // Reapply search filter immediately after DOM updates
                    this.$nextTick(() => { this.applySearch(); });
                    
                    // Update Chart softly without reload flash
                    if(revenueChartInstance) {
                        revenueChartInstance.data.labels = data.labels;
                        revenueChartInstance.data.datasets[0].data = data.totals;
                        revenueChartInstance.update();
                    }
                }
            } catch(e) {} finally {
                setTimeout(() => { this.isFetching = false; }, 500); // Visual delay for indicator
            }
        }
    }));

    Alpine.data('tipFeed', () => ({
        tips: [],
        isFetching: false,
        startPolling() {
            this.fetchTips();
            setInterval(() => this.fetchTips(), 10000);
        },
        async fetchTips() {
            this.isFetching = true;
            try {
                const res = await fetch('/api/admin/tips');
                if(res.ok) {
                    const data = await res.json();
                    this.tips = data.tips;
                }
            } catch(e) {} finally {
                setTimeout(() => { this.isFetching = false; }, 500);
            }
        }
    }));
});

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Gradien menawan untuk Pemasukan
    const gradientFill = ctx.createLinearGradient(0, 0, 0, 300);
    gradientFill.addColorStop(0, 'rgba(99, 102, 241, 0.4)'); // Indigo 500
    gradientFill.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

    window.revenueChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Revenue (Rp)',
                data: {!! json_encode($totals) !!},
                borderColor: '#6366f1',
                backgroundColor: gradientFill,
                borderWidth: 4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#6366f1',
                pointBorderWidth: 3,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Smooth curve
            }]
        },
        options: {
            animation: {
                duration: 800,
            },
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, family: "'Inter', sans-serif" },
                    bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { size: 11, family: "'Inter', sans-serif" }, color: '#94a3b8' }
                },
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#f1f5f9', drawTicks: false },
                    ticks: {
                        font: { size: 11, family: "'Inter', sans-serif" },
                        color: '#94a3b8',
                        padding: 10,
                        callback: function(value) {
                            if (value === 0) return '0';
                            return value >= 1000 ? (value / 1000) + 'k' : value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
