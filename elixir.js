"use strict";

const Elixir = require('laravel-elixir');
const compile = require('gulp-ng-html2js');

class CompileTemplatesTask extends Elixir.Task {

    constructor(name, paths, options) {
        super(name, null, paths);

        this.options = options;
    }

    gulpTask() {
        return gulp
            .src(this.src.path)
            .pipe(compile(this.options))
            .on('error', this.onError())
            .pipe(this.concat())
            .pipe(this.minify())
            .pipe(this.saveAs(gulp))
            .pipe(this.onSuccess());
    }

    registerWatchers() {
        this.watch(this.src.baseDir)
            .ignore(this.output.path);
    }

}

Elixir.extend('templates', function (src, output, baseDir, options) {
    new CompileTemplatesTask(
        'templates', new Elixir.GulpPaths().src(src, baseDir).output(output), options
    );
});