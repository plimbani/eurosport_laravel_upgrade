const elixir = require('laravel-elixir');

require('laravel-elixir-remove');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {
    mix.sass('app.scss')
       .webpack('app.js')
       .webpack('pitch.js')
       .copy(
            'node_modules/bootstrap-sass/assets/fonts/bootstrap',
            'public/fonts/bootstrap'
        );
    mix.remove(['public/build/css','public/build/js']);
    mix.version(['css/app.css','js/pitch.js']);
});


