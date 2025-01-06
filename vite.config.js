import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/pos/css/app.css', 'resources/pos/js/app.jsx'],
            refresh: true,
        }),
        react()
    ],
});
