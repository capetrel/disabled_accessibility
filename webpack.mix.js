const mix = require('laravel-mix');
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

mix.disableSuccessNotifications()
    .sass('resources/sass/disabled_accessibility.scss', 'assets/css')
    .options({
        processCssUrls: false,
        terser: {
            extractComments: false
        }
    })
    .js('resources/js/disabled_accessibility.js', 'assets/js')
    .sourceMaps(false, 'source-map')

