const elixir = require('laravel-elixir');

require('./elixir');

// disable argument mangling, so angular dependecies do not get renamed
elixir.config.js.uglify.options.mangle = false;
elixir.config.sourcemaps = true;

elixir((mix) => {
    elixir.webpack.mergeConfig({
        devtool: elixir.inProduction ? '' : 'source-map'
    });

    mix.copy('resources/assets/index.html', 'resources/views/index.blade.php');
    mix.copy('resources/assets/fonts', 'public/fonts');

    mix.copy('resources/assets/images/favicon.ico', 'public/favicon.ico');

    mix.webpack('app.js', 'public/js/app.js');
    mix.webpack('vendor.js');

    mix.sass('app.sass');
    mix.sass('vendor.sass');
    mix.sass('fonts.sass');

    mix.templates('**/*.html', './public/js/templates.js', 'resources/assets/views', { moduleName: 'app', prefix: '/views/' });
});
