'use strict';

import gulp from 'gulp';
import del from 'del';
import browsersyncLib from 'browser-sync';
const browsersync = browsersyncLib.create();
import plumber from 'gulp-plumber';
import sass from 'gulp-sass';
import autoPrefixer from 'gulp-autoprefixer';
import babel from 'gulp-babel';
import uglify from 'gulp-uglify';
import sourcemaps from 'gulp-sourcemaps';
import rename from 'gulp-rename';
import include from 'gulp-include';

const base = 'wp-content/themes/_sw/';

// Clean dist folder
const gClean = function(done) {
  del(base + 'dist/**', {
    force: true
  });
  done();
};
gClean.displayName = "Directory Clean";
gClean.description = "Cleans out the dist folder";
gulp.task(gClean);

// Initialize Browsersync
const gBrowsersync = function(done) {
  browsersync.init({
    open: 'external',
    host: 'socialworks.local.com',
    proxy: 'socialworks.local.com'
  });
  done();
};
gBrowsersync.displayName = "Browsersync";
gBrowsersync.description = "Initializes Browsersync";
gulp.task(gBrowsersync);

// Reload Browser Sync
const gReload = function(done) {
  browsersync.reload();
  done();
}
gReload.displayName = "Reload Browsersync";
gBrowsersync.description = "Refreshes the browser window";

// Compile and minify CSS (SASS) files
const gSass = function(done) {
  return gulp.src(base + 'src/scss/**/*.scss')
    .pipe(
      sass({
        outputStyle: 'compressed'
      })
      .on('error', function(e) {
        sass.logError.bind(this)(e);
        console.error('\x07\x07');
      })
    )
    .pipe(
      autoPrefixer({
        browsers: ['> 1%', 'last 2 versions', 'Safari >= 7']
      })
    )
    .pipe(
      rename({
        suffix: '.min'
      })
    )
    .pipe(
      gulp.dest(base + 'dist/css')
    )
    .pipe(
      browsersync.stream()
    );
};
gSass.displayName = "SCSS Compilation";
gSass.description = "Compiles and minifies scss";
gulp.task(gSass);

// Compile ES6 functionality, concatenate, minify, and generate source maps for javascript files
const gJs = function(done) {
  return gulp.src(base + 'src/js/**/[^_]*.js')
    .pipe(plumber({
      errorHandler: function(err) {
        console.log(err);
        console.error('\x07\x07');
      }
    }))
    .pipe(
      sourcemaps.init()
    )
    .pipe(
      babel({
        presets: ['env']
      })
    )
    .pipe(
      include({
        includePaths: [
          'node_modules',
          base + 'src/js/**'
        ]
      })
    )
    .pipe(
      uglify()
    )
    .pipe(
      rename({
        suffix: '.min'
      })
    )
    .pipe(
      sourcemaps.write('./')
    )
    .pipe(
      gulp.dest(base + 'dist/js')
    );
};
gJs.displayName = "JavaScript Compilation";
gJs.description = "Compiles and minifies javascript";
gulp.task(gJs);

// Watch files for changes
const gWatch = function(done) {
  gulp.watch(base + 'src/scss/**/*.scss', gSass);
  gulp.watch(base + '**/*.{php,html,svg}', gReload);
  gulp.watch(base + 'src/js/**/*.js', gulp.series(gJs, gReload));
  done();
};
gWatch.displayName = "File Watcher";
gWatch.description = "Recompile files and refresh Browsersync when any files are updated";

// Start standard development task (launches Browsersync)
gulp.task('default', gulp.series(gClean, gSass, gJs, gBrowsersync, gWatch));

// Build files without running browserSync
gulp.task('build', gulp.series(gClean, gSass, gJs));
