const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js/aardvark-seo.js').vue({ version: 2 });
mix.styles('resources/css/app.css', 'public/css/aardvark-seo.css');
