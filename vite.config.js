import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

import path from 'path';

export default defineConfig({
    base: '/build/', //NGROKPRUEBA
    plugins: [
        laravel({input: [
                'resources/js/app.js',
                /*'resources/js/index.js',*/
                'resources/js/landing.js',      //Inclusión de css personalizado.
            ],
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
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        //sourcemap: true, // Obligatorio para debug per abajo se elige
        sourcemap: process.env.NODE_ENV !== 'production', // Cambio sourcemap
        rollupOptions: { // CSSCAMBIO
            input: {
                app: 'resources/js/app.js',
                landing: 'resources/js/landing.js'
            }
        }
    },
    resolve: { // CSSCAMBIO
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@Components': path.resolve(__dirname, 'resources/js/Components'),
        }
    }
});
