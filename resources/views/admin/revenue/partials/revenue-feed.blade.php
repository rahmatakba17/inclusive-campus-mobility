@forelse($revenues as $r)
<div class="revenue-item-row flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-50">
    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-xs">
        {{ substr($r->passenger_name, 0, 1) }}
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-xs font-bold text-slate-800 truncate">{{ $r->passenger_name }}</p>
        <p class="text-[10px] text-slate-400 font-semibold">{{ $r->booking_date->format('d M y') }} &bull; Bus {{ $r->bus->bus_number }}</p>
    </div>
    <div class="text-right">
        <p class="text-sm font-black text-emerald-600 truncate">+ Rp{{ number_format($r->price, 0, ',', '.') }}</p>
        <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $r->payment_method ?? 'CASH' }}</p>
    </div>
</div>
@empty
<div class="text-center py-6">
    <i class="fas fa-inbox text-3xl text-slate-200 mb-3"></i>
    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Belum ada pendapatan pada periode ini.</p>
</div>
@endforelse
