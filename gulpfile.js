var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.scripts([
            'jquery.dataTables.min.js',
            'dataTables.foundation.js'],
        "public/js/table.min.js"
    );
    mix.scripts([
            '../bower/foundation/js/foundation/foundation.reveal.js',
            '../bower/foundation/js/foundation/foundation.tab.js',
            '../bower/jquery-validation/dist/jquery.validate.min.js',
            'svg.js'],
        "public/js/app.min.js"
    );
});


// concat all application css files.

elixir(function(mix) {
    mix.styles([
        'dataTables.foundation.min.css',
        'site.css'
    ], 'public/css/app.min.css')
});


elixir(function(mix) {
    mix.copy([
        'resources/assets/bower/foundation/css/foundation.min.css'],
        'public/css/foundation.min.css')
});


elixir(function(mix) {
    mix.copy([
        'resources/assets/bower/angular/angular.min.js'],
        'public/js/angular.min.js')
});

