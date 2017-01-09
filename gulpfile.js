const elixir = require('laravel-elixir');
require('laravel-elixir-remove');
require('laravel-elixir-vue-2');
require('laravel-elixir-eslint');

// Add jslint
//const gulp = require('gulp');
//const eslint = require('gulp-eslint');
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
var adminThemePath = './public/';

elixir((mix) => {
    mix.sass('app.scss')
        .webpack('app.js')
        .webpack('pitch.js')
        .webpack('tournament.js')
        .webpack('dashboard.js')
       
       .copy(
            'node_modules/bootstrap-sass/assets/fonts/bootstrap',
            'public/fonts/bootstrap'
        );
    mix.styles([
    adminThemePath + 'admin_theme/global/plugins/simple-line-icons/simple-line-icons.min.css',
    adminThemePath + 'admin_theme/global/plugins/bootstrap/css/bootstrap.min.css',
    adminThemePath + 'admin_theme/global/plugins/bootstrap-toastr/toastr.min.css',
    adminThemePath + 'admin_theme/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
    adminThemePath + 'admin_theme/global/css/components.min.css',
    adminThemePath + 'admin_theme/global/css/plugins.min.css',
    adminThemePath + 'admin_theme/global/plugins/select2/css/select2.min.css',
    adminThemePath + 'admin_theme/global/plugins/select2/css/select2-bootstrap.min.css',
    adminThemePath + 'admin_theme/layouts/layout/css/layout.min.css',
    adminThemePath + 'admin_theme/layouts/layout/css/themes/default.min.css',
    adminThemePath + 'admin_theme/layouts/layout/css/custom.min.css', 
    adminThemePath + 'css/styles.css',
    adminThemePath + 'css/popupbutton.css',
    ],'public/css/layout_admin_theme.css')  

    mix.scripts([
        adminThemePath + 'admin_theme/global/plugins/jquery.blockui.min.js',
        adminThemePath + 'admin_theme/global/plugins/select2/js/select2.full.min.js',
        adminThemePath + 'admin_theme/global/scripts/app.js',
        adminThemePath + 'admin_theme/layouts/layout/scripts/layout.min.js',
        adminThemePath + 'admin_theme/layouts/layout/scripts/demo.min.js',
        adminThemePath + 'admin_theme/layouts/global/scripts/quick-sidebar.min.js',
        adminThemePath + 'admin_theme/global/plugins/bootstrap-toastr/toastr.min.js',
        adminThemePath + 'admin_theme/global/plugins/moment.min.js',
        adminThemePath + 'admin_theme/pages/scripts/components-select2.min.js',
        adminThemePath + 'js/jquery.cokie.min.js',
        adminThemePath + 'js/jquery.twbsPagination.min.js',
        adminThemePath + 'js/jquery.validate.min.js',
        adminThemePath + 'js/metronic.js',
        adminThemePath + 'js/inview.js',
    ],'public/js/layout_admin_theme.js');

    mix.remove(['public/build/css','public/build/js']);
    mix.version(['css/app.css','js/pitch.js']);
   
    mix.eslint('resources/assets/js/*.js');
});