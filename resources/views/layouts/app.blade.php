<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ __('Sistem Tiket Bus Kampus Kampus Non-Merdeka - Pesan tiket bus kampus secara online dengan mudah dan cepat.') }}">
    <meta name="theme-color" content="#c41e3a">
    <title>@yield('title', __('Bus Kampus Non-Merdeka')) | {{ __('Sistem Tiket Bus Kampus Non-Merdeka') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* =============================================
           WCAG 2.1 LEVEL AA — GLOBAL ACCESSIBILITY CSS
           ============================================= */

        * { font-family: 'Inter', system-ui, sans-serif; }

        /* --- Skip Navigation Link (WCAG 2.4.1) --- */
        .skip-nav {
            position: absolute;
            top: -100%;
            left: 1rem;
            background: #c41e3a;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0 0 0.75rem 0.75rem;
            font-weight: 800;
            font-size: 0.9rem;
            text-decoration: none;
            z-index: 99999;
            transition: top 0.2s;
            outline: 3px solid #ffd700;
            outline-offset: 2px;
        }
        .skip-nav:focus { top: 0; }

        /* --- Global Focus Indicator (WCAG 2.4.7) --- */
        :focus-visible {
            outline: 3px solid #c41e3a;
            outline-offset: 3px;
            border-radius: 4px;
        }
        /* Override for dark backgrounds */
        .bg-\[\#821326\] :focus-visible,
        .bg-\[\#a51c24\] :focus-visible,
        aside :focus-visible {
            outline-color: #ffd700;
        }
        /* Remove outline for mouse users (keep for keyboard) */
        :focus:not(:focus-visible) { outline: none; }

        /* --- Reduced Motion (WCAG 2.3.3) --- */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* --- High Contrast Media Query (WCAG 1.4.6) --- */
        @media (forced-colors: active) {
            .badge-green, .badge-yellow, .badge-red {
                border: 2px solid ButtonText;
                forced-color-adjust: none;
            }
            .sidebar-link.active {
                border: 2px solid Highlight;
            }
        }

        .gradient-header { background: linear-gradient(135deg, #c41e3a 0%, #821326 100%); }
        .gradient-navy   { background: linear-gradient(135deg, #1e3a5f 0%, #0f2137 100%); }

        .glass-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            color: rgba(255,255,255,0.85); /* Improved: was 0.7 — min 4.5:1 contrast */
            transition: all 0.3s;
            font-weight: 500;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        .sidebar-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 2px solid rgba(255,255,255,0.25); /* Improved border visibility */
        }

        .btn-primary {
            background: linear-gradient(to right, #c41e3a, #821326);
            color: white;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            transform: scale(1);
            display: inline-flex;
            align-items: center;
        }
        .btn-primary:hover { transform: scale(1.02); box-shadow: 0 25px 50px rgba(196,30,58,0.3); }
        .btn-primary:active { transform: scale(0.98); }
        .btn-primary:focus-visible { outline: 3px solid #ffd700; outline-offset: 3px; }

        .btn-gold {
            background: linear-gradient(to right, #ffd700, #f59e0b);
            color: #4c0b16; /* High contrast ratio ~12:1 */
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            transition: all 0.3s;
            transform: scale(1);
        }
        .btn-gold:hover { transform: scale(1.02); }
        .btn-gold:active { transform: scale(0.98); }
        .btn-gold:focus-visible { outline: 3px solid #1e3a5f; outline-offset: 3px; }

        .card {
            background: white;
            border-radius: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
            padding: 2rem;
            transition: all 0.3s;
        }
        .card:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        .stat-card {
            border-radius: 2rem;
            padding: 2rem;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }

        /* Badges — WCAG 1.4.3: min 4.5:1 contrast */
        .badge-green  { display: inline-flex; align-items: center; padding: 0.375rem 1rem; border-radius: 9999px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; background: #d1fae5; color: #064e3b; border: 1px solid #6ee7b7; }
        .badge-yellow { display: inline-flex; align-items: center; padding: 0.375rem 1rem; border-radius: 9999px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; background: #fef3c7; color: #78350f; border: 1px solid #fde68a; }
        .badge-red    { display: inline-flex; align-items: center; padding: 0.375rem 1rem; border-radius: 9999px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; background: #ffe4e6; color: #881337; border: 1px solid #fecdd3; }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-50">

    {{-- Skip Navigation Link (WCAG 2.4.1) --}}
    <a href="#main-content" class="skip-nav">{{ __('Lewati ke konten utama') }}</a>

    @yield('content')

    {{-- Accessibility Toolbar (WCAG 2.1 Level AA) --}}
    @include('partials.accessibility-toolbar')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>