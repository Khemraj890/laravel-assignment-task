import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // UI uses design.html stack (Tailwind 4 browser + Basecoat); keep JS only so Tailwind 3 preflight in app.css does not override Basecoat.
            input: ['resources/js/app.js'],
            refresh: true,
        }),
    ],
});