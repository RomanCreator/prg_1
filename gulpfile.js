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
});

elixir(function(mix) {
    mix.scripts('bootstrap/bootstrap.js', 'public/js/bootstrap.js');
});

elixir(function(mix) {
    mix.scripts('jquery/*.js', 'public/js/jquery.js');
});

elixir(function(mix) {
    mix.less('fontawesome/font-awesome.less', 'public/css/font-awesome.css');
});

elixir(function(mix) {
    mix.less('backend/*.less', 'public/css/backend.css');
});

elixir(function(mix) {
    mix.scripts('backend/*.js', 'public/js/backend.js');
});

elixir(function(mix) {
    mix.copy('resources/assets/img', 'public/img');
})
