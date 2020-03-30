const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .js('node_modules/startbootstrap-sb-admin/js/sb-admin.min.js', 'public/js/sb-admin.min.js')
    .sass('resources/sass/bootstrap.scss', 'public/vendor/bootstrap/css/bootstrap.min.css')
    .sass('resources/sass/app.scss', 'public/css')
    .copyDirectory('node_modules/moment', 'public/vendor/moment')
    .copyDirectory('node_modules/datatables.net', 'public/vendor/datatables.net')
    .copyDirectory('node_modules/datatables.net-bs4', 'public/vendor/datatables.net-bs4')
    .copyDirectory('node_modules/jquery/dist', 'public/vendor/jquery')
    .copyDirectory('resources/assets/js/scripts.js', 'public/js/scripts.js')
    .copyDirectory('resources/assets/css/styles.css', 'public/css/styles.css');

