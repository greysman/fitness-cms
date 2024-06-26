const mix = require('laravel-mix').setPublicPath('public/backend');
const tailwindcss = require('tailwindcss'); 

require('laravel-mix-purgecss');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.postCss('resources/css/filament.css', 'public/backend/css', [
        tailwindcss("tailwind-admin.config.js")
    ])
    .version();
