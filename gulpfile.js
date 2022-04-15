const { src, dest, series, watch } = require('gulp');

const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const source = require('vinyl-source-stream');
const cleanCSS = require('gulp-clean-css');

const paths = {
    css: './assets/css', 
    js: './assets/js', 
    dist: './assets/dist'
};

const buildScript = function() {
    return src([
        `${paths.js}/donation.js`
    ])
    .pipe(uglify())
    .pipe(rename('donation.min.js'))
    .pipe(dest(paths.dist));
};

const buildStyles = function() {
    return src([
            './node_modules/bootstrap/dist/css/bootstrap.css', 
            `${paths.css}/*.css`
        ])
        .pipe(concat('style.css'))
        .pipe(dest(paths.dist))
        .pipe(rename('style.min.css'))
        .pipe(cleanCSS())
        .pipe(dest(paths.dist));
};

const watchStyles = function() {
    return watch(`${paths.css}/*.css`, series(buildStyles));
};

const watchScripts = function() {
    return watch(`${paths.js}/*.js`, series(buildScript));
};

exports.default = series(buildStyles, buildScript);
exports.watchStyles = watchStyles;
exports.watchScripts = watchScripts;