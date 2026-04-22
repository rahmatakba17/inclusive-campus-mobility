<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Error Page Test Dashboard — Bus Kampus Non-Merdeka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .card-hover { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
    </style>
</head>
<body class="min-h-screen bg-[#f0f4f8] p-8">

    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-4">
                <i class="fas fa-flask"></i> Development Only — Local Environment
            </div>
            <h1 class="text-4xl font-black text-slate-800 tracking-tight mb-2">Error Page Test Dashboard</h1>
            <p class="text-slate-500 font-medium">Klik kartu di bawah untuk menguji tampilan halaman error Bus Kampus Non-Merdeka.</p>
            <p class="text-xs text-slate-500 mt-2 font-mono bg-white inline-block px-3 py-1 rounded-lg border border-slate-200 mt-3">
                Route aktif hanya di <strong>APP_ENV=local</strong>
            </p>
        </div>

        {{-- Error Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($errors as $err)
            <a href="/test-error/{{ $err['code'] }}" class="card-hover block bg-white rounded-3xl p-8 border border-slate-100 shadow-sm no-underline group">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-12 h-12 rounded-2xl {{ $err['color'] }} flex items-center justify-center">
                        <i class="fas {{ $err['icon'] }} text-xl"></i>
                    </div>
                    <span class="text-xs font-black text-slate-300 uppercase tracking-widest group-hover:text-slate-500 transition-colors">PREVIEW →</span>
                </div>
                <p class="text-5xl font-black text-slate-800 tracking-tighter leading-none mb-2">{{ $err['code'] }}</p>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wide">{{ $err['label'] }}</p>
                <div class="mt-4 text-xs font-mono text-slate-300">/test-error/{{ $err['code'] }}</div>
            </a>
            @endforeach
        </div>

        {{-- How-to Guide --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <h2 class="text-lg font-black text-slate-800 mb-6 tracking-tight">
                <i class="fas fa-book-open text-[#1e3a5f] mr-2"></i> Cara Menguji Error Pages
            </h2>
            <div class="space-y-4">

                <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-8 h-8 bg-blue-500 text-white rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0">1</div>
                    <div>
                        <p class="font-bold text-slate-700 text-sm">Klik kartu di atas</p>
                        <p class="text-slate-500 text-xs mt-0.5">Atau buka langsung URL: <code class="bg-slate-200 px-2 py-0.5 rounded font-mono">/test-error/{kode}</code></p>
                    </div>
                </div>

                <div class="flex gap-4 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-8 h-8 bg-blue-500 text-white rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0">2</div>
                    <div>
                        <p class="font-bold text-slate-700 text-sm">Uji via URL langsung</p>
                        <div class="mt-2 space-y-1 font-mono text-xs text-slate-500">
                            @foreach($errors as $err)
                            <div class="flex items-center gap-2">
                                <span class="w-10 text-right font-bold text-slate-600">{{ $err['code'] }}</span>
                                <span class="text-slate-300">→</span>
                                <span>http://localhost:8181/test-error/{{ $err['code'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                    <div class="w-8 h-8 bg-amber-400 text-white rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0">!</div>
                    <div>
                        <p class="font-bold text-amber-700 text-sm">Catatan Khusus untuk Error 419 & 500</p>
                        <div class="text-amber-600 text-xs mt-1 space-y-1">
                            <p><strong>419 (CSRF Token Expired)</strong> — Terjadi otomatis jika form dibiarkan terlalu lama lalu di-submit. Route ini mensimulasikannya.</p>
                            <p><strong>500 (Server Error)</strong> — Pastikan <code class="bg-amber-100 px-1 rounded">APP_DEBUG=false</code> di <code class="bg-amber-100 px-1 rounded">.env</code> agar tampil halaman 500 kustom (bukan stack trace).</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <div class="w-8 h-8 bg-emerald-500 text-white rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <div>
                        <p class="font-bold text-emerald-700 text-sm">Cara memicu error NATURAL (tanpa route test)</p>
                        <div class="text-emerald-600 text-xs mt-1 space-y-1">
                            <p><strong>404</strong> → Buka URL yang tidak ada, misal: <code class="bg-emerald-100 px-1 rounded">/halaman-tidak-ada</code></p>
                            <p><strong>403</strong> → Login sebagai user biasa, lalu akses <code class="bg-emerald-100 px-1 rounded">/admin/dashboard</code></p>
                            <p><strong>401</strong> → Akses endpoint yang butuh auth tanpa login</p>
                            <p><strong>503</strong> → Jalankan <code class="bg-emerald-100 px-1 rounded">php artisan down</code> di terminal</p>
                            <p><strong>419</strong> → Submit form setelah buka browser baru / cookies dihapus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8">
            <a href="/" class="inline-flex items-center gap-2 bg-[#1e3a5f] text-white px-6 py-3 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-[#0f2137] transition-colors shadow-lg">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <p class="text-xs text-slate-500 mt-4 font-mono">
                Route ini <strong>tidak tersedia</strong> di production (APP_ENV=production)
            </p>
        </div>
    </div>

</body>
</html>
