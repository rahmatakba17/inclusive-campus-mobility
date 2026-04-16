@extends('layouts.admin')

@section('title', 'Detail Pemesanan')
@section('admin-content')

<div class="max-w-2xl mx-auto">
    <div class="card">

        {{-- Header Tiket --}}
        <div class="gradient-header rounded-xl p-6 mb-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-200 text-sm mb-1">Kode Pemesanan</p>
                    <p class="font-mono text-xl font-bold tracking-wider">{{ $booking->booking_code }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                        {{ $booking->status === 'confirmed' ? 'bg-green-400/20 text-green-100 border border-green-400/30' : '' }}
                        {{ $booking->status === 'pending'   ? 'bg-yellow-400/20 text-yellow-100 border border-yellow-400/30' : '' }}
                        {{ $booking->status === 'cancelled' ? 'bg-red-400/20 text-red-100 border border-red-400/30' : '' }}">
                        {{ $booking->status_badge }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Detail --}}
        <div class="space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Informasi Penumpang</h4>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <span class="text-orange-600 font-bold">{{ $booking->passenger_avatar }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $booking->passenger_name }}</p>
                                <p class="text-gray-500 text-xs">{{ $booking->passenger_contact }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Informasi Bus</h4>
                    <div class="space-y-1.5">
                        <p class="font-semibold text-gray-800 text-sm">{{ $booking->bus->name }}</p>
                        <p class="text-xs text-gray-500"><i class="fas fa-id-card mr-1"></i>{{ $booking->bus->plate_number }}</p>
                        <p class="text-xs text-gray-500"><i class="fas fa-route mr-1"></i>{{ $booking->bus->route }}</p>
                        <p class="text-xs text-gray-500"><i class="fas fa-clock mr-1"></i>{{ $booking->bus->departure_time }} – {{ $booking->bus->arrival_time }}</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal Perjalanan</p>
                    <p class="font-semibold text-gray-800">{{ $booking->booking_date->format('l, d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Dipesan Pada</p>
                    <p class="font-semibold text-gray-800">{{ $booking->created_at->format('d M Y H:i') }}</p>
                </div>
                @if($booking->notes)
                <div class="col-span-2">
                    <p class="text-xs text-gray-500 mb-1">Catatan</p>
                    <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $booking->notes }}</p>
                </div>
                @endif
            </div>

            {{-- Update Status --}}
            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Update Status Pemesanan</h4>
                <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:ring-2 focus:ring-orange-500 outline-none">
                        <option value="pending"   {{ $booking->status === 'pending'   ? 'selected' : '' }}>⏳ Menunggu</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>✅ Konfirmasi</option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>❌ Batalkan</option>
                    </select>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition-colors">
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-5 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-800 font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke daftar pemesanan
            </a>
        </div>
    </div>
</div>
@endsection