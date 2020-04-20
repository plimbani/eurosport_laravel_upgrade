const { mix } = require('laravel-mix');

const postcss = require('postcss');

const postcssCustomProperties = require('postcss-custom-properties');

// const defaultSassOptions = {
//     processCssUrls: false,
//     postCss: [
//         require('autoprefixer')({
//             grid: true,
//             overrideBrowserslist: ['last 40 versions'],
//             cascade: false
//         })
//     ]
// };

if(process.env.NODE_ENV == 'development') {
    mix.webpackConfig({
            devtool: 'source-map'
        })
        .sourceMaps();
} else {
    mix.version();
}

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

var pluginPath =  'resources/assets/plugins/';

mix.combine([
    // ** Required Plugins **
    pluginPath + 'jquery/jquery.js',
    pluginPath + 'bootstrap/tether.js',
    pluginPath + 'bootstrap/bootstrap.js',
    pluginPath + 'customScrollBar/customScrollBar.js',

    // ** Additional Plugins **
    pluginPath + 'ladda/spin.js',
    pluginPath + 'ladda/ladda.js',
    pluginPath + 'toastr/toastr.js',
    // pluginPath + 'notie/notie.js',
    pluginPath + 'jquery-validate/jquery.validate.js',
    pluginPath + 'jquery-validate/additional-methods.js',
    // pluginPath + 'clockpicker/bootstrap-clockpicker.js',
    // pluginPath + 'switchery/switchery.js',
    pluginPath + 'select2/select2.js',
    pluginPath + 'datatables/dataTables.min.js',
    pluginPath + 'datatables/dataTables.bootstrap.js',
    pluginPath + 'multiselect/jquery.multi-select.js',
    // pluginPath + 'bootstrapSelect/bootstrap-select.js',
    pluginPath + 'bootstrap-datepicker/bootstrap-datepicker.js',
    pluginPath + 'timepicker/jquery.timepicker.js',
    // pluginPath + 'summernote/summernote.js',
    // pluginPath + 'simplemde/simplemde.min.js',
    // pluginPath + 'Chartjs/Chart.js',
    // pluginPath + 'alertify/alertify.js',
    // pluginPath + 'easypiecharts/jquery.easypiechart.js',
    // pluginPath + 'metisMenu/metisMenu.js',

    pluginPath + 'fullcalendar/lib/moment.min.js',
    pluginPath + 'fullcalendar/fullcalendar.js',
    pluginPath + 'fullcalendar-scheduler/scheduler1.js',
    pluginPath + 'fullcalendar/draggable.js',
    pluginPath + 'jquery-minicolors/jquery.minicolors.min.js',

],'public/assets/js/core/plugins.js')

.combine([
    pluginPath + 'select2/select2.js',
],'public/assets/js/core/matches-plugins.js')

.js('resources/assets/js/app.js','public/assets/js/')

.js('resources/assets/js/app-messages.js', 'public/frontend/js/app-messages.js')

.js('resources/assets/js/views/front/matches/scheduleresults.js', 'public/frontend/js/scheduleresults.js')

.js('resources/assets/js/views/front/tournament/tournamenthistory.js', 'public/frontend/js/tournamenthistory.js')

.sass('resources/assets/sass/laraspace.scss', 'public/assets/css/')

.sass('resources/assets/sass/frontend/main.scss', 'public/frontend/css/')

.sass('resources/assets/sass/frontend/theme-1.scss', 'public/frontend/css/')

.sass('resources/assets/sass/frontend/theme-2.scss', 'public/frontend/css/')

.sass('resources/assets/sass/frontend/theme-3.scss', 'public/frontend/css/')

.sass('resources/assets/sass/frontend/theme-4.scss', 'public/frontend/css/')

.sass('resources/assets/sass/frontend/theme-5.scss', 'public/frontend/css/')

.sass('resources/assets/sass/frontend/theme-6.scss', 'public/frontend/css/')

.version();

mix.sass('resources/assets/sass/tvpresentation/app.scss', 'public/tvpresentation/css');

// mix.sass('resources/assets/sass/tvpresentation/app.scss', 'public/tvpresentation/css')
//     .options({
//         postCss: [
//             require("postcss-custom-properties")
//         ]
//     });

mix.copy('node_modules/@fortawesome/fontawesome-pro/webfonts', 'public/assets/webfonts');
mix.copy('node_modules/@fortawesome/fontawesome-pro/webfonts', 'public/frontend/fonts/webfonts');
