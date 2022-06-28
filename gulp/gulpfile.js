const gulp = require('gulp');
const { src, dest, task } = require('gulp');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const minifyCSS = require('gulp-minify-css');
const autoprefixer = require('gulp-autoprefixer');
const rename = require('gulp-rename');
const ngAnnotate = require('gulp-ng-annotate')


gulp.task('default', () => {

	var files = [
		'../public/assets/plugins/bootstrap/css/bootstrap.min.css',
		'../public/assets/plugins/simple-ine/css/simple-line-icons.css',
		'../public/assets/css/custom.css',
		'../public/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
		'../public/assets/plugins/admin/scripts/ui-cropper.css',
		'../public/assets/plugins/admin/scripts/jquery-cropper/croppie.css'
	];

	gulp
	.src(files)
	.pipe(minifyCSS())
	.pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
	.pipe(concat('combined.min.css'))
	.pipe(dest('../public/assets/dist/css'));
});

function cssWeb() {

	var files = [
		'../public/assets/plugins/bootstrap/css/bootstrap.min.css',
		'../public/assets/plugins/simple-ine/css/simple-line-icons.css',
		'../public/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
		'../public/assets/css/web_custom.css'
	];

	gulp
	.src(files)
	.pipe(minifyCSS())
	.pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
	.pipe(concat('combined_web.min.css'))
	.pipe(dest('../public/assets/dist/css'));
};

exports.cssweb = cssWeb;

function coreJS() {

	var files = [
		'../public/assets/plugins/admin/scripts/core/app.js',
		'../public/assets/plugins/admin/scripts/core/services.js',
		'../public/assets/plugins/admin/scripts/core/students/students_profile_controller.js',
		'../public/assets/plugins/admin/scripts/core/students/students_controller.js',
		'../public/assets/plugins/admin/scripts/core/students/students_attendance_controller.js',
		'../public/assets/plugins/admin/scripts/core/students/students_performance_controller.js',
		'../public/assets/plugins/admin/scripts/core/dropdownMaster/DropDownMasterController.js',
		'../public/assets/plugins/admin/scripts/core/center/center_controller.js',
		'../public/assets/plugins/admin/scripts/core/center/center_report_controller.js',
		'../public/assets/plugins/admin/scripts/core/communications/smsTempCtrl.js',
		'../public/assets/plugins/admin/scripts/core/communications/communicationCtrl.js',
		'../public/assets/plugins/admin/scripts/core/communications/sendMessageCtrl.js',
		'../public/assets/plugins/admin/scripts/core/communications/communicationListCtrl.js',
		'../public/assets/plugins/admin/scripts/core/communications/emailTempCtrl.js',
		'../public/assets/plugins/admin/scripts/core/inventories/stock_controller.js',
		'../public/assets/plugins/admin/scripts/core/inventories/Request_controller.js',
		'../public/assets/plugins/admin/scripts/core/inventories/Inventory_controller.js',
		'../public/assets/plugins/admin/scripts/core/inventories/Company_controller.js',
		'../public/assets/plugins/admin/scripts/core/settings/settings_controller.js',
		'../public/assets/plugins/admin/scripts/core/leads/leads_controller.js',
		'../public/assets/plugins/admin/scripts/core/leads/master_leads_controller.js',
		'../public/assets/plugins/admin/scripts/core/parameters/Parameters_controller.js',
		'../public/assets/plugins/admin/scripts/core/roles/user_controller.js',
		'../public/assets/plugins/admin/scripts/core/roles/access_rights_controller.js',
		'../public/assets/plugins/admin/scripts/core/roles/roles_controller.js',
		'../public/assets/plugins/admin/scripts/core/city/city_controller.js',
		'../public/assets/plugins/admin/scripts/core/payments/payments_controller.js',
		'../public/assets/plugins/admin/scripts/core/payments/coupon_controller.js',
		'../public/assets/plugins/admin/scripts/core/payments/pay_type_category_controller.js',
		'../public/assets/plugins/admin/scripts/core/payments/pay_type_price_controller.js',
		'../public/assets/plugins/admin/scripts/core/payments/accounts_controller.js',
		'../public/assets/plugins/admin/scripts/core/staff/staff_attendance_controller.js',
		'../public/assets/plugins/admin/scripts/core/parents/parents_controller.js',
		'../public/assets/plugins/admin/scripts/core/sales/sales_controller.js',
		'../public/assets/plugins/admin/scripts/core/clients/clients_controller.js',
		'../public/assets/plugins/admin/scripts/core/events/events_controller.js',
	];

  gulp
  .src(files)
  	.pipe(concat('combined.min.js'))
  	.pipe(ngAnnotate())
	.pipe(uglify())
	.pipe(dest('../public/assets/dist/js'));
};

exports.js = coreJS;

function pluginJS() {

	var files = [
		'../public/assets/plugins/popper.js',
		'../public/assets/plugins/bootstrap/js/bootstrap.min.js',
		'../public/assets/plugins/bootbox.min.js',
		'../public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
		'../public/assets/plugins/echarts.min.js',
		'../public/assets/plugins/admin/scripts/angular.min.js',
		'../public/assets/plugins/admin/scripts/angular-sanitize.js',
		'../public/assets/plugins/admin/scripts/ng-file-upload.min.js',
		'../public/assets/plugins/admin/scripts/ng-file-upload-shim.min.js',
		'../public/assets/plugins/admin/scripts/jcs-auto-validate.js',
		'../public/assets/plugins/admin/scripts/jquery-cropper/croppie.js',
		'../public/assets/plugins/admin/scripts/ui-cropper.js',
		'../public/assets/plugins/admin/scripts/core/custom.js',
	];

  gulp
  .src(files)
  	.pipe(concat('bundle.min.js'))
	.pipe(uglify())
	.pipe(dest('../public/assets/dist/js'));
};

exports.jsbundle = pluginJS;

function coreWebJS() {

	var files = [
		'../public/assets/plugins/admin/scripts/core/custom.js',
		'../public/assets/plugins/admin/scripts/core/app.js',
		'../public/assets/plugins/admin/scripts/core/services.js',
		'../public/assets/plugins/admin/scripts/core/web/reg_controller.js',
		'../public/assets/plugins/admin/scripts/core/web/lead_controller.js',
		'../public/assets/plugins/admin/scripts/core/web/demo_schedule_controller.js',
		'../public/assets/plugins/admin/scripts/core/web/renewal_controller.js',
		'../public/assets/plugins/admin/scripts/core/web/signup_controller.js',
		'../public/assets/plugins/admin/scripts/core/web/client_payment_controller.js',
	];

  gulp
  .src(files)
  	.pipe(concat('combined_web.min.js'))
  	.pipe(ngAnnotate())
	.pipe(uglify())
	.pipe(dest('../public/assets/dist/js'));
};

exports.jsweb = coreWebJS;

function pluginWebJS() {

	var files = [
		'../public/assets/plugins/popper.js',
		'../public/assets/plugins/bootstrap/js/bootstrap.min.js',
		'../public/assets/plugins/bootbox.min.js',
		'../public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
		'../public/assets/plugins/echarts.min.js',
		'../public/assets/plugins/admin/scripts/angular.min.js',
		'../public/assets/plugins/admin/scripts/angular-sanitize.js',
		'../public/assets/plugins/admin/scripts/ng-file-upload.min.js',
		'../public/assets/plugins/admin/scripts/ng-file-upload-shim.min.js',
		'../public/assets/plugins/admin/scripts/jcs-auto-validate.js',
		'../public/assets/plugins/admin/scripts/jquery-cropper/croppie.js',
		'../public/assets/plugins/admin/scripts/ui-cropper.js'
	];

  gulp
  .src(files)
  	.pipe(concat('bundle_web.min.js'))
	.pipe(uglify())
	.pipe(dest('../public/assets/dist/js'));
};

exports.jsbundleweb = pluginWebJS;