var gulp = require('gulp'),
    sass = require('gulp-sass'),
    del = require('del'),
    jade = require('gulp-jade-php');

gulp.task('styles', function () {
    gulp.src('./assets/src/scss/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./assets/dist/css'));
});

gulp.task('templates', function() {
    gulp.src('./application/jade/**/*.jade')
        .pipe(jade({
            locals: {
                title: 'OMG THIS IS THE TITLE'
            }
        }))
        .pipe(gulp.dest('./application/views/'));
});

// Clean
gulp.task('clean', function() {
    return del(['assets/dist/styles', 'assets/dist/scripts']);
});

gulp.task('sass:watch', ['clean'], function () {
    gulp.watch('./assets/src/scss/**/*.scss', ['styles']);
    gulp.watch('./application/jade/**/*.jade', ['templates']);
});

gulp.task('default', function() {
    gulp.start('styles');
    gulp.start('templates');
});