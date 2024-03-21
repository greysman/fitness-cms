import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import purge from '@sakadda/vite-plugin-laravel-purgecss';
const path = require('path');

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/scss/app.scss',
                'resources/js/app.js',
                // 'resources/css/filament.css',
                'resources/css/misc.css'
            ],
            refresh: true,
        }),
        purge({
            enabled: true,
            extend: {
                content: [
                    "app/**/*.php",
                    "app/View/Components/*.php",
                    "resources/**/*.html",
                    "resources/**/*.js",
                    "resources/**/*.jsx",
                    "resources/**/*.ts",
                    "resources/**/*.tsx",
                    "resources/**/*.php",
                    "resources/**/*.vue",
                    "resources/**/*.twig",
                    "vendor/filament/*/resources/**/*.js",
                    "vendor/filament/*/resources/**/*.php",
                ],
                defaultExtractor: (content) => content.match(/[\w-/.:]+(?<!:)/g) || [],
                safelist: [
                    'price-list',
                    'metis',
                    'tparrows',
                    'modal-open',
                    'modal-backdrop',
                ],
                whitelistPatterns: [
                    /-active$/, 
                    /-enter$/, 
                    /-leave-to$/, 
                    /show$/,
                    /fade$/,
                    /entered$/,
                ],
            }
        }),
    ],
    resolve: {
        alias: {
          '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
      },    
});
