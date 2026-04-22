@extends('layouts.admin', ['view_name' => 'Drivers'])

@section('title', __('Edit Data Sopir'))
@section('admin-content')

<div class="max-w-2xl bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-12 py-10 border-b border-slate-50 bg-slate-50/20">
        <h3 class="font-black text-[#1e3a5f] text-2xl tracking-tighter">{{ __('Edit Informasi Sopir') }}</h3>
        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1.5">{{ __('Ubah data akun operasional') }}</p>
    </div>

    <form action="{{ route('admin.drivers.update', $driver) }}" method="POST" class="p-10 space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-xs font-black text-slate-500 tracking-widest uppercase mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $driver->name) }}" required
                   class="w-full px-5 py-3 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-[#c41e3a] focus:border-[#c41e3a]">
            @error('name')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-black text-slate-500 tracking-widest uppercase mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $driver->email) }}" required
                   class="w-full px-5 py-3 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-[#c41e3a] focus:border-[#c41e3a]">
            @error('email')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="pt-4 border-t border-slate-100">
            <p class="text-[10px] font-black tracking-widest text-[#c41e3a] uppercase mb-4"><i class="fas fa-lock mr-2"></i>Ubah Password (Opsional)</p>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-slate-500 tracking-widest uppercase mb-2">Password Baru</label>
                    <input type="password" name="password" minlength="8" placeholder="Kosongkan jika tidak ingin diubah"
                           class="w-full px-5 py-3 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-[#c41e3a] focus:border-[#c41e3a]">
                    @error('password')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 tracking-widest uppercase mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" minlength="8"
                           class="w-full px-5 py-3 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-[#c41e3a] focus:border-[#c41e3a]">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
            <a href="{{ route('admin.drivers.index') }}" class="px-6 py-3 font-bold text-slate-500 hover:bg-slate-50 rounded-2xl transition-all text-sm">Batal</a>
            <button type="submit" class="bg-[#1e3a5f] hover:bg-navy-700 text-white px-8 py-3 rounded-2xl font-black tracking-wide shadow-md transition-all text-sm">
                Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection
