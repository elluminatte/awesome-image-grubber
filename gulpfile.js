var gulp = require('gulp'),
    sass = require('gulp-sass'),
    del = require('del');

gulp.task('styles', function () {
    gulp.src('./assets/src/scss/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./assets/dist/css'));
});

// Clean
gulp.task('clean', function() {
    return del(['assets/dist/styles', 'assets/dist/scripts', 'assets/dist/images']);
});

gulp.task('sass:watch', ['clean'], function () {
    gulp.watch('./assets/src/scss/**/*.scss', ['styles']);
});

gulp.task('default', function() {
    gulp.start('styles');
});