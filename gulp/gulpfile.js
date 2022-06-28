const gulp = require('gulp');
const { src, dest, task } = require('gulp');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const minifyCSS = require('gulp-minify-css');
const autoprefixer = require('gulp-autoprefixer');
const rename = require('gulp-rename');
const ngAnnotate = require('gulp-ng-annotate')

// gulp.task('default', () => {
// 	gulp
// 	.src('input/*.js')
// 	.pipe(uglify())
// 	.pipe(rename({ extname: '.min.js' }))
// 	.pipe(dest('output/'));
// });

// exports.copy = copy;

gulp.task('default', () => {

	var files = [
		'../public/assets/plugins/bootstrap/css/bootstrap.min.css',
		'../public/assets/plugins/simple-ine/css/simple-line-icons.css'
	];

	gulp
	.src(files)
	.pipe(minifyCSS())
	.pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
	.pipe(concat('combined.min.css'))
	.pipe(dest('../public/assets/dist/css'));
});

// exports.css = combineCSS;

function coreJS() {

	var files = [
		'../public/assets/plugins/bootstrap/js/bootstrap.min.js',
		'../public/assets/plugins/bootbox.min.js',
	];

  gulp
  .src(files)
	.pipe(uglify())
	.pipe(concat('combined.min.js'))
	.pipe(dest('../public/assets/dist/js'));
};

exports.js = coreJS;

// exports.js = combineJS;

// function coreJS() {

// 	var files = [
// 		'../assets/js/jquery.validate.min.js',
// 		'../assets/js/axios.min.js',
// 		'../assets/js/vue.min.js',
// 		'../assets/plugins/bootstrap/js/bootstrap.min.js',
// 		'../assets/plugins/owlcarousel/owl.carousel.min.js',
// 		'../assets/plugins/bxslider/dist/jquery.bxslider.min.js',
// 		'../assets/plugins/fancybox-master/dist/jquery.fancybox.min.js',
// 		'../assets/js/jquery.ticker.min.js',
// 	];

//   	return src(files)
//   		.pipe(concat('core.min.js'))
//   		.pipe(ngAnnotate())
//   		.pipe(uglify())
//   		.pipe(dest('../assets/js/'));
// }

// exports.corejs = coreJS;

// function customJS() {

// 	var files = [
// 		'../assets/js/custom.js'
// 	];

//   	return src(files)
//   		.pipe(concat('custom.min.js'))
//   		.pipe(ngAnnotate())
//   		.pipe(uglify())
//   		.pipe(dest('../assets/js/'));
// }

// exports.customjs = customJS;