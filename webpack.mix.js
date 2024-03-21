const mix = require('laravel-mix');
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
mix.sass('resources/scss/app.scss', 'public/css/app.css')
    .postCss('resources/css/misc.css', 'public/css/misc.css', [
        tailwindcss("tailwind-public.config.js")
    ])
    .purgeCss({ 
        enabled: true,
        css: ['public/css/app.css', 'public/css/misc.css'],
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
    })
    .js('resources/js/app.js', 'public/js')
    .version();
