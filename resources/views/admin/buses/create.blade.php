@extends('layouts.admin')

@section('title', 'Tambah Bus')
@section('admin-content')

<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
            <div class="w-12 h-12 gradient-header rounded-xl flex items-center justify-center">
                <i class="fas fa-bus text-white text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Form Tambah Bus</h3>
                <p class="text-sm text-gray-500">Isi semua data bus dengan lengkap</p>
            </div>
        </div>

        <form action="{{ route('admin.buses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4" x-data="{ status: '{{ old('status', 'active') }}' }">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Bus <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="cth: Bus Kampus Non-Merdeka 01"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm">
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sopir Bus (Opsional)</label>
                    <select name="driver_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm">
                        <option value="">-- Belum Ditugaskan --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Polisi <span class="text-red-500">*</span></label>
                    <input type="text" name="plate_number" value="{{ old('plate_number') }}" required
                           placeholder="cth: DD 1001 BK"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kapasitas Kursi <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" id="capacity"
                           class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-2 text-sm text-gray-500 @error('capacity') border-red-500 @enderror"
                           value="20" readonly required>
                    <p class="text-[11px] text-gray-400 mt-1">
                        <i class="fas fa-lock mr-1"></i>Kapasitas armada dikunci stabil 20 untuk UI Visual Seat Map (16 Duduk + 4 Berdiri).
                    </p>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rute <span class="text-red-500">*</span></label>
                    <input type="text" name="route" value="{{ old('route') }}" required
                           placeholder="cth: Tamalanrea → Gowa (Sungguminasa)"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Berangkat <span class="text-red-500">*</span></label>
                    <input type="time" name="departure_time" value="{{ old('departure_time') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Tiba (Estimasi) <span class="text-red-500">*</span></label>
                    <input type="time" name="arrival_time" value="{{ old('arrival_time') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi / Fasilitas</label>
                    <textarea name="description" rows="3"
                              placeholder="Deskripsi bus, fasilitas, informasi rute, dll."
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm resize-none">{{ old('description') }}</textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required x-model="status"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm">
                        <option value="active">Aktif</option>
                        <option value="maintenance">Perawatan</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>

                    <div x-show="status === 'maintenance'" x-cloak class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
                        <label class="block text-sm font-bold text-amber-900 mb-1.5"><i class="fas fa-tools mr-1"></i> Catatan Laporan Perawatan <span class="text-red-500">*</span></label>
                        <p class="text-xs text-amber-700 mb-2 font-medium">Laporan ini wajib diisi dan akan dicatat ke dalam histori riwayat pemeriksaan armada sebagai referensi teknisi.</p>
                        <textarea name="maintenance_notes" rows="3" placeholder="Sebutkan analisa kerusakan, komponen yang diganti, atau rincian perbaikan yang sedang dilakukan..." 
                                  :required="status === 'maintenance'"
                                  class="w-full px-4 py-3 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 bg-white text-sm resize-none">{{ old('maintenance_notes') }}</textarea>
                    </div>
                </div>

                <div x-data="{ 
                        photoName: null, 
                        photoPreview: null,
                        updatePreview(event) {
                            const file = event.target.files[0];
                            if(!file) return;
                            this.photoName = file.name;
                            const reader = new FileReader();
                            reader.onload = (e) => { this.photoPreview = e.target.result; };
                            reader.readAsDataURL(file);
                        }
                    }">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Bus (Real-time Preview)</label>
                    <div class="flex items-center gap-6 mt-2">
                        <!-- Preview Box -->
                        <div class="w-32 h-32 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden shrink-0 shadow-inner group">
                            <template x-if="!photoPreview">
                                <div class="text-center p-4">
                                    <i class="fas fa-image text-3xl text-gray-300 mb-2 group-hover:text-orange-400 transition-colors"></i>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">No Photo</p>
                                </div>
                            </template>
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                        </div>
                        
                        <!-- Upload Input -->
                        <div class="flex-1">
                            <input type="file" name="image" id="imageInput" accept="image/*" @change="updatePreview" class="hidden">
                            <button type="button" onclick="document.getElementById('imageInput').click()" 
                                    class="bg-white border border-gray-200 hover:border-orange-500 text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                                <i class="fas fa-upload text-orange-500"></i> Pilih Foto...
                            </button>
                            <p class="mt-2 text-xs text-gray-500 font-medium break-all flex items-center gap-1">
                                <i class="fas fa-file-image text-gray-400" x-show="photoName"></i> 
                                <span x-text="photoName ? photoName : 'Format didukung: JPG, PNG, WEBP (Max 2MB)'"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                <button type="submit"
                        class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition-all text-sm">
                    <i class="fas fa-save"></i> Simpan Bus
                </button>
                <a href="{{ route('admin.buses.index') }}"
                   class="text-gray-600 hover:text-gray-800 font-medium px-4 py-3 rounded-xl hover:bg-gray-100 transition-colors text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection