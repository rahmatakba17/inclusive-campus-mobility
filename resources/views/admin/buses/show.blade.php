@extends('layouts.admin', ['view_name' => 'Fleet Data'])

@section('title', $bus->name)
@section('admin-content')

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Bus Profile --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white shadow-sm border border-slate-200">
            <div class="h-44 bg-slate-900 flex items-center justify-center overflow-hidden border-b-4 border-slate-800">
                @if($bus->image)
                    <img src="{{ Storage::url($bus->image) }}" alt="{{ $bus->name }}" class="w-full h-full object-cover">
                @else
                    <div class="text-center">
                        <i class="fas fa-bus text-slate-700 text-6xl"></i>
                        <p class="text-slate-500 font-bold text-xs mt-3 uppercase tracking-widest">{{ $bus->plate_number }}</p>
                    </div>
                @endif
            </div>

            <div class="p-8 space-y-6">
                <div class="flex items-start justify-between flex-col gap-3">
                    <h1 class="font-black text-slate-900 text-2xl tracking-tighter leading-tight">{{ $bus->name }}</h1>
                    <span class="inline-flex px-3 py-1 bg-slate-100 border border-slate-200 text-slate-700 rounded-sm text-[9px] font-black uppercase tracking-widest">
                        {{ __($bus->status_badge) }}
                    </span>
                </div>

                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 flex items-center justify-center text-slate-500 bg-slate-50 border border-slate-100 shrink-0">
                            <i class="fas fa-id-card text-xs"></i>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[9px] font-black uppercase tracking-widest">{{ __('License Plate') }}</span>
                            <span class="text-slate-900 font-bold text-sm">{{ $bus->plate_number }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 flex items-center justify-center text-slate-500 bg-slate-50 border border-slate-100 shrink-0">
                            <i class="fas fa-route text-xs"></i>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[9px] font-black uppercase tracking-widest">{{ __('Route Area') }}</span>
                            <span class="text-slate-900 font-bold text-sm">{{ $bus->route }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 flex items-center justify-center text-slate-500 bg-slate-50 border border-slate-100 shrink-0">
                            <i class="fas fa-clock text-xs"></i>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[9px] font-black uppercase tracking-widest">{{ __('Operation Window') }}</span>
                            <span class="text-slate-900 font-bold text-sm">{{ $bus->departure_time }} – {{ $bus->arrival_time }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 flex items-center justify-center text-slate-500 bg-slate-50 border border-slate-100 shrink-0">
                            <i class="fas fa-users text-xs"></i>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[9px] font-black uppercase tracking-widest">{{ __('Capacity') }}</span>
                            <span class="text-slate-900 font-bold text-sm">{{ $bus->capacity }} {{ __('tickets') }}</span>
                        </div>
                    </div>
                </div>

                @if($bus->description)
                <div class="pt-6 border-t border-slate-100">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5">{{ __('Review') }}</p>
                    <p class="text-sm text-slate-600 font-medium leading-relaxed">{{ $bus->description }}</p>
                </div>
                @endif
            </div>

            <div class="flex gap-3 px-8 pb-8 pt-4">
                <a href="{{ route('admin.buses.edit', $bus) }}"
                   class="flex-1 text-center bg-slate-900 hover:bg-slate-700 text-white font-black py-3 text-[10px] uppercase tracking-widest transition-colors shadow-sm">
                    <i class="fas fa-edit mr-1"></i>{{ __('Edit') }}
                </a>
                <a href="{{ route('admin.buses.index') }}"
                   class="flex-1 text-center bg-white border-2 border-slate-900 hover:bg-slate-900 hover:text-white text-slate-900 font-black py-3 text-[10px] uppercase tracking-widest transition-colors shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i>{{ __('Back') }}
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="bg-white shadow-sm border border-slate-200 p-8">
            <h2 class="font-black text-slate-900 text-xl tracking-tighter mb-6">{{ __('Booking Activity') }}</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white border-l-4 border-slate-900 p-4 shadow-sm flex flex-col">
                    <div class="text-2xl font-black text-slate-900">{{ $stats['total_bookings'] }}</div>
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Total') }}</div>
                </div>
                <div class="bg-white border-l-4 border-slate-400 p-4 shadow-sm flex flex-col">
                    <div class="text-2xl font-black text-slate-700">{{ $stats['confirmed'] }}</div>
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Success') }}</div>
                </div>
                <div class="bg-white border-l-4 border-slate-400 p-4 shadow-sm flex flex-col">
                    <div class="text-2xl font-black text-slate-700">{{ $stats['pending'] }}</div>
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Pending') }}</div>
                </div>
                <div class="bg-white border-l-4 border-slate-400 p-4 shadow-sm flex flex-col">
                    <div class="text-2xl font-black text-slate-700">{{ $stats['cancelled'] }}</div>
                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ __('Voided') }}</div>
                </div>
            </div>
            <div class="mt-6 bg-slate-50 border border-slate-200 p-6 text-center shadow-sm">
                <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                    <i class="fas fa-gift mr-1"></i>{{ __('Total Tip Masuk') }}
                </div>
                <div class="text-3xl font-black text-slate-900 tracking-tighter">Rp{{ number_format($stats['total_tips'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Booking List --}}
    <div class="lg:col-span-2 space-y-6">

        <section class="bg-white shadow-sm border border-slate-200">
            <div class="px-8 py-6 border-b-2 border-slate-900 bg-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="font-black text-slate-900 text-xl tracking-tighter">{{ __('Recent Bookings') }}</h2>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] border-b border-slate-200 bg-white">
                            <th class="px-8 py-5">{{ __('Passenger') }}</th>
                            <th class="px-8 py-5">{{ __('Scheduled Date') }}</th>
                            <th class="px-8 py-5">{{ __('Internal Code') }}</th>
                            <th class="px-8 py-5 text-right">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($bus->bookings as $booking)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 bg-slate-200 text-slate-600 font-black rounded-none flex items-center justify-center flex-shrink-0 text-xs">
                                        {{ $booking->passenger_avatar }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900">{{ $booking->passenger_name }}</p>
                                        <p class="text-[10px] text-slate-500 font-bold mt-0.5">{{ $booking->passenger_contact }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-xs text-slate-600 font-bold tracking-tight">{{ $booking->booking_date->format('d M Y') }}</td>
                            <td class="px-8 py-5 font-mono text-[11px] font-bold text-slate-500">#{{ $booking->booking_code }}</td>
                            <td class="px-8 py-5 text-right">
                                <span class="inline-flex px-2 py-1 bg-slate-100 border border-slate-300 text-slate-800 text-[9px] font-black tracking-widest uppercase">
                                    {{ __($booking->status_badge) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <i class="fas fa-inbox text-slate-300 text-3xl mb-4"></i>
                                <p class="text-sm font-bold text-slate-500">{{ __('No ticket records found for this bus') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Anonymous Tip List --}}
        <section class="bg-white shadow-sm border border-slate-200">
            <div class="px-8 py-6 border-b-2 border-slate-900 bg-slate-50 flex items-center justify-between">
                <h2 class="font-black text-slate-900 text-xl tracking-tighter">
                    <i class="fas fa-hand-holding-usd text-slate-500 mr-2"></i>{{ __('Riwayat Tip Anonim') }}
                </h2>
                <span class="bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 shadow-sm">
                    <i class="fas fa-eye-slash mr-1"></i>{{ __('Identitas Disembunyikan') }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] border-b border-slate-200 bg-white">
                            <th class="px-8 py-5">{{ __('Date') }}</th>
                            <th class="px-8 py-5">{{ __('Nominal') }}</th>
                            <th class="px-8 py-5 text-right">{{ __('Status Category') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($bus->tips()->latest()->get() as $tip)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5 text-xs text-slate-600 font-bold tracking-tight">{{ $tip->created_at->format('d M Y H:i') }}</td>
                            <td class="px-8 py-5 text-sm font-black text-slate-900 tracking-tighter">Rp{{ number_format($tip->amount, 0, ',', '.') }}</td>
                            <td class="px-8 py-5 text-right">
                                <span class="inline-flex px-2 py-1 bg-slate-100 border border-slate-300 text-slate-800 text-[9px] font-black tracking-widest uppercase">
                                    {{ __('Civitas (Disamarkan)') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-20 text-center">
                                <i class="fas fa-box-open text-slate-300 text-3xl mb-4"></i>
                                <p class="text-sm font-bold text-slate-500">{{ __('Belum ada tip yang tercatat untuk bus ini.') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Riwayat Laporan Armada --}}
        <section class="bg-white shadow-sm border border-slate-200">
            <div class="px-8 py-6 border-b-2 border-slate-900 bg-slate-50 flex items-center justify-between">
                <h2 class="font-black text-slate-900 text-xl tracking-tighter">
                    <i class="fas fa-clipboard-check text-slate-500 mr-2"></i>{{ __('Riwayat Laporan Armada') }}
                </h2>
                <span class="bg-blue-100 text-blue-700 border border-blue-200 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded">
                    Sopir & Admin
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] border-b border-slate-200 bg-white">
                            <th class="px-8 py-5 w-40">Waktu</th>
                            <th class="px-8 py-5 w-48">Pelapor & Tipe</th>
                            <th class="px-8 py-5 w-40">Kondisi Fisik</th>
                            <th class="px-8 py-5">Catatan Teknis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($bus->reports()->latest()->get() as $report)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5 text-xs text-slate-600 font-bold tracking-tight align-top">
                                    {{ $report->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-8 py-5 align-top">
                                    <div class="text-sm font-black text-slate-900">{{ $report->user ? $report->user->name : 'Sistem' }}</div>
                                    <div class="inline-flex px-1.5 py-0.5 mt-1 {{ $report->type === 'maintenance' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-700 border-slate-200' }} border text-[8px] uppercase font-black tracking-widest rounded-sm">
                                        {{ $report->type === 'maintenance' ? 'Laporan Perawatan' : 'Inspeksi Harian' }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 align-top">
                                    @if($report->condition === 'good')
                                        <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 border border-emerald-200 text-[9px] font-black uppercase tracking-widest rounded"><i class="fas fa-check-circle mr-1"></i> Normal</span>
                                    @elseif($report->condition === 'needs_maintenance')
                                        <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 border border-amber-200 text-[9px] font-black uppercase tracking-widest rounded"><i class="fas fa-tools mr-1"></i> Perlu Servis</span>
                                    @elseif($report->condition === 'damaged')
                                        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 border border-red-200 text-[9px] font-black uppercase tracking-widest rounded"><i class="fas fa-car-crash mr-1"></i> Kandang</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-700 border border-slate-200 text-[9px] font-black uppercase tracking-widest rounded">{{ $report->condition }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-xs text-slate-600 leading-relaxed min-w-[250px]">
                                    {{ $report->notes ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-slate-50 border border-slate-200 mb-4">
                                        <i class="fas fa-clipboard text-slate-300 text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500">{{ __('Belum ada riwayat laporan inspeksi maupun perawatan.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection