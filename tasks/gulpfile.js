// Include project requirements.
var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    sass = require('gulp-sass'),
    livereload = require('gulp-livereload'),
    watch = require('gulp-watch'),
    concat = require('gulp-concat'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    rename = require("gulp-rename");

// Sets assets folders.
var dirs = {
    js: '../assets/js',
    css: '../assets/css',
    vendor: '../assets/vendor',
    sass: '../assets/scss'
};

gulp.task('js-custom', function () {
    return gulp.src([dirs.js + '/src/*.js'])
        .pipe(concat('alg-wc-wish-list-pro.js'))
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest(dirs.js))
        .pipe(concat('alg-wc-wish-list-pro.min.js'))
        .pipe(uglify({
            preserveComments:'license'
        }).on('error', function(e){
            console.log(e.message); return this.end();
        }))
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest(dirs.js))
        .pipe(livereload());
});

gulp.task('sass', function () {
    gulp.src(dirs.sass + '/*.scss')
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .on('error', sass.logError)
        .pipe(rename("alg-wc-wish-list-pro.min.css"))
        .pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade: false
        }))
        .pipe(gulp.dest(dirs.css));

    return gulp.src(dirs.sass + '/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'expanded'
        }))
        .on('error', sass.logError)
        .pipe(rename("alg-wc-wish-list-pro.css"))
        .pipe(autoprefixer({
            browsers: ['last 3 versions'],
            cascade: false
        }))
        .pipe(gulp.dest(dirs.css))
        .pipe(livereload())
        .pipe(sourcemaps.write('../maps'))
        .pipe(gulp.dest(dirs.css));
});

gulp.task('copy.libs', function() {
  console.log("Moving Balloon to Project ");

  //Balloon
  gulp.src("./node_modules/balloon-css/*.css")
        .pipe(gulp.dest(dirs.vendor + "/balloon-css/css"));
});

gulp.task('watch', gulp.series('sass', 'js-custom', function () {
    livereload.listen();
	gulp.watch(dirs.js + '/src/*.js', gulp.series('js-custom'));
	gulp.watch(dirs.sass + '/**/*.scss',gulp.series('sass'));
}));

gulp.task('default', function () {
    gulp.start(['sass', 'scripts']);
});