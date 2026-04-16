{{--
  ============================================================
  ACCESSIBILITY TOOLBAR — WCAG 2.1 Level AA
  Fitur: Ukuran teks, Kontras tinggi, Kurangi gerak
  State disimpan di localStorage
  ============================================================
--}}

{{-- Trigger Button --}}
<div id="a11y-launcher" class="a11y-launcher" role="complementary" aria-label="{{ __('Panel Aksesibilitas') }}">

    {{-- Trigger --}}
    <button id="a11y-toggle-btn"
            type="button"
            aria-expanded="false"
            aria-controls="a11y-panel"
            aria-label="{{ __('Buka panel aksesibilitas') }}"
            title="{{ __('Aksesibilitas') }}"
            class="a11y-trigger-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="22" height="22">
            <path d="M12 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm8 7h-5v13h-2v-6h-2v6H9V9H4V7h16v2z"/>
        </svg>
    </button>

    {{-- Panel --}}
    <div id="a11y-panel"
         role="dialog"
         aria-label="{{ __('Opsi Aksesibilitas') }}"
         aria-modal="false"
         class="a11y-panel"
         hidden>

        <div class="a11y-panel-header">
            <span class="a11y-panel-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="16" height="16">
                    <path d="M12 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm8 7h-5v13h-2v-6h-2v6H9V9H4V7h16v2z"/>
                </svg>
                {{ __('Aksesibilitas') }}
            </span>
            <button type="button" id="a11y-close-btn" aria-label="{{ __('Tutup panel aksesibilitas') }}" class="a11y-close-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="14" height="14">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        {{-- Font Size Control --}}
        <div class="a11y-section">
            <p class="a11y-section-label" id="font-size-label">{{ __('Ukuran Teks') }}</p>
            <div class="a11y-btn-group" role="group" aria-labelledby="font-size-label">
                <button type="button" data-font="normal"   class="a11y-font-btn active" aria-pressed="true"  title="{{ __('Ukuran normal') }}">A</button>
                <button type="button" data-font="large"    class="a11y-font-btn"         aria-pressed="false" title="{{ __('Ukuran besar') }}">A+</button>
                <button type="button" data-font="x-large"  class="a11y-font-btn"         aria-pressed="false" title="{{ __('Ukuran sangat besar') }}">A++</button>
            </div>
        </div>

        {{-- High Contrast Control --}}
        <div class="a11y-section">
            <p class="a11y-section-label" id="contrast-label">{{ __('Kontras Warna') }}</p>
            <div class="a11y-btn-group" role="group" aria-labelledby="contrast-label">
                <button type="button" id="a11y-contrast-btn"
                        class="a11y-toggle-btn"
                        aria-pressed="false"
                        title="{{ __('Aktifkan kontras tinggi') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" width="14" height="14">
                        <path d="M12 3a9 9 0 1 0 0 18A9 9 0 0 0 12 3zm0 16V5a7 7 0 0 1 0 14z"/>
                    </svg>
                    <span id="contrast-label-text">{{ __('Kontras Tinggi') }}</span>
                </button>
            </div>
        </div>

        {{-- Reduce Motion Control --}}
        <div class="a11y-section">
            <p class="a11y-section-label" id="motion-label">{{ __('Animasi') }}</p>
            <div class="a11y-btn-group" role="group" aria-labelledby="motion-label">
                <button type="button" id="a11y-motion-btn"
                        class="a11y-toggle-btn"
                        aria-pressed="false"
                        title="{{ __('Nonaktifkan animasi') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" width="14" height="14">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span id="motion-label-text">{{ __('Kurangi Gerak') }}</span>
                </button>
            </div>
        </div>

        {{-- Reset --}}
        <button type="button" id="a11y-reset-btn" class="a11y-reset-btn">
            {{ __('Reset ke Default') }}
        </button>

        <p class="a11y-footer-note">WCAG 2.1 Level AA</p>
    </div>
</div>

<style>
    /* =============================
       ACCESSIBILITY TOOLBAR STYLES
       ============================= */
    :root {
        --a11y-font-scale: 1;
    }

    /* Font Size Scaling */
    html.font-large  { font-size: 110% !important; }
    html.font-large  body * { --a11y-font-scale: 1.1; }
    html.font-xlarge { font-size: 125% !important; }
    html.font-xlarge body * { --a11y-font-scale: 1.25; }

    /* High Contrast Mode */
    html.high-contrast {
        filter: contrast(1.5) !important;
    }
    html.high-contrast body {
        background: #000 !important;
        color: #fff !important;
    }
    html.high-contrast a { color: #ffff00 !important; }
    html.high-contrast button { border: 2px solid #fff !important; }
    html.high-contrast .badge-green,
    html.high-contrast .badge-yellow,
    html.high-contrast .badge-red {
        border: 2px solid currentColor !important;
    }

    /* Reduce Motion */
    html.reduce-motion *,
    html.reduce-motion *::before,
    html.reduce-motion *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }

    /* Launcher wrapper */
    .a11y-launcher {
        position: fixed;
        bottom: 2rem;
        right: 1.5rem;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }

    /* Trigger button */
    .a11y-trigger-btn {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background: #1e3a5f;
        color: #fff;
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        transition: background 0.2s, transform 0.2s;
        outline-offset: 3px;
    }
    .a11y-trigger-btn:hover { background: #c41e3a; transform: scale(1.1); }
    .a11y-trigger-btn:focus-visible {
        outline: 3px solid #ffd700;
        outline-offset: 3px;
    }

    /* Panel */
    .a11y-panel {
        background: #fff;
        border: 2px solid #1e3a5f;
        border-radius: 1.25rem;
        padding: 1.25rem;
        width: 220px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        animation: a11y-slide-in 0.2s ease-out;
    }
    .a11y-panel[hidden] { display: none; }

    @keyframes a11y-slide-in {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .a11y-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .a11y-panel-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        font-weight: 900;
        color: #1e3a5f;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .a11y-close-btn {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: background 0.2s;
    }
    .a11y-close-btn:hover { background: #e2e8f0; }
    .a11y-close-btn:focus-visible { outline: 3px solid #c41e3a; outline-offset: 2px; border-radius: 50%; }

    .a11y-section {
        margin-bottom: 1rem;
    }
    .a11y-section-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.5rem;
    }
    .a11y-btn-group {
        display: flex;
        gap: 0.375rem;
        flex-wrap: wrap;
    }

    /* Font buttons */
    .a11y-font-btn {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        color: #1e3a5f;
        font-weight: 800;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.15s;
        min-width: 2.5rem;
    }
    .a11y-font-btn:hover { border-color: #1e3a5f; background: #eff6ff; }
    .a11y-font-btn.active,
    .a11y-font-btn[aria-pressed="true"] {
        background: #1e3a5f;
        color: #fff;
        border-color: #1e3a5f;
    }
    .a11y-font-btn:focus-visible { outline: 3px solid #c41e3a; outline-offset: 2px; }

    /* Toggle buttons */
    .a11y-toggle-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.75rem;
        border-radius: 0.5rem;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        color: #1e3a5f;
        font-weight: 700;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.15s;
        width: 100%;
    }
    .a11y-toggle-btn:hover { border-color: #1e3a5f; background: #eff6ff; }
    .a11y-toggle-btn[aria-pressed="true"] {
        background: #c41e3a;
        color: #fff;
        border-color: #c41e3a;
    }
    .a11y-toggle-btn:focus-visible { outline: 3px solid #ffd700; outline-offset: 2px; }

    /* Reset button */
    .a11y-reset-btn {
        width: 100%;
        padding: 0.5rem;
        border-radius: 0.5rem;
        border: 1.5px dashed #cbd5e1;
        background: transparent;
        color: #94a3b8;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: all 0.15s;
        margin-top: 0.5rem;
    }
    .a11y-reset-btn:hover { color: #c41e3a; border-color: #c41e3a; }
    .a11y-reset-btn:focus-visible { outline: 3px solid #c41e3a; outline-offset: 2px; }

    .a11y-footer-note {
        text-align: center;
        font-size: 0.55rem;
        color: #cbd5e1;
        margin-top: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }
</style>

<script>
(function() {
    'use strict';

    const STORAGE_KEY = 'buskampus_a11y';

    // --- Load saved prefs ---
    function loadPrefs() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; }
        catch(e) { return {}; }
    }
    function savePrefs(prefs) {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(prefs)); } catch(e) {}
    }

    // --- Apply preferences ---
    function applyFont(size) {
        document.documentElement.classList.remove('font-large','font-xlarge');
        if (size === 'large')   document.documentElement.classList.add('font-large');
        if (size === 'x-large') document.documentElement.classList.add('font-xlarge');

        document.querySelectorAll('.a11y-font-btn').forEach(btn => {
            const active = btn.dataset.font === size;
            btn.classList.toggle('active', active);
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    }

    function applyContrast(on) {
        document.documentElement.classList.toggle('high-contrast', on);
        const btn = document.getElementById('a11y-contrast-btn');
        if (btn) {
            btn.setAttribute('aria-pressed', on ? 'true' : 'false');
            document.getElementById('contrast-label-text').textContent =
                on ? btn.getAttribute('data-off-label') : btn.getAttribute('data-on-label');
        }
    }

    function applyMotion(on) {
        document.documentElement.classList.toggle('reduce-motion', on);
        const btn = document.getElementById('a11y-motion-btn');
        if (btn) {
            btn.setAttribute('aria-pressed', on ? 'true' : 'false');
            document.getElementById('motion-label-text').textContent =
                on ? btn.getAttribute('data-off-label') : btn.getAttribute('data-on-label');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const prefs = loadPrefs();
        const toggleBtn  = document.getElementById('a11y-toggle-btn');
        const panel      = document.getElementById('a11y-panel');
        const closeBtn   = document.getElementById('a11y-close-btn');
        const contrastBtn= document.getElementById('a11y-contrast-btn');
        const motionBtn  = document.getElementById('a11y-motion-btn');
        const resetBtn   = document.getElementById('a11y-reset-btn');

        // Label data
        if (contrastBtn) {
            contrastBtn.setAttribute('data-on-label',  contrastBtn.querySelector('span').textContent.trim());
            contrastBtn.setAttribute('data-off-label', '{{ __("Kontras Normal") }}');
        }
        if (motionBtn) {
            motionBtn.setAttribute('data-on-label',  motionBtn.querySelector('span').textContent.trim());
            motionBtn.setAttribute('data-off-label', '{{ __("Animasi Normal") }}');
        }

        // Apply saved prefs
        applyFont(prefs.font || 'normal');
        if (prefs.contrast) applyContrast(true);
        if (prefs.motion)   applyMotion(true);

        // Also respect system preference for reduced motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches && !prefs.motion) {
            applyMotion(true);
        }

        // Toggle panel
        function openPanel() {
            panel.removeAttribute('hidden');
            toggleBtn.setAttribute('aria-expanded', 'true');
            closeBtn && closeBtn.focus();
        }
        function closePanel() {
            panel.setAttribute('hidden', '');
            toggleBtn.setAttribute('aria-expanded', 'false');
            toggleBtn.focus();
        }

        if (toggleBtn) toggleBtn.addEventListener('click', function() {
            const isOpen = panel && !panel.hasAttribute('hidden');
            isOpen ? closePanel() : openPanel();
        });

        if (closeBtn) closeBtn.addEventListener('click', closePanel);

        // Escape closes panel
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && panel && !panel.hasAttribute('hidden')) closePanel();
        });

        // Font buttons
        document.querySelectorAll('.a11y-font-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const size = this.dataset.font;
                applyFont(size);
                const p = loadPrefs(); p.font = size; savePrefs(p);
            });
        });

        // Contrast toggle
        if (contrastBtn) contrastBtn.addEventListener('click', function() {
            const isOn = this.getAttribute('aria-pressed') === 'true';
            applyContrast(!isOn);
            const p = loadPrefs(); p.contrast = !isOn; savePrefs(p);
        });

        // Motion toggle
        if (motionBtn) motionBtn.addEventListener('click', function() {
            const isOn = this.getAttribute('aria-pressed') === 'true';
            applyMotion(!isOn);
            const p = loadPrefs(); p.motion = !isOn; savePrefs(p);
        });

        // Reset
        if (resetBtn) resetBtn.addEventListener('click', function() {
            applyFont('normal');
            applyContrast(false);
            applyMotion(false);
            savePrefs({});
            toggleBtn.focus();
        });
    });
})();
</script>
