/* global require, exports */

const gulp = require('gulp');
const stylelint = require('gulp-stylelint');
const eslint = require('gulp-eslint');
const scss = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const cleancss = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const notify = require('gulp-notify');
const plumber = require('gulp-plumber');
const livereload = require('gulp-livereload');
const del = require('del');
const babel = require('gulp-babel');
const minify = require('gulp-uglify');
const concat = require('gulp-concat');

const path_js = 'includes/assets/js/src/*.js';
const path_js_admin = 'admin/js/blocks/src/*.js';
const path_scss = 'includes/assets/css/scss/*.scss';
const path_dest_css = 'includes/assets/css';
const path_dest_js = 'includes/assets/js';
const path_dest_js_admin = 'admin/js/blocks';


let error_handler = {
  errorHandler: notify.onError({
    title: 'Gulp',
    message: 'Error: <%= error.message %>'
  })
};

function lint_css() {
  return gulp.src(path_scss)
    .pipe(plumber(error_handler))
    .pipe(stylelint({
      reporters: [{ formatter: 'string', console: true}]
    }))
    .pipe(livereload());
}

function lint_js() {
  return gulp.src(path_js)
    .pipe(plumber(error_handler))
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failAfterError())
    .pipe(livereload());
}

function lint_js_admin() {
  return gulp.src(path_js_admin)
    .pipe(plumber(error_handler))
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failAfterError())
    .pipe(livereload());
}

function fix_css() {
  return gulp.src(path_scss)
    .pipe(plumber(error_handler))
    .pipe(stylelint({
      reporters: [{ formatter: 'string', console: true}],
      fix: true
    }))
    .pipe(gulp.dest('includes/assets/css/scss/'));
//  This outputs to 'assets/scss/**/*.scss', manual copy to right directory for now..
}

function fix_js() {
  return gulp.src(path_js)
    .pipe(plumber(error_handler))
    .pipe(eslint({fix:true}))
    .pipe(eslint.format())
    .pipe(gulp.dest('includes/assets/js/src'));
}

function fix_js_admin() {
  return gulp.src(path_js_admin)
    .pipe(plumber(error_handler))
    .pipe(eslint({fix:true}))
    .pipe(eslint.format())
    .pipe(gulp.dest('admin/js/admin-blocks-src'));
}

// TODO configure gulp-sass-glob to auto include all .scss files
function sass() {
  // clean_css_maps;
  return gulp.src(path_scss)
    .pipe(plumber(error_handler))
    .pipe(sourcemaps.init())
    .pipe(scss().on('error', scss.logError))
    .pipe(autoprefixer())
    .pipe(cleancss({rebase: false, level :2}))
    .pipe(sourcemaps.write('/maps/'))
    .pipe(gulp.dest(path_dest_css))
    .pipe(livereload());
}

function uglify() {
  gulp.parallel(clean_js, clean_js_maps);
  return gulp.src(path_js)
    .pipe(plumber(error_handler))
    .pipe(sourcemaps.init())
    .pipe(babel({
      presets: ['@babel/env']
    }))
    .pipe(minify())
    .pipe(sourcemaps.write('/maps/'))
    .pipe(gulp.dest(path_dest_js))
    .pipe(livereload());
}

function uglify_admin() {
  gulp.parallel(clean_js_admin, clean_js_maps_admin);
  return gulp.src(path_js_admin)
    .pipe(plumber(error_handler))
    .pipe(sourcemaps.init())
    .pipe(babel({
      presets: ['@babel/env']
    }))
    .pipe(concat('admin-blocks.min.js'))
    .pipe(minify())
    .pipe(sourcemaps.write('/maps/'))
    .pipe(gulp.dest(path_dest_js_admin))
    .pipe(livereload());
}

function clean_css_maps () {
  return del(path_dest_css+'/maps/*');
}

function clean_js_maps () {
  return del(path_dest_js+'/maps/*');
}

function clean_js_maps_admin () {
  return del(path_dest_js_admin+'/maps/*');
}

function clean_js () {
  return del([path_dest_js+'/*.js', '!'+path_dest_js+'/vue*', !path_dest_js+'/src' ]);
}

function clean_js_admin () {
  return del([path_dest_js_admin+'/*.js', !path_dest_js_admin+'/src' ]);
}

function watch() {
  livereload.listen({'port': 35730});
  gulp.watch(path_scss, gulp.series(lint_css, sass));
  gulp.watch(path_js, gulp.series(lint_js, uglify));
  gulp.watch(path_js_admin, gulp.series(lint_js_admin, uglify_admin));
}

exports.fix = gulp.parallel(fix_css, fix_js, fix_js_admin);
exports.sass = sass;
exports.clean_all= gulp.parallel(clean_js, clean_js_admin, clean_css_maps, clean_js_maps, clean_js_maps_admin);
exports.clean_css = clean_css_maps;
exports.clean_js = gulp.parallel(clean_js, clean_js_maps);
exports.clean_js_admin = gulp.parallel(clean_js_admin, clean_js_maps_admin);
exports.uglify = uglify;
exports.uglify_admin = uglify_admin;
exports.watch = watch;
exports.test = gulp.parallel(lint_css, lint_js, lint_js_admin);
exports.default = gulp.series(lint_css, lint_js, sass, uglify, uglify_admin);

