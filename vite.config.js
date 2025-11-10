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
            registerType: 'autoUpdate', // Otomatis update service worker
            injectRegister: 'auto',
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg,jpg,jpeg,webp}']
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
                    // Arahkan ke ikon yang akan kita buat
                    {
                        "src": "/images/icons/pwa-192x192.png",
                        "sizes": "192x192",
                        "type": "image/png"
                    },
                    {
                        "src": "/images/icons/pwa-512x512.png",
                        "sizes": "512x512",
                        "type": "image/png"
                    },
                    {
                        "src": "/images/icons/pwa-512x512.png",
                        "sizes": "512x512",
                        "type": "image/png",
                        "purpose": "any maskable" // Penting untuk Android
                    }
                ]
            }
        })
    ],
});
