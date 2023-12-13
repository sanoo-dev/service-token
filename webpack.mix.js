const mix = require('laravel-mix');
let glob = require('glob');

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

// if (mix.inProduction()) {
//     mix.version();
// }

mix.version();


mix.copyDirectory(  'resources/img', 'public/img')
    .copyDirectory('resources/location', 'public/location')
    .copyDirectory('resources/assets', 'public/assets')
    .copyDirectory('resources/fafont', 'public/fafont')
    .js( 'resources/js/bootstrap.bundle.min.js', 'public/js')
    .js(  'resources/js/jquery.js', 'public/js')
    .js(  'resources/js/flatpickr.js', 'public/js')
    .js(  'resources/js/endpoint.js', 'public/js')
    // .js('resources/js/bootstrap.js', 'public/js')
    .js('resources/js/bootstrap5.js', 'public/js')

    .postCss(  'resources/css/screen.css', 'public/css/screen.css')
    // .postCss(  'resources/css/app.css', 'public/css/app.css')
    .postCss(  'resources/css/custom.css', 'public/css/custom.css')
    // .postCss(  'resources/css/darkmode.css', 'public/css/darkmode.css')
    .postCss(  'resources/css/bootstrap.min.css', 'public/css/bootstrap.min.css')
    .postCss(  'resources/css/style.css', 'public/css/style.css')

glob.sync('./app/Modules/**/webpack.mix.js').forEach(item => require(item));
