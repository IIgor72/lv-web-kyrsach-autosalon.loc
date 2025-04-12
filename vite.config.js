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

    server: {
        // Указываем явный хост и порт Open Server
        host: 'localhost',
        port: 5173,
        strictPort: true, // Запрещаем Vite менять порт
        hmr: {
            host: 'localhost',
            protocol: 'ws',
        },
        cors: {
            origin: true,
            credentials: true
        }
    },
});
