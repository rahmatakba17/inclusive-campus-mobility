import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],

    // ── Build Optimizations ──────────────────────────────────────────────
    build: {
        // Gzip target untuk file yang lebih kecil
        target: 'es2019',

        // Pakai esbuild (default) — lebih cepat dari Terser, tetap sangat kecil
        minify: 'esbuild',

        // Pisahkan CSS agar bisa di-preload terpisah
        cssCodeSplit: true,

        // Inline assets kecil (< 4kb) langsung ke JS/CSS — kurangi request
        assetsInlineLimit: 4096,

        rollupOptions: {
            output: {
                // ── Chunk Splitting Manual ────────────────────────────────────
                // Leaflet dipisah agar halaman non-map tidak memuat peta.
                manualChunks(id) {
                    if (id.includes('leaflet')) {
                        return 'vendor-leaflet'; // ~140KB — hanya dimuat di halaman map
                    }
                    if (id.includes('alpinejs')) {
                        return 'vendor-alpine';  // dimuat lazy via <script defer>
                    }
                    if (id.includes('axios')) {
                        return 'vendor-axios';
                    }
                    if (id.includes('node_modules')) {
                        return 'vendor'; // semua node_modules lain → satu chunk
                    }
                },

                // Nama file pakai hash untuk bust cache di production
                chunkFileNames:  'assets/js/[name]-[hash].js',
                entryFileNames:  'assets/js/[name]-[hash].js',
                assetFileNames:  'assets/[ext]/[name]-[hash].[ext]',
            },
        },

        // Report ukuran chunk di terminal setelah build
        chunkSizeWarningLimit: 500,
    },

    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
