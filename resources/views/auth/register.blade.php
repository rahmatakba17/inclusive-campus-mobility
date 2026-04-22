@extends('layouts.app')

@section('title', __('Daftar Akun Baru'))

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden"
     style="background: linear-gradient(135deg, #0f2137 0%, #1e3a5f 40%, #821326 100%);">
    
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-red-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20"></div>

    <div class="w-full max-w-xl z-10 transition-all duration-300">
        {{-- Language Switcher --}}
        <div class="flex justify-end mb-4">
            <div class="flex items-center bg-white/10 backdrop-blur-md p-1 rounded-2xl border border-white/20 shadow-inner gap-0.5">
                <a href="{{ route('lang.switch', 'id') }}"
                   class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ App::getLocale() === 'id' ? 'bg-white text-[#c41e3a] shadow-md' : 'text-white/60 hover:text-white' }}">ID</a>
                <a href="{{ route('lang.switch', 'en') }}"
                   class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ App::getLocale() === 'en' ? 'bg-white text-[#c41e3a] shadow-md' : 'text-white/60 hover:text-white' }}">EN</a>
            </div>
        </div>

        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden animate-slide-up border border-white/20">
            
            <div class="p-8">
                {{-- Header --}}
                <div class="text-center mb-6">
                    <div class="w-20 h-20 gradient-header rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg transform hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-user-plus text-white text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('Buat Akun Baru') }}</h2>
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">{!! __('Bergabunglah dengan Bus Kampus Non-Merdeka<br>dan pantau perjalanan Anda secara real-time.') !!}</p>
                </div>

                {{-- Hint / Petunjuk --}}
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-2xl p-4 flex gap-4 animate-fade-in shadow-sm transform transition hover:-translate-y-1 hover:shadow-md">
                    <div class="mt-1">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-800 text-sm mb-1">{{ __('Informasi Tipe Akun') }}</h4>
                        <div class="text-xs text-blue-700 space-y-1.5">
                            <p class="flex items-start gap-1.5">
                                <i class="fas fa-check-circle text-blue-500 mt-0.5"></i>
                                <span>{{ __('Gunakan email') }} <strong class="bg-blue-100 px-1 rounded">@kampus-non-merdeka.ac.id</strong> {{ __('untuk mendaftar sebagai') }} <strong>{{ __('Civitas Akademika') }}</strong> {{ __('(Gratis)') }}.</span>
                            </p>
                            <p class="flex items-start gap-1.5">
                                <i class="fas fa-check-circle text-blue-500 mt-0.5"></i>
                                <span>{{ __('Email selain itu akan terdaftar sebagai akun') }} <strong>{{ __('Umum') }}</strong> {{ __('(Fitur E-Payment)') }}.</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Errors --}}
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex gap-3 animate-fade-in shadow-sm text-sm">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    <div>
                        <ul class="text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('register') }}" method="POST" class="space-y-5 relative">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5 ml-1">{{ __('Nama Lengkap') }}</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-red-600">
                                <i class="fas fa-user text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="{{ __('Contoh: Budi Santoso') }}"
                                   class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm shadow-sm hover:border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5 ml-1">{{ __('Alamat Email') }}</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-red-600">
                                <i class="fas fa-envelope text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="anda@kampus-non-merdeka.ac.id"
                                   class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm shadow-sm hover:border-gray-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5 ml-1">{{ __('Password') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                                </div>
                                <input type="password" name="password" required id="pwd"
                                       placeholder="{{ __('Minimal 8 karakter') }}"
                                       class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm shadow-sm hover:border-gray-300">
                                <button type="button" onclick="togglePwd('pwd', 'eye1')" tabindex="-1"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition-colors">
                                    <i class="fas fa-eye" id="eye1"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5 ml-1">{{ __('Konfirmasi Sandi') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-shield-alt text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                                </div>
                                <input type="password" name="password_confirmation" required id="pwd2"
                                       placeholder="{{ __('Ulangi password') }}"
                                       class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition-all bg-gray-50 focus:bg-white text-sm shadow-sm hover:border-gray-300">
                                <button type="button" onclick="togglePwd('pwd2', 'eye2')" tabindex="-1"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition-colors">
                                    <i class="fas fa-eye" id="eye2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                                class="w-full gradient-header text-white font-bold py-3.5 rounded-xl shadow-[0_8px_20px_rgba(196,30,58,0.3)] hover:shadow-[0_12px_25px_rgba(196,30,58,0.4)] transition-all duration-300 transform hover:-translate-y-1 text-sm flex items-center justify-center gap-2 relative overflow-hidden group">
                            <span class="absolute inset-0 w-full h-full bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <i class="fas fa-rocket relative z-10 group-hover:animate-bounce"></i>
                            <span class="relative z-10">{{ __('Daftar Sekarang') }}</span>
                        </button>
                    </div>
                </form>

            </div>
            
            <div class="bg-gray-50/80 border-t border-gray-100 py-5 text-center transition-colors hover:bg-gray-100/80 rounded-b-3xl">
                <p class="text-gray-500 text-sm">{{ __('Sudah punya akun?') }}
                    <a href="{{ route('login') }}" class="text-red-600 font-bold hover:text-red-800 transition-colors ml-1 hover:underline decoration-2 underline-offset-4">{{ __('Masuk di sini') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePwd(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
            icon.classList.add('text-red-600');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
            icon.classList.remove('text-red-600');
        }
    }
</script>
@endpush
@endsection