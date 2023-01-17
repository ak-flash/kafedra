import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import mkcert from'vite-plugin-mkcert'
import livewire from '@defstudio/vite-livewire-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament.css',
                'resources/css/filament.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
                'app/Forms/Components/**',
            ],
        }),
        mkcert(),
        livewire({
            refresh: ['resources/css/app.css'],
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    },
})
