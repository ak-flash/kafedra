import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import mkcert from'vite-plugin-mkcert'
import livewire from '@defstudio/vite-livewire-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/kafedra/kafedra.css',
                'resources/css/filament/admin/admin.css',
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
});
