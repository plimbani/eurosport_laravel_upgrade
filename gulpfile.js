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

elixir((mix) => {
    mix.sass('app.scss')
       .webpack('app.js')
       .webpack('pitch.js')
       .webpack('tournament.js')
       .copy(
            'node_modules/bootstrap-sass/assets/fonts/bootstrap',
            'public/fonts/bootstrap'
        );
    mix.remove(['public/build/css','public/build/js']);
    mix.version(['css/app.css','js/pitch.js']);
});

//     mix.version(['css/app.css','js/tournament.js']);
// });
    // Add ESLint
    mix.eslint(
    	 'resources/assets/js/*.js'
    );

});


/*gulp.task('lint', () => {
    // ESLint ignores files with "node_modules" paths.
    // So, it's best to have gulp ignore the directory as well.
    // Also, Be sure to return the stream from the task;
    // Otherwise, the task may end before the stream has finished.
   
        // eslint() attaches the lint output to the "eslint" property
        // of the file object so it can be used by other modules.
        .pipe(eslint())
        // eslint.format() outputs the lint results to the console.
        // Alternatively use eslint.formatEach() (see Docs).
        .pipe(eslint.format())
        // To have the process exit with an error code (1) on
        // lint error, return the stream and pipe to failAfterError last.
        .pipe(eslint.failAfterError());
});

gulp.task('default', ['lint'], function () {
    // This will only run if the lint task is successful...
});
*/