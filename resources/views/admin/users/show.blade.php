@extends('layouts.admin', ['view_name' => 'Passengers'])

@section('title', __('Detail Pengguna'))
@section('admin-content')

<div class="grid lg:grid-cols-3 gap-10">

    {{-- Profile card --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white shadow-sm border border-slate-200 p-8 text-center">
            <div class="w-32 h-32 bg-slate-900 rounded-none flex items-center justify-center text-white text-5xl font-black mx-auto mb-6 shadow-sm">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h1 class="font-black text-slate-900 text-2xl tracking-tighter leading-tight">{{ $user->name }}</h1>
            <div class="inline-flex items-center px-3 py-1 bg-slate-100 border border-slate-200 text-slate-700 rounded-sm text-[9px] font-black uppercase tracking-widest mt-2">
                {{ $user->roleNameDisplay() }}
            </div>

            <div class="mt-8 space-y-4">
                <div class="flex items-center gap-4 py-3 border-b border-slate-100">
                    <div class="w-8 h-8 flex items-center justify-center text-slate-400">
                        <i class="far fa-envelope text-lg"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ __('Email Address') }}</p>
                        <p class="text-sm font-bold text-slate-900 truncate w-full max-w-[180px]">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 py-3 border-b border-slate-100">
                    <div class="w-8 h-8 flex items-center justify-center text-slate-400">
                        <i class="far fa-calendar-alt text-lg"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ __('Joined Date') }}</p>
                        <p class="text-sm font-bold text-slate-900">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex flex-col gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center justify-center gap-3 bg-white border-2 border-slate-900 hover:bg-slate-900 hover:text-white text-slate-900 font-black py-3 rounded-none text-xs uppercase tracking-widest transition-colors shadow-sm">
                <i class="fas fa-arrow-left text-[10px]"></i>
                {{ __('Back to Database') }}
            </a>
        </div>
    </div>

    {{-- Stats & Booking history --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Stats Highlight --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 border-l-4 border-slate-900 shadow-sm flex flex-col">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('Total') }}</p>
                <p class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white p-5 border-l-4 border-slate-400 shadow-sm flex flex-col">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Success') }}</p>
                <p class="text-2xl font-black text-slate-700">{{ $stats['confirmed'] }}</p>
            </div>
            <div class="bg-white p-5 border-l-4 border-slate-400 shadow-sm flex flex-col">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Pending') }}</p>
                <p class="text-2xl font-black text-slate-700">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white p-5 border-l-4 border-slate-400 shadow-sm flex flex-col">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Failed') }}</p>
                <p class="text-2xl font-black text-slate-700">{{ $stats['cancelled'] }}</p>
            </div>
        </div>

        {{-- List --}}
        <section class="bg-white shadow-sm border border-slate-200">
            <div class="px-8 py-6 border-b-2 border-slate-900 bg-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="font-black text-slate-900 text-xl tracking-tighter">{{ __('Booking Activity') }}</h2>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">{{ __('Full transaction history') }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.15em] border-b border-slate-200 bg-white">
                            <th class="px-8 py-5">{{ __('Internal Code') }}</th>
                            <th class="px-8 py-5">{{ __('Bus Service') }}</th>
                            <th class="px-8 py-5">{{ __('Service Date') }}</th>
                            <th class="px-8 py-5 text-right">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5 font-mono text-[11px] font-bold text-slate-500">#{{ $booking->booking_code }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-sm bg-slate-100 text-slate-700 flex items-center justify-center border border-slate-200">
                                        <i class="fas fa-bus text-[10px]"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 leading-none">{{ $booking->bus->name }}</p>
                                        <p class="text-[10px] text-slate-500 font-semibold mt-1">{{ $booking->bus->route }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-bold tracking-tight">{{ $booking->booking_date->format('d M Y') }}</td>
                            <td class="px-8 py-5 text-right">
                                <span class="inline-flex px-2 py-1 bg-slate-100 border border-slate-300 text-slate-800 text-[9px] font-black tracking-widest uppercase">
                                    {{ strtoupper(__($booking->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <i class="fas fa-folder-open text-slate-300 text-3xl mb-4"></i>
                                <p class="text-sm font-bold text-slate-500">{{ __('No ticket records found for this user') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-200">
                    {{ $bookings->links() }}
                </div>
            @endif
        </section>
    </div>
</div>
@endsection