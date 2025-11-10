import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        VitePWA({
             outDir: 'public',
            registerType: 'autoUpdate',

            injectRegister: 'auto',
            workbox: {
        globPatterns: ['**/*.{js,css,html,ico,png,svg,jpg,jpeg,webp}'],
        maximumFileSizeToCacheInBytes: 5 * 1024 * 1024, // (opsional) naikkan limit jadi 5 MB
        globIgnores: [
            '**/storage/**', // abaikan folder besar
            '**/*.mp4',
            '**/*.zip',
            '**/*.pdf'
        ]
    },

            // Ini adalah "KTP" aplikasi Anda
            manifest: {
                name: 'LionNews Sumba',
                short_name: 'LionNews',
                description: 'Portal Berita Terkini, Kritis, dan Berkelas dari Sumba.',
                // Tema Brand KITA
                theme_color: '#1E3A8A', // Brand Biru (Deep Blue)
                background_color: '#F5F5F5', // Brand Putih (Base)

                start_url: '/',
                display: 'standalone', // Tampil tanpa bar browser
                orientation: 'portrait',
                icons: [
                    {
                        "src": "/images/icons/web-app-manifest-192x192.png",
                        "sizes": "192x192",
                        "type": "image/png"
                    },
                    {
                        "src": "/images/icons/web-app-manifest-512x512.png",
                        "sizes": "512x512",
                        "type": "image/png"
                    },
                    {
                        "src": "/images/icons/web-app-manifest-512x512.png",
                        "sizes": "512x512",
                        "type": "image/png",
                        "purpose": "any maskable"
                    }
                ]
            }
        })
    ],
});
