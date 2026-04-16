@extends('layouts.app')

@section('title', 'Masuk - Sistem Booking Bus Kampus Non-Merdeka | Reservasi Transportasi Civitas Hasanuddin')
@section('meta_description', 'Login ke Sistem Informasi Bus Kampus Non-Merdeka. Pesan tiket bus kampus secara online, pantau posisi bus secara real-time, dan nikmati perjalanan menuju Kampus Non-Merdeka.')

@section('content')

    <main class="min-h-screen flex relative overflow-hidden" style="background: linear-gradient(135deg, #0f2137 0%, #1e3a5f 40%, #821326 100%);">
        
        <!-- Decorative background elements -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-red-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 pointer-events-none"></div>

        {{-- Left panel - Hero Section --}}
        <section aria-labelledby="hero-heading" class="hidden lg:flex lg:w-1/2 flex-col justify-between p-14 relative z-10">

            {{-- Logo & Brand --}}
            <header class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-md shadow-lg border border-white/20 transition-transform hover:scale-105">
                    <i class="fas fa-bus text-white text-xl"></i>
                </div>
                <div>
                    <a href="{{ route('home') }}" class="hover:opacity-90 transition-opacity" aria-label="Beranda Bus Kampus Non-Merdeka">
                        <h1 class="text-white font-extrabold text-2xl tracking-tight leading-tight drop-shadow-md">Bus Kampus Non-Merdeka</h1>
                        <p class="text-red-200 text-sm font-medium drop-shadow">Sistem Informasi Transportasi Kampus Resmi</p>
                    </a>
                </div>
            </header>

            {{-- Hero Content --}}
            <article class="space-y-6">
                <div class="rounded-3xl p-8 backdrop-blur-md bg-white/10 border border-white/20 shadow-2xl transition-transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-red-500/30 rounded-xl flex items-center justify-center mb-6 shadow-inner">
                        <i class="fas fa-route text-red-100 text-xl"></i>
                    </div>
                    <h2 id="hero-heading" class="text-white text-3xl lg:text-4xl font-extrabold leading-tight mb-4 drop-shadow-md tracking-tight">
                        Perjalanan Aman &amp; Nyaman<br>untuk <span class="text-red-200 inline-block mt-1">Civitas Kampus Non-Merdeka</span>
                    </h2>
                    <p class="text-blue-50 text-base leading-relaxed mb-6 font-light drop-shadow">
                        Platform booking transportasi kampus resmi Kampus Non-Merdeka. Pantau posisi bus secara <em class="font-semibold">real-time</em>, pesan tiket kapan saja, dan tiba di tujuan tepat waktu.
                    </p>

                    {{-- Feature badges --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <span class="bg-white/10 text-white font-medium text-sm px-4 py-2.5 rounded-2xl md:rounded-full border border-white/20 flex items-center justify-center gap-2 shadow-sm backdrop-blur-sm">
                            <i class="fas fa-map-marker-alt text-red-300"></i> Pelacakan Real-time
                        </span>
                        <span class="bg-white/10 text-white font-medium text-sm px-4 py-2.5 rounded-2xl md:rounded-full border border-white/20 flex items-center justify-center gap-2 shadow-sm backdrop-blur-sm">
                            <i class="fas fa-ticket-alt text-red-300"></i> Booking Online
                        </span>
                        <span class="sm:col-span-2 bg-white/10 text-white font-medium text-sm px-4 py-2.5 rounded-2xl md:rounded-full border border-white/20 flex items-center justify-center gap-2 shadow-sm backdrop-blur-sm">
                            <i class="fas fa-shield-alt text-red-300"></i> Sistem Terverifikasi
                        </span>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-2xl p-5 text-center backdrop-blur-md bg-white/10 border border-white/20 group hover:bg-white/20 transition-all shadow-lg hover:-translate-y-1">
                        <div class="w-10 h-10 bg-red-500/30 rounded-xl flex items-center justify-center mx-auto mb-3 transition-transform group-hover:scale-110">
                            <i class="fas fa-bus text-red-100"></i>
                        </div>
                        <div class="text-3xl font-extrabold text-white drop-shadow-md">5+</div>
                        <div class="text-red-100 text-sm mt-1 font-semibold">Armada Bus</div>
                        <div class="text-white/60 text-xs">ber-AC &amp; modern</div>
                    </div>
                    <div class="rounded-2xl p-5 text-center backdrop-blur-md bg-white/10 border border-white/20 group hover:bg-white/20 transition-all shadow-lg hover:-translate-y-1">
                        <div class="w-10 h-10 bg-red-500/30 rounded-xl flex items-center justify-center mx-auto mb-3 transition-transform group-hover:scale-110">
                            <i class="fas fa-road text-red-100"></i>
                        </div>
                        <div class="text-3xl font-extrabold text-white drop-shadow-md">2</div>
                        <div class="text-red-100 text-sm mt-1 font-semibold">Rute Aktif</div>
                        <div class="text-white/60 text-xs">Perintis ↔ Gowa</div>
                    </div>
                    <div class="rounded-2xl p-5 text-center backdrop-blur-md bg-white/10 border border-white/20 group hover:bg-white/20 transition-all shadow-lg hover:-translate-y-1">
                        <div class="w-10 h-10 bg-red-500/30 rounded-xl flex items-center justify-center mx-auto mb-3 transition-transform group-hover:scale-110">
                            <i class="fas fa-satellite-dish text-red-100"></i>
                        </div>
                        <div class="text-3xl font-extrabold text-white drop-shadow-md">GPS</div>
                        <div class="text-red-100 text-sm mt-1 font-semibold">Tracking Langsung</div>
                        <div class="text-white/60 text-xs">posisi real-time</div>
                    </div>
                </div>
            </article>

            <footer class="text-white/50 text-xs tracking-wide">
                &copy; {{ date('Y') }} Kampus Non-Merdeka. Hak Cipta Terlindungi. Sistem Informasi Bus Kampus Non-Merdeka.
            </footer>
        </section>

        {{-- Right panel - Login Form --}}
        <section aria-labelledby="login-heading" class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-10 relative z-10">
            <div class="w-full max-w-md">
                <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.5)] p-8 animate-slide-up border border-white/20">

                    {{-- Header --}}
                    <header class="text-center mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-red-500/30 transform transition-transform hover:scale-105">
                            <i class="fas fa-sign-in-alt text-white text-2xl"></i>
                        </div>
                        <h2 id="login-heading" class="text-2xl font-extrabold text-gray-900 tracking-tight">Masuk ke Akun Anda</h2>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed px-4">
                            Akses fitur lengkap Sistem Bus Kampus Non-Merdeka – booking tiket, pantau bus, dan kelola perjalanan.
                        </p>
                    </header>

                    {{-- Success message (e.g. from registration) --}}
                    @if(session('success'))
                        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 px-4 py-3 rounded-r-lg text-sm shadow-sm animate-fade-in" role="alert" aria-live="polite">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-emerald-500 mt-0.5 text-base"></i>
                                <div>
                                    <strong class="font-bold block">Berhasil</strong>
                                    <span class="block mt-1 text-emerald-700">{{ session('success') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Error --}}
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-r-lg text-sm shadow-sm animate-fade-in" role="alert" aria-live="assertive">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 text-base"></i>
                                <div>
                                    <strong class="font-bold block">Login Gagal</strong>
                                    <span class="block mt-1 text-red-700">{{ $errors->first() }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('login') }}" method="POST" class="space-y-5" novalidate>
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5 flex items-center gap-1.5">
                                <i class="fas fa-user-circle text-gray-400"></i>
                                Alamat Email Kampus Non-Merdeka
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-red-600">
                                    <i class="fas fa-envelope text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    autocomplete="email"
                                    placeholder="nama@kampus-non-merdeka.ac.id"
                                    aria-required="true"
                                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all bg-gray-50 focus:bg-white text-sm shadow-sm placeholder-gray-400 font-medium text-gray-800 hover:border-gray-300">
                            </div>
                            @error('email')
                                <span class="text-red-500 text-xs mt-1.5 flex items-start gap-1.5 font-medium" role="alert">
                                    <i class="fas fa-times-circle mt-0.5"></i> <span>{{ $message }}</span>
                                </span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password-input" class="block text-sm font-bold text-gray-700 mb-1.5 flex items-center gap-1.5">
                                <i class="fas fa-key text-gray-400"></i>
                                Kata Sandi
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-red-600">
                                    <i class="fas fa-lock text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                                </div>
                                <input type="password" name="password" required id="password-input"
                                    autocomplete="current-password"
                                    placeholder="Masukkan kata sandi"
                                    aria-required="true"
                                    class="w-full pl-11 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all bg-gray-50 focus:bg-white text-sm shadow-sm placeholder-gray-400 font-medium text-gray-800 hover:border-gray-300">
                                <button type="button" onclick="togglePassword()" aria-label="Tampilkan/Sembunyikan Kata Sandi"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition-colors focus:outline-none">
                                    <i class="fas fa-eye text-base" id="eye-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-xs mt-1.5 flex items-center gap-1 font-medium" role="alert">
                                    <i class="fas fa-times-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Remember me --}}
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <div class="relative flex items-center justify-center w-4 h-4">
                                    <input type="checkbox" name="remember" id="remember"
                                        class="peer appearance-none w-4 h-4 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:ring-offset-1 checked:bg-red-600 checked:border-red-600 transition-colors cursor-pointer">
                                    <i class="fas fa-check absolute text-[10px] text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors select-none">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" id="btn-login"
                            class="w-full bg-gradient-to-r from-red-600 to-red-800 text-white font-bold py-3.5 rounded-xl shadow-[0_8px_20px_rgba(220,38,38,0.25)] hover:shadow-[0_12px_25px_rgba(220,38,38,0.35)] transition-all duration-300 transform hover:-translate-y-1 block relative overflow-hidden group">
                            <span class="absolute inset-0 w-full h-full bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <div class="relative z-10 flex items-center justify-center gap-2">
                                <i class="fas fa-sign-in-alt group-hover:translate-x-1 transition-transform"></i>
                                <span>Masuk ke Akun</span>
                            </div>
                        </button>
                    </form>

                    {{-- Register Link --}}
                    <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                        <p class="text-gray-500 text-sm font-medium">
                            Belum memiliki akun civitas? 
                            <a href="{{ route('register') }}"
                                class="text-red-600 font-bold hover:text-red-800 transition-colors inline-flex items-center gap-1.5 ml-1 group decoration-2 underline-offset-4 hover:underline">
                                Daftar Sekarang
                                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </p>
                    </div>

                    {{-- Demo credentials --}}
                    <aside class="mt-6 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 shadow-inner" aria-label="Kredensial Demo Eksperimental">
                        <header class="text-xs font-extrabold text-blue-900 mb-4 flex items-center gap-2 uppercase tracking-wide">
                            <i class="fas fa-vial text-blue-600 text-sm"></i>
                            Akun Uji Coba Sistem
                        </header>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 p-3 bg-white/80 backdrop-blur-sm rounded-lg border border-white hover:border-blue-200 transition-colors hover:shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600">
                                    <i class="fas fa-user-shield text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-gray-800 text-sm tracking-tight truncate">Administrator</div>
                                    <div class="text-[11px] font-mono text-gray-500 mt-0.5"><span class="text-blue-600 font-semibold">admin@kampus-non-merdeka.ac.id</span> / <span class="bg-gray-100 rounded px-1 transition-colors hover:bg-gray-200 cursor-text">password</span></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-white/80 backdrop-blur-sm rounded-lg border border-white hover:border-blue-200 transition-colors hover:shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 text-indigo-600">
                                    <i class="fas fa-id-card text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-gray-800 text-sm tracking-tight truncate">Supir / Operator</div>
                                    <div class="text-[11px] font-mono text-gray-500 mt-0.5"><span class="text-indigo-600 font-semibold">sopir1@kampus-non-merdeka.ac.id</span> / <span class="bg-gray-100 rounded px-1 transition-colors hover:bg-gray-200 cursor-text">password</span></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-white/80 backdrop-blur-sm rounded-lg border border-white hover:border-blue-200 transition-colors hover:shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                    <i class="fas fa-user-graduate text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-gray-800 text-sm tracking-tight truncate">Civitas / Umum</div>
                                    <div class="text-[11px] font-mono text-gray-500 mt-0.5"><span class="text-emerald-600 font-semibold">budi@kampus-non-merdeka.ac.id</span> / <span class="bg-gray-100 rounded px-1 transition-colors hover:bg-gray-200 cursor-text">password</span></div>
                                </div>
                            </div>
                        </div>
                    </aside>

                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            function togglePassword() {
                const input = document.getElementById('password-input');
                const icon = document.getElementById('eye-icon');
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