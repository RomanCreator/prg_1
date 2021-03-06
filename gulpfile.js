var gulp = require('gulp');
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less('bootstrap/bootstrap.less', 'public/css/bootstrap.css');
    mix.less('fontawesome/font-awesome.less', 'public/css/font-awesome.css');
    mix.less('backend/*.less', 'public/css/backend.css');
    mix.less('frontend/frontend.less', 'public/css/style.css');

    mix.scripts('jquery/*.js', 'public/js/jquery.js');
    mix.scripts('frontend/*.js', 'public/js/frontend.js');
    mix.scripts('bootstrap/bootstrap.js', 'public/js/bootstrap.js');
    mix.scripts('backend/*.js', 'public/js/backend.js');

    mix.copy('resources/assets/img', 'public/img');
    mix.copy('resources/assets/fonts', 'public/fonts');
    mix.copy('resources/assets/js/lib', 'public/js/');
});



