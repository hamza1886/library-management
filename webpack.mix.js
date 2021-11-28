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

mix.js('resources/js/app.js', 'public/js')
    .sourceMaps(false)
    .postCss('resources/css/app.css', 'public/css', [
        //
    ])
    .when(mix.inProduction(), function (mix) {
        mix.version()
            .minify('public/css/app.css', 'public/css/app.min.css')
            .minify('public/js/app.js', 'public/js/app.min.js');
    })
    .disableNotifications();
