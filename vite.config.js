import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        cors: true,  // ✅ Autorise les requêtes cross-origin
        localhost: '127.0.0.1', // Ou 'localhost' selon ton setup
        port: 5173, // S'assurer que Vite utilise bien ce port
        strictPort: true, // Évite que Vite change de port si 5173 est occupé
        hmr: {
            host: 'localhost',
        },
    },
});