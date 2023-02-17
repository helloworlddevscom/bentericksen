let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 | BrowserSync: adjust your proxy settings below with the local domain.
 |
 */
mix.setPublicPath('../httpdocs/assets')
  .js('resources/assets/js/app.js', 'js/app.js')
  .sass('resources/assets/sass/app.scss', 'styles/app.css')
  .browserSync({
    proxy: 'http://bentericksen.localhost:3000',
    files: [
      'resources/assets/**/*'
    ]
  });
